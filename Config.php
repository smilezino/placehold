<?php

class Config {
    public static function getConfig() {

        $params = [];
        $pathinfo = self::getPathInfo();
        $pathinfo = array_filter(explode('/', $pathinfo), function($item){
            return !empty($item);
        });

        $params = array_merge($params, $pathinfo);
        $pathConfig = self::parseRequestPath($params);
        $paramsConfig = self::queryString();
        return array_merge($pathConfig, $paramsConfig);
    }

    public static function getPathInfo() {
        $scripName = $_SERVER['SCRIPT_NAME'];
        $requestURI = $_SERVER['REQUEST_URI'];
        $phpSelf = $_SERVER['PHP_SELF'];
        $pathinfo = $scripName==$phpSelf?$requestURI:substr($phpSelf, strpos($phpSelf, $scripName) + strlen($scripName));
        return  ($index=strpos($pathinfo, '?'))?substr($pathinfo, 0, $index):$pathinfo;
    }

    public static function parseRequestPath($params) {
        $requestPathConfig = array();

        list($size, $bgColor, $textColor, $text) = $params;

        $size = preg_replace('/[^0-9x]/', '', $size);
        $dimensions = explode('x', $size);
        $dimensions = array_slice($dimensions, 0, 2);

        if(!empty($dimensions[0])) {
            $requestPathConfig['w'] = $dimensions[0];
            $requestPathConfig['h'] = !empty($dimensions[1]) ? $dimensions[1] : $dimensions[0];
        }

        if( !empty($bgColor) && preg_match('/^[a-fA-Z0-9]{3,6}$/i', $bgColor) )
            $requestPathConfig['bgColor'] = strtolower($bgColor);

        if( !empty($textColor) && preg_match('/^[a-fA-Z0-9]{3,6}$/i', $textColor) )
            $requestPathConfig['textColor'] = strtolower($textColor);

        if(!empty($text)) {
            $requestPathConfig['text'] = urldecode($text);
        }

        return $requestPathConfig;
    }

    public static function queryString() {
        $queryString = array();

        if ( function_exists('mb_parse_str') )
            mb_parse_str($_SERVER['QUERY_STRING'], $queryString);
        else
            parse_str($_SERVER['QUERY_STRING'], $queryString);

        return $queryString;
    }
} 