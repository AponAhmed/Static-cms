<?php

use Aponahmed\Cmsstatic\Hook;
use Aponahmed\Cmsstatic\Utilities\MetaBoxs;
use Aponahmed\Cmsstatic\Performance;
use Aponahmed\Cmsstatic\shortcode\RandkeyShortcode;

$_p = new Performance();
$_p->start('system', "System", ['file' => __FILE__, 'line' => __LINE__]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//Non Dependent Functions
require_once 'functions/function-third.php';
$Auth = false;
$ajax = false;
$publicUrls = ['cart/', 'cart/add-item/', 'cart/remove-item/', 'cart/send/', 'contacts/send/', 'contacts/upload/'];
$page = null;
$hook =  Hook::getInstance();

$hook->add_filter('the_content', function ($str) {
    return autop($str);
}, 1);

$hook->add_action('ran_key_list', [RandkeyShortcode::class, 'getRandKeyList'], 2);

$metabox = MetaBoxs::getInstance();
$excrpt = [];
