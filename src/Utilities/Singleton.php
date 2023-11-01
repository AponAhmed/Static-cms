<?php

namespace Aponahmed\Cmsstatic\Utilities;


abstract class Singleton
{
    private static $instances = [];

    protected function __construct()
    {
        // Prevent direct instantiation.
    }

    public static function getInstance($params = null)
    {
        $className = get_called_class();

        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new static($params);
        }

        //var_dump(count(self::$instances));

        return self::$instances[$className];
    }
}
