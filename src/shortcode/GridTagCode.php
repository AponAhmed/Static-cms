<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\ShortcodeParser;

class GridTagCode  implements Shortcode
{
    public $attributes;
    public $innerHtml;
    public function __construct($attributes, public ShortcodeParser $app)
    {
        $default = [
            'col' => 2,
        ];
        $this->attributes = array_merge($default, $attributes);
    }

    function filter()
    {
        $style = "";
        if ($this->attributes['col'] != 2) {
            $style = "style='column-count:" . $this->attributes['col'] . "'";
        }
        return  "<div class=\"grid-view\" $style>" . $this->innerHtml . "</div>";
    }
}
