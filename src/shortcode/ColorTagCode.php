<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\ShortcodeParser;

class ColorTagCode  implements Shortcode
{
    public $attributes;
    public $innerHtml;
    public function __construct($attributes, public ShortcodeParser $app)
    {
        $default = [
            'color' => "#666",
        ];
        $this->attributes = array_merge($default, $attributes);
    }

    function filter()
    {
        $style = "";
        if ($this->attributes['color'] != "#666") {
            $style = "style='color:" . $this->attributes['color'] . "'";
        }
        return  "<div class=\"color-wrap\" $style>" . $this->innerHtml . "</div>";
    }
}
