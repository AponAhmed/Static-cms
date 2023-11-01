<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\ShortcodeParser;

class ExpandTagCode  implements Shortcode
{
    public $attributes;
    public $innerHtml;
    public function __construct($attributes, public ShortcodeParser $app)
    {
        $default = [
            'arrow' => "yes",
        ];
        $this->attributes = array_merge($default, $attributes);
    }

    function filter()
    {
        $arrow = '<span class="expand-arrow"><svg width="15" height="15" viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M112 184l144 144 144-144"/></svg></span>';
        if ($this->attributes['arrow'] != "yes") {
            $arrow = "";
        }
        return  "<div class=\"expand-wrap\"> $arrow <div class='expand-content'>" . $this->innerHtml . "</div></div>";
    }
}
