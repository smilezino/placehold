<?php
require('Config.php');
require('Image.php');
$config = Config::getConfig();
if(isset($config['w']) && isset($config['h'])) {
    $image = new Image($config);
    $image->output();
}
require('home.php');