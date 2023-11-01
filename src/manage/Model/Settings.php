<?php

namespace Aponahmed\Cmsstatic\manage\Model;

use Aponahmed\Cmsstatic\Traits\Main;

class Settings
{
    use Main;
    public $data;
    function __construct()
    {
        $this->init();
        $this->data = (array) self::$settings;
    }

    function update($data)
    {
        $this->data = array_merge($this->data, $data);
        return file_put_contents(self::$settingsFile, json_encode($this->data));
    }
}
