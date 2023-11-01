<?php

use Aponahmed\Cmsstatic\manage\Model\Media;

function urlSlashFix($url)
{
    return preg_replace('/([^:])(\/{2,})/', '$1/', $url);
}

function getGlobal($index)
{
    return isset($GLOBALS['STCMS'][$index]) ? $GLOBALS['STCMS'][$index] : false;
}

function app()
{
    return getGlobal('app');
}

function getMedia($path)
{
    return Media::getInstanse($path);
}

function is_404()
{
    $is404 = getGlobal('error_404');
    if ($is404) {
        return true;
    }
    return false;
}

function ajax_url()
{
    return urlSlashFix(site_url() . "/ajax/");
}

//Template Functions 
require_once 'functions/hook.php';
require_once 'functions/template.php';
require_once 'functions/seo.php';
