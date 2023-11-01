<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\manage\Controllers\ContactsController;
use Aponahmed\Cmsstatic\ShortcodeParser;


class ContactShortcode implements Shortcode
{
    public $attributes;
    public function __construct($attributes, public ShortcodeParser $app)
    {
        $default = [
            'n'     => "5"
        ];
        $this->attributes = array_merge($default, $attributes);
    }


    function filter()
    {
        return ContactsController::form();
    }
}
