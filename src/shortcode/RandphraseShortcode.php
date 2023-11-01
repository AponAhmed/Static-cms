<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\ShortcodeParser;


class RandphraseShortcode implements Shortcode
{
    public $attributes;
    private $keyFile;
    private $keys;

    public function __construct($attributes, public ShortcodeParser $app)
    {

        $this->keyFile = $app::urlSlashFix($app::$dataDir . "/randKey.txt");
        $default = [
            'n'     => "5"
        ];
        $this->attributes = array_merge($default, $attributes);
    }


    function filter()
    {
        if (file_exists($this->keyFile)) {
            $this->getKey();
            return implode(", ", $this->getRandomLimit());
        }
        return '';
    }

    function getKey()
    {
        $str = stripslashes(file_get_contents($this->keyFile));
        $keys = array_unique(array_filter(explode(",", $str)));
        //var_dump(count($keys));
        //exit;
        $this->keys = $keys;
    }
    function getRandomLimit()
    {
        // Get random keys from the array
        $randomKeys = array_rand($this->keys, $this->attributes['n']);

        // If $numElements is 1, array_rand returns a single key, not an array
        if (!is_array($randomKeys)) {
            $randomKeys = [$randomKeys];
        }

        // Initialize an array to store the randomly selected elements
        $randomElements = [];

        // Access the elements using the random keys
        foreach ($randomKeys as $key) {
            $randomElements[] = $this->keys[$key];
        }

        return $randomElements;
    }
}
