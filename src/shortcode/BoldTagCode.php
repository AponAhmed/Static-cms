<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\ShortcodeParser;

class BoldTagCode  implements Shortcode
{
    public $attributes;
    public $innerHtml;
    public function __construct($attributes, public ShortcodeParser $app)
    {
        $default = [];
        $this->attributes = array_merge($default, $attributes);
    }

    function filter()
    {
        return  "<div class=\"has-strong\">" . $this->innerHtml . "</div>";
    }
}
