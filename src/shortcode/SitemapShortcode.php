<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\ShortcodeParser;


class SitemapShortcode implements Shortcode
{
    public $attributes;

    public function __construct($attributes, public ShortcodeParser $app)
    {
        $default = [];
        $this->attributes = array_merge($default, $attributes);
    }

    function filter()
    {
        $sitemapUrl = $this->app::urlSlashFix($this->app::$siteurl . "/" . $this->app->GetSetting('sitemap_file_name', 'sitemap') . ".xml");
        $html = '<a href="' . $sitemapUrl . '" target="_blank">Sitemap</a>';
        return $html;
    }
}
