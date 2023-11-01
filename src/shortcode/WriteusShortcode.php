<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\ShortcodeParser;


class WriteusShortcode implements Shortcode
{
    public $attributes;

    public function __construct($attributes, public ShortcodeParser $app)
    {
        $default = [
            'n'     => "1",
        ];
        $this->attributes = array_merge($default, $attributes);
    }

    function filter()
    {
        return '<button class="write-us-btn btn btn-primary" type="button">Write Us</button>';
    }
}
