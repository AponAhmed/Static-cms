<?php

use Aponahmed\Cmsstatic\manage\Controllers\FooterController;
use Aponahmed\Cmsstatic\manage\Controllers\ThemesController;
use Aponahmed\Cmsstatic\manage\Model\Media;
use Aponahmed\Cmsstatic\manage\Model\Menu;
use Aponahmed\Cmsstatic\manage\Model\Slider;

function is_admin()
{
    return false;
}

function is_home()
{
    $isHome = getGlobal('is_home');
    if ($isHome) {
        return true;
    }
    return false;
}
function is_attachment()
{
    return getGlobal('media');
}
function current_slug()
{
    return getGlobal('slug');
}
function get_content()
{
    $page = getGlobal('page');
    if ($page && $page) {
        return $page->content; //return $hook->apply_filters('output', $page->content);
    }
}

function page()
{
    $page = getGlobal('page');
    return $page;
}

function page_object()
{
    $page = getGlobal('page-instanse');
    return $page;
}


function get_breadcrumb()
{
    $items = get_breadcrumb_items();
    $htm = "<div class=\"breadcrumb\"><ul>";
    // Extract the first two elements
    if (count($items) > 3) {
        $firstTwo = array_slice($items, 0, 2);
        // Extract the last element
        $lastElement = end($items);
        $items = $firstTwo;
        $items[] = $lastElement;
    }

    $n = count($items);
    foreach ($items as $key => $value) {
        //var_dump($value);
        $htm .= "<li class=\"breadcrumb-item\">";
        $url = $value['item'];
        if ($n == ($key + 1) || !$value['link']) {
            $htm .= "<span title=\"$value[name]\">$value[name]</span>";
        } else {
            $htm .= "<a href=\"$url\" title=\"$value[name]\">$value[name]</a>";
        }
        $htm .= "</li>";
    }
    $htm .= "</ul></div>";
    echo $htm;
}

function get_breadcrumb_json()
{
    $data = [
        "@context" => "http://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => []
    ];
    $items = get_breadcrumb_items();
    $n = 0;
    foreach ($items as $k => $v) {
        if ($v['link']) {
            $n++;
            $item = [
                "@type" => "ListItem",
                "position" => $n,
                "name" => $v['name'],
            ];

            $item["item"] = $v['item'];
            $data['itemListElement'][] = $item;
        }
    }
    echo "<script type=\"application/ld+json\">" . json_encode($data) . "</script>" . PHP_EOL;
}

function get_breadcrumb_items()
{
    $segments = app()->route->segments;
    $breadcrumbs = array();
    $siteurl = site_url();
    $breadcrumbs[] = [
        'name' => 'Home',
        'item' => $siteurl,
        'link' => true
    ];
    $n = count($segments);
    foreach ($segments as $k => $segment) {
        $link = false;
        $name = ucwords(str_replace('-', ' ', $segment));
        $item = urlSlashFix($siteurl . "/" . $segment);
        if (($k + 1) == $n) {
            $link = true;
            if (is_404()) {
                $link = false;
                $name = "404 Page Not Found";
                $item = "";
            }
        }

        $breadcrumbs[] = [
            'name' => $name,
            'item' => $item,
            'link' => $link
        ];
    }
    return $breadcrumbs;
}

function sizeCalc($size, $columnBasis)
{
    $app = app();
    $gap = 40;
    $device = $app::getDeviceType();
    $aprxWidth = $app::getApproxDeviceWidth();
    if ($columnBasis) {
        switch ($device) {
            case 'mobile':
                $getSizeCom = isset($size[2]) ? $size[2] : 12;
                $scwidth = $aprxWidth - ((12 / $getSizeCom) * $gap);
                $width = round(($scwidth / 12) * $getSizeCom);
                break;
            case 'tablet':
                $getSizeCom = isset($size[1]) ? $size[1] : 6;
                $scwidth = $aprxWidth - ((12 / $getSizeCom) * $gap);
                $width = round(($scwidth / 12) * $getSizeCom);
                break;
            default:
                $getSizeCom = isset($size[0]) ? $size[0] : 6;
                //Here 196 is the (1366 - self::$containerWidth) only for max window > self::$containerWidth
                $scwidth = $aprxWidth - ((((12 / $getSizeCom) - 1) * $gap) + 196);
                $width = round(($scwidth / 12) * $getSizeCom);
        }
    } else {
        switch ($device) {
            case 'mobile':
                $width = isset($size[2]) ? $size[2] : 300;
                break;
            case 'tablet':
                $width = isset($size[1]) ? $size[1] : 400;
                break;
            default:
                $width = isset($size[0]) ? $size[0] : 550;
        }
    }
    return $width;
}

function featureimages($images = "")
{
    if (empty($images)) {
        $page = page();
        $images = $page->featureimages;
    }
    //Images Array
    if (!is_array($images)) {
        // Split the string by both commas and new lines
        $images = preg_split("/,|\n/", $images);
        $images = array_filter($images, 'trim');
        // Remove any empty elements from the resulting array
        $images = array_filter($images, 'strlen');
    }
    return $images;
}

function makeSlider($images, $arg = [])
{
    echo Slider::makeSlider($images, $arg, app());
}

function get_thumbnail($size = false, $columnBasis = false, $random = false)
{
    $page = getGlobal('page');
    $media = getGlobal('media');

    if ($media && $page) {
        if (is_array($size)) {
            $width = sizeCalc($size, $columnBasis);
        } else {
            $width = $size;
        }
        $media = $page->attachments;
        echo $media->getImage($width, false);
    } else {
        if (is_array($size)) {
            $width = sizeCalc($size, $columnBasis);
        } else {
            $width = $size;
        }

        $page = page();
        $thumbnails = $page->featureimages;
        if (!is_array($thumbnails)) {
            // Split the string by both commas and new lines
            $thumbnails = preg_split("/,|\n/", $thumbnails);
            $thumbnails = array_filter($thumbnails, 'trim');
            // Remove any empty elements from the resulting array
            $thumbnails = array_filter($thumbnails, 'strlen');
        }

        if ($random) {
            shuffle($thumbnails);
        }
        $attr = [];
        if (property_exists($page, 'feature_image_title')) {
            $attr['title'] = $page->feature_image_title;
        }
        if (isset($thumbnails[0])) {
            $media = Media::getInstanse($thumbnails[0]);
            if ($media) {
                echo $media->getImage($width, true, $attr);
            }
        } else {
            //Not Selected media as features images;
            $media = Media::getInstanse("/not-found/notfound.png"); //here fake image path provided for load not found image :)
            if ($media) {
                echo $media->getImage($width, true, $attr);
            }
        }
    }
}

/**
 * Page Title of Current Page
 */
function get_title()
{
    $page = getGlobal('page');
    if ($page && $page) {
        return $page->title;
    } else {
        return getGlobal('title');
    }
}

function h1_text()
{
    $page = page();
    if (!empty($page->h1)) {
        return $page->h1;
    }
    return get_title();
}

function the_content()
{
    $content = get_content();
    echo apply_filters('the_content', $content);
}

function getRandKeyList(){
    
}


/**
 * Get the Menu by Slug
 * @param string $slug
 */
function get_menu($name, $arg = [])
{
    $Menu = new Menu($name);
    echo $Menu->get_menu_items($arg);
}

function get_menu_instance($name)
{
    return  new Menu($name);
}

function slug_text()
{
    $title = getGlobal('slug_text');
    if ($title && $title) {
        return $title;
    }
}

//System Function 
function site_name()
{
    return app()->getSetting('name');
}


function site_url()
{
    return trim(app()->getSetting('siteurl'));
}

function site_logo($width = 200, $mobileWidth = false)
{
    $app = app();
    if ($app::getApproxDeviceWidth() < 500 && $mobileWidth) {
        $width = $mobileWidth;
    }
    $logoUrl = trim($app->getSetting('logo'));
    if (empty($logoUrl)) {
        return site_name();
    } else {
        $img = Media::getInstanse($logoUrl);
        return $img->getImage($width, true);
        // $src = $img->srcRatio;
        // if ($width) {
        //     $src = $img->getSize($width, true);
        // }
        // return "<img class='logo-image' src=\"$src\" alt=\"$img->name\" title=\"$img->name\">";
    }
}

function site_icon(...$sizes)
{
    $sizes = array_unique(array_merge([16, 32, 48, 128, 192], $sizes));
    $app = app();
    $favImg = $app->getSetting('favicon');
    if (!empty($favImg)) {
        $iconUrl = trim($favImg);
        if (!empty($iconUrl)) {
            $imgPath = pathinfo($iconUrl);
            $img = new Media($imgPath['basename'], $imgPath['dirname']);
            foreach ($sizes as $size) {
                $hrf = $img->getSize($size);
                echo '<link rel="icon" href="' . $hrf . '" sizes="' . $size . 'x' . $size . '" >';
            }
        }
    }
}


//Theme Related Methods
function get_header()
{
    $headerFile = getGlobal('header_file');
    if ($headerFile && file_exists($headerFile)) {
        include_once($headerFile);
    }
}


function getCustomCss()
{
    $css = getGlobal('theme_custom_css');
    echo "<style>$css</style>";
}

function get_footer_top()
{
    $blocks = FooterController::$blocks;
    $blocksData = app()->GetSetting('footer_blocks');
    echo '<div class="box-row">';
    foreach ($blocks as $k => $block) {
        if (!isset($blocksData[$k]['value']))
            continue;
        $val = $blocksData[$k]['value'];
        echo '<div class="box  box-' . $block['col'] . ' t6 m12">';
        echo "<h3>" . $blocksData[$k]['title'] . "</h3>";
        if ($block['type'] == 'menus') {
            get_menu($val, ['class' => 'footer-menu']);
        } else {
            echo "<p>" . nl2br($val) . "</p>";
        }
        echo '</div>';
    }
    echo '</div>';
}

function get_footer()
{
    $footerFile = getGlobal('footer_file');
    if ($footerFile && file_exists($footerFile)) {
        include_once($footerFile);
    }
}


function getMediaDirectory()
{
    $media = getGlobal('media');
    if ($media) {
        return $media->subDir;
    }
    return '';
}

function theme_uri($path = "")
{
    $themeRoot = getGlobal('theme_url');
    $path = $themeRoot . "/" . $path;
    return urlSlashFix($path);
}

function inline_theme_asset($path = '')
{
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $dir = getGlobal('theme_dir');
    $content = '';
    $fullPath = urlSlashFix($dir . '/' . $path);

    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
    }

    switch ($ext) {
        case 'css':
            # code...
            echo "<style>$content</style>" . PHP_EOL;
            break;
        case 'js':
            echo "<script>$content</script>" . PHP_EOL;
            # code...
            break;
        default:
            # code...
            break;
    }
}

function get_attachment($size = false)
{
    $page = getGlobal('page');
    $media = getGlobal('media');

    if ($media && $page) {
        $src = $page->attachments->src;
        if ($size) {
            $src = $page->attachments->getSize($size);
        }

        return "<image src=\"$src\" alt=\"\">";
    }
}
