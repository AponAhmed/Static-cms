<?php

namespace Aponahmed\Cmsstatic;

use Aponahmed\Cmsstatic\Traits\Main;

class ShortcodeParser
{

    use Main;
    /**
     * Parses all shortcode
     */
    private $str = null;

    public function __construct($str = "")
    {
        $this->init();
        $this->str = $str;
        $this->parseShortcodes();
    }

    function parseShortcodes()
    {

        //$re = '/(?:<p>)?\[(\w+)(?:\s+([^]]+))?\]([^[]+)\[\/\1\](?:<\/p>)?/';
        $re = '/((<p>|)\[(\w+)(?:\s+([^]]+))?\](<\/p>|))(.*?)((<p>|)\[\/\3\](<\/p>|))/s';
        $this->str = preg_replace_callback($re, array($this, 'shortcodeTag'), $this->str);
        $this->str = preg_replace_callback($re, array($this, 'shortcodeTag'), $this->str); //for nested tags 2 times

        //$pattern = '/\[([a-zA-Z]+)([^\]]*)\]/';
        //$pattern = '/(?:<p>)?\[([a-zA-Z-]+)([^\]]*)\](?:<\/p>)?/';
        $pattern = '/(?:<(p)>)?\[([a-zA-Z-]+)([^\]]*)\](?:<\/\1>)?/';
        $this->str = preg_replace_callback($pattern, array($this, 'replaceShortcode'), $this->str);
        $this->str = preg_replace_callback($pattern, array($this, 'replaceShortcode'), $this->str); //Nested function
    }

    /**
     * Tag Type Shortcode with [start] and [/end]
     */
    private function shortcodeTag($matches)
    {

        $tag = $matches[3];

        $attributes = $this->parseShortcodeAttributes($matches[4]);
        $innerText = $matches[6];
        $ucWTag = ucwords($tag);
        $className = __NAMESPACE__ . "\shortcode\\" . $ucWTag . "TagCode";
        if (class_exists($className)) {
            $shortcodeInstance = new $className($attributes, $this);
            $shortcodeInstance->innerHtml = $innerText;
            return $shortcodeInstance->filter();
        }
        $code = $matches[0];
        if (self::$debug) {
            $code .= ":" . $ucWTag . "TagCode" . " controller class not found in shortcode namespace";
        }
        return $code;
    }

    private function replaceShortcode($matches)
    {
        $tag = $matches[2];

        $attributes = $this->parseShortcodeAttributes(html_entity_decode($matches[3]));
        $ucWTag = ucwords(str_replace("-", "", $tag));
        $className = __NAMESPACE__ . "\shortcode\\" . $ucWTag . "Shortcode";
        if (class_exists($className)) {
            $shortcodeInstance = new $className($attributes, $this);
            return $shortcodeInstance->filter();
        }
        $code = $matches[0];
        if (self::$debug) {
            $code .= ":" . $ucWTag . "Shortcode" . " controller class not found";
        }
        return $code;
    }

    function parseShortcodeAttributes($attributesString)
    {
        $attributes = [];
        // Define the regex pattern to match attribute-value pairs
        $pattern = '/\b([\w-]+)\s*=\s*("([^"]*)"|\'([^\']*)\'|(\S+))\s*/';
        // Use preg_match_all to find all attribute-value pairs in the string
        preg_match_all($pattern, $attributesString, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $attribute = $match[1];
            $value = isset($match[3]) ? $match[3] : (isset($match[4]) ? $match[4] : $match[5]);
            $attributes[$attribute] = $value;
        }
        return $attributes;
    }


    function getContent()
    {
        return $this->str;
    }
}
