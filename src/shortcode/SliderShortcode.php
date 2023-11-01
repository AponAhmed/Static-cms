<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\manage\Model\Slider;
use Aponahmed\Cmsstatic\ShortcodeParser;


class SliderShortcode implements Shortcode
{
    public $attributes;

    public function __construct($attributes, public ShortcodeParser $app)
    {
        $default = [
            'slug' => ''
        ];
        $this->attributes = array_merge($default, $attributes);
    }

    function getSlider()
    {
        return new Slider($this->attributes['slug']);
    }

    function filter()
    {
        $html = $this->getSlider()->html();
        return $html;
    }
}
