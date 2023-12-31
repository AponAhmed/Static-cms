<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\ShortcodeParser;

/**
 * Parpose of this class is add External links in [exlink] shortcode 
 */
class TexlinkTagCode  implements Shortcode
{
    public array $attributes;
    public string $innerHtml;

    public function __construct($attributes, public ShortcodeParser $app)
    {
        $default = [];
        $this->attributes = array_merge($default, $attributes);
    }

    function filter()
    {
        $inputString = $this->app->GetSetting('external_links');

        // Define a regular expression pattern to match URLs
        $pattern = '/https?:\/\/\S+/';

        // Find all matches of URLs in the input string
        preg_match_all($pattern, $inputString, $matches);

        $own = trim($this->app::$siteurl, "/");
        //$own = trim("https://www.siatex.co/", "/");
        // Extract the matched URLs from the result
        $urlLines = $matches[0];

        // Remove duplicate URLs and filter out invalid URLs
        $uniqueAndValidUrls = array_filter(array_unique($urlLines), function ($url) use ($own) {
            // Use additional validation logic if needed
            if ($url != $own)
                return filter_var($url, FILTER_VALIDATE_URL);
        });
        // If there are valid URLs, return a random one; otherwise, return an empty string
        if (!empty($uniqueAndValidUrls)) {
            $randomIndex = array_rand($uniqueAndValidUrls);
            $link = $uniqueAndValidUrls[$randomIndex];
            return  "<a href=\"$link\" target=\"_blank\" title=\"$this->innerHtml\">$this->innerHtml</a>";
        } else {
            return $this->innerHtml;
        }
    }
}
