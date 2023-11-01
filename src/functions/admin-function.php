<?php

use Aponahmed\Cmsstatic\manage\Model\Media;
use Aponahmed\Cmsstatic\manage\Model\Menu;

require_once 'hook.php';

function is_admin()
{
    return true;
}

function urlSlashFix($url)
{
    return preg_replace('/([^:])(\/{2,})/', '$1/', $url);
}
function getGlobal($index)
{
    return isset($GLOBALS['STCMSADMIN'][$index]) ? $GLOBALS['STCMSADMIN'][$index] : false;
}

function siteUrl()
{
    return getGlobal('site_url');
}

function app()
{
    return getGlobal('app');
}

function site_icon(...$sizes)
{
    $sizes = array_unique(array_merge([16, 32, 48, 128, 192], $sizes));
    $app = app();
    $iconUrl = trim($app->getSetting('favicon'));
    if (!empty($iconUrl)) {
        $imgPath = pathinfo($iconUrl);
        $img = new Media($imgPath['basename'], $imgPath['dirname']);
        foreach ($sizes as $size) {
            $hrf = $img->getSize($size);
            echo '<link rel="icon" href="' . $hrf . '" sizes="' . $size . 'x' . $size . '" >';
        }
    }
}


function __e($var)
{
    if (isset($var)) {
        echo $var;
    }
}

function getMessage()
{
    if (
        isset($_SESSION['message']) &&
        is_array($_SESSION['message']) &&
        isset($_SESSION['message']['text'])
    ) {
        $type = 'success';
        if (isset($_SESSION['message']['type'])) {
            $type = $_SESSION['message']['type'];
        }
        echo "<div class=\"notification-container\"><div class='user-notification $type'>" . $_SESSION['message']['text'] . "</div></div>";
        unset($_SESSION['message']);
    }
}

function admin_header()
{
    //var_dump($GLOBALS['STCMSADMIN']);
    require_once ROOT_DIR . "/manage/view/header.php";
}

function admin_footer()
{
    require_once ROOT_DIR . "/manage/view/footer.php";
}

function admin_sidebar()
{
    require_once ROOT_DIR . "/manage/view/sidebar.php";
}

function admin_topbar()
{
    require_once ROOT_DIR . "/manage/view/topbar.php";
}

function admin_url()
{
    return getGlobal('admin_url');
}

function nav_current($val = '', $index = 1)
{

    $segments = getGlobal('segments');
    if ($index < 0) {
        $fromLast = array_slice($segments, $index);
        $fromLastStr = implode('.', $fromLast);
        if ($segments && $fromLastStr === $val) {
            echo "nav-current";
        }
    } else {
        if ($segments && isset($segments[$index]) &&  $segments[$index] === $val) {
            echo "nav-current";
        }
    }
}

function admin_title()
{
    $pageTitle = getGlobal('admin_title');
    if ($pageTitle) {
        return $pageTitle;
    } else {
        return "Admin";
    }
}



function page_url()
{
    $root = admin_url();
    $segments = getGlobal('segments');
    unset($segments[0]);
    $segments = array_values($segments);

    echo urlSlashFix($root . "/" . $segments[0]);
}

function admin_page_title()
{
    $segments = getGlobal('segments');
    unset($segments[0]);
    $root = admin_url();
    $str = "<ul class='page-title-nav'>";
    $url = $root;
    $n = 0;
    if (count($segments) > 0) {
        foreach ($segments as $segment) {
            $n++;
            $url .= "/" . $segment;
            $url = urlSlashFix($url);
            $sep = "";
            if ($n > 1) {
                $sep = "has_prev";
            }
            $str .= "<li class='page-title-nav-item $sep'><a href=\"$url\">" . ucwords($segment) . "</a></li>";
        }
    } else {
        $str .= "<li class='page-title-nav-item'><a href=\"$url/\">Dashboard</a></li>";
    }

    $str .= "</ul>";
    echo $str;
}

function UploadSubdirSet($segments)
{
    unset($segments[0]); //remove media
    return  implode("/", $segments);
}

//Add New Button
function admin_page_new()
{
    $segments = getGlobal('segments');
    unset($segments[0]);
    $segments = array_values($segments);
    $array = ['pages', 'menu', 'sliders','static'];
    if (isset($segments[0])) {
        if ($segments[0] == 'media') {
            $subDir = UploadSubdirSet($segments);
            if (end($segments) != 'upload') {
                echo "<a class='add-new-btn' href='./upload'>Upload</a> <label  class='newDirectory' title='New Folder' onclick='createNewDirectory(\"$subDir\")'><svg viewBox='0 0 512 512'><path fill='none' stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='32' d='M256 112v288M400 256H112' /></svg></label>";
            }
        } else {
            if (in_array(end($segments), $array)) {
                $url = urlSlashFix(admin_url() . "/" . end($segments) . "/add/");
                echo "<a class='add-new-btn' href='$url'>Add New</a>";
            }
        }
    }
}

function menuSelect($name, $curent)
{
    return Menu::getSelect($name, $curent);
}

function admin_assets($path)
{

    $adminAssets = getGlobal('admin_assets_uri');
    $path = $adminAssets . "/" . $path;
    return urlSlashFix($path);
}

require_once 'admin-metabox.php';
