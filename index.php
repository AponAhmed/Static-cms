<?php

use Aponahmed\Cmsstatic\App;

//requre autoload
define('ROOT_DIR', dirname(__FILE__));
require_once './vendor/autoload.php';
//Global variables
require_once './src/global.php';
try {
    App::getInstance();
} catch (\Exception $e) {
    echo $e->getMessage();
}
