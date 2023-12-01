<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\ShortcodeParser;

class BgblockTagCode implements Shortcode
{
    public $attributes;
    public $innerHtml;
    public function __construct($attributes, public ShortcodeParser $app)
    {
        $default = [
            'bg' => "#eee",
            'color' => "#333",
            'padding' => "15",
            'radius' => "5",
        ];
        $this->attributes = array_merge($default, $attributes);
    }

    function filter()
    {
        $style = "style='";
        if ($this->attributes['bg']) {
            $style .= "background:" . $this->attributes['bg'] . ";";
        }
        if ($this->attributes['padding']) {
            $style .= "padding:" . $this->attributes['padding'] . "px;";
        }
        if ($this->attributes['color']) {
            $style .= "color:" . $this->attributes['color'] . ";";
        }
        if ($this->attributes['color']) {
            $style .= "border-radius:" . $this->attributes['radius'] . "px;";
        }
        $style .= "'";
        return  "<div class=\"bg-block\" $style>" . $this->innerHtml . "</div>";
    }
}
