<?php

use Aponahmed\Cmsstatic\manage\Model\Media;

function meta_robots()
{
    $page = page();
    $disableSearchEngines = app()->getSetting('disable_search_engines');
    if ($disableSearchEngines) {
        return 'noindex, nofollow';
    }
    if (!is_404()) {
        if (!empty($page->meta_robots)) {
            return $page->meta_robots;
        } else {
            return 'index, follow';
        }
    } else {
        return 'noindex, nofollow';
    }
}


function get_page_image_src($size = false, $random = false)
{
    $page = getGlobal('page');
    $media = getGlobal('media');

    if ($media && $page) {
        $media = $page->attachments;
        return $media->getSize($size);
    } else {
        $thumbnails = $page->featureimages;
        if (is_array($thumbnails)) {
            $thumbnails = array_filter($thumbnails);
        }
        if ($random) {
            shuffle($thumbnails);
        }
        if (isset($thumbnails[0])) {
            $media = Media::getInstanse($thumbnails[0]);
            return $media->getSize($size);
        } else {
            $media = Media::getInstanse(app()->getSetting('logo'));
            if ($media) {
                return $media->getSize($size);
            }
        }
    }
}

function meta_title()
{
    $page = getGlobal('page');
    if (!is_404() && $page && $page->meta_title) {
        return $page->meta_title;
    }
    return get_title();
}

function meta_description()
{
    $page = getGlobal('page');
    if ($page && $page->meta_desc) {
        return $page->meta_desc;
    }
    return "";
}

function meta_keywords()
{
    $page = getGlobal('page');
    if ($page && $page->meta_key) {
        return $page->meta_key;
    }
    return "";
}

function get_url()
{
    $url = getGlobal('url');
    if ($url) {
        if (is_home()) {
            return trim($url, "/");
        }
        return $url;
    }
    return "";
}

function seo_meta()
{
    $title = meta_title();
    $metaTags = "<title>$title</title>" . PHP_EOL;
    if (!is_404()) {
        $description = meta_description();
        $keywords = meta_keywords();
        $robots = meta_robots();
        $url = get_url();
        $img = get_page_image_src();
        $metaTags .= "    <meta name=\"description\" content=\"$description\">" . PHP_EOL;
        if (!empty($keywords))
            $metaTags .= "    <meta name=\"keywords\" content=\"$keywords\">" . PHP_EOL;
        $metaTags .= "    <meta name=\"robots\" content=\"$robots\">" . PHP_EOL;
        $metaTags .= "    <link rel=\"canonical\" href=\"$url\">" . PHP_EOL;
        $metaTags .= '    <meta property="og:title" content="' . $title . '">' . PHP_EOL;
        $metaTags .= '    <meta property="og:description" content="' . $description . '">' . PHP_EOL;
        $metaTags .= '    <meta property="og:image" content="' . $img . '">' . PHP_EOL;
        $metaTags .= '    <meta property="og:url" content="' . $url . '">' . PHP_EOL;
        $metaTags .= '    <meta property="og:type" content="website">' . PHP_EOL;
    }

    echo  $metaTags;
}
