<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\ShortcodeParser;


class RandkeylistShortcode  extends RandkeyShortcode implements Shortcode
{

    public function __construct($attributes, public ShortcodeParser $app)
    {
        parent::__construct($attributes, $this->app);
        $default = [
            'n'     => "30"
        ];
        $this->attributes = array_merge($default, $attributes);
        $this->setKey();
    }

    function filter()
    {
        $keys = $this->getKeys($this->attributes['n']);
        $htm = "<ul class=\"key-list\">";
        foreach ($keys as $key) {
            $htm .= "<li>$key</li>";
        }
        $htm .= "</ul>";
        return $htm;
        //self::getRandKeyList($this->attributes['n'], $this->app);
    }
}
