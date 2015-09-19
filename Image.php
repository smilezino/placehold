<?php

class Image {

    private $w = 50;
    private $h = 50;
    private $text = '';
    private $bgColor = 'ccc';
    private $textColor = '666';
    private $alpha = 0;
    private $fontFile = "./msyh.ttf";

    private $image;


    public function __construct($config) {
        $this->w = (!empty($config['w'])) ? $config['w'] : $this->w;
        $this->h = (!empty($config['h'])) ? $config['h'] : $this->h;

        $this->bgColor = (!empty($config['bgColor'])) ? $config['bgColor'] : $this->bgColor;

        $this->textColor = (!empty($config['textColor'])) ? $config['textColor'] : $this->textColor;

        $this->text = (!empty($config['text'])) ? $config['text'] : $this->text;

        $this->alpha = (!empty($config['alpha'])) ? $config['alpha'] : $this->alpha;

        $this->create();
    }


    public function create() {
        $this->image = imagecreatetruecolor($this->w, $this->h);
        $this->fillColor();
        $this->fillText();
    }

    public function fillColor() {
        $rbg = $this->hex2rgb($this->bgColor);
        $bgColor = imagecolorallocatealpha($this->image, $rbg[0], $rbg[1], $rbg[2], 0);
        imagefilledrectangle($this->image, 0, 0, $this->w, $this->h, $bgColor);


    }

    public function fillText() {
        $rbg = $this->hex2rgb($this->textColor);
        $textColor = imagecolorallocatealpha($this->image, $rbg[0], $rbg[1], $rbg[2], 0);

        $size = $this->fontSize();

        $text = empty($this->text) ? $this->w . "×" . $this->h : $this->text;
        list($x, $y) = $this->centerPosition($text, $size);
        imagettftext($this->image, $size, 0, $x, $y, $textColor, $this->fontFile, $text);


    }

    public function output() {
        header("Content-Type: image/png");

        imagepng($this->image);

        imagedestroy($this->image);
    }


    public function hex2rgb($color) {
        $color = str_replace("#", "", $color);


        if(strlen($color) == 3) {
            $r = hexdec( substr($color,0,1).substr($color,0,1) );
            $g = hexdec( substr($color,1,1).substr($color,1,1) );
            $b = hexdec( substr($color,2,1).substr($color,2,1) );
        } else {
            $r = hexdec( substr($color,0,2) );
            $g = hexdec( substr($color,2,2) );
            $b = hexdec( substr($color,4,2) );
        }

        $rgb = array($r, $g, $b);

        return $rgb;
    }

    public function centerPosition($text, $size) {
        $imageWidth = imagesx($this->image);
        $imageHeight = imagesy($this->image);

        $box = imagettfbbox($size, 0, $this->fontFile, $text);

        $textWidth = abs($box[6]) + abs($box[4]);
        $textHeight = abs($box[7]) + abs($box[1]) ;

        $x = ($imageWidth - $textWidth) / 2;
        $y = ($imageHeight + $textHeight) / 2;

        return array($x, $y);
    }

    public function fontSize() {
        $imageWidth = imagesx($this->image);
        $imageHeight = imagesy($this->image);

        $width = round($imageWidth / 10);
        $height  = round($imageHeight / 10);

        $size =  min($width, $height);

        if($size < 12) $size = 12;

        //if($size > 48) $size = 48;

        return $size;
    }

} 