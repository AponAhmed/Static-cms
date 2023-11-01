<?php

namespace Aponahmed\Cmsstatic\manage\Model;


class Page extends Model
{

    public $type = 'page';
    function __construct($file)
    {
        parent::__construct();
        $this->modelDataDir = self::urlSlashFix(self::$dataDir . "/pages/");
        $this->getData($file);
    }

    function getLink()
    {
        $homeSlug = $this->GetSetting('home_page');
        if ($this->slug == $homeSlug) {
            return trim(self::$siteurl, '/');
        }
        return self::urlSlashFix(self::$siteurl . "/" . $this->slug . "/");
    }


    function multiple_default()
    {
        $url = $this->url_structure;
        if (strpos($url, self::$siteurl) === false) {
            $url = self::urlSlashFix(self::$siteurl . "/" . $url);
        }

        //Default Ignore 
        $defaultSegmentsStr = $this->default_segments;
        $defaultSegments = explode(',', $defaultSegmentsStr);
        $defaultSegments = array_filter($defaultSegments, 'trim');
        $defaultUrl = $url;
        foreach ($defaultSegments as $k => $segment) {
            $placeholder = '{col' . ($k + 1) . '}';
            $defaultUrl = str_replace($placeholder, $this->slugify($segment), $defaultUrl);
        }
        return trim($defaultUrl);
        //End Default Ignore
    }

    static function  all($app)
    {
        $dir = $app::urlSlashFix($app::$dataDir . "/pages/");
        $files = scandir($dir);
        // Loop through the files and filter for JSON files
        $pages = [];
        foreach ($files as $file) {
            // Check if the file has a .json extension
            if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
                // Output the JSON file name
                $name = pathinfo($file, PATHINFO_FILENAME);
                $pages[] = new self($name);
            }
        }
        return $pages;
    }

    public static function random($app, $excerpt = [])
    {
        $dir = $app::urlSlashFix($app::$dataDir . "/pages/");
        $files = scandir($dir);
        $jsonFiles = [];

        // Loop through the files and filter for JSON files
        foreach ($files as $file) {
            // Check if the file has a .json extension
            if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
                $name = pathinfo($file, PATHINFO_FILENAME);
                if (!in_array($name, $excerpt)) {
                    $jsonFiles[] = $name;
                }
            }
        }
        // Check if there are any JSON files
        if (count($jsonFiles) > 0) {
            // Generate a random index between 0 and the number of JSON files - 1
            $randomIndex = rand(0, count($jsonFiles) - 1);
            // Get the random JSON file name
            $name = $jsonFiles[$randomIndex];
            // Create and return a new instance of your class with the random name
            return new self($name);
        } else {
            return null; // No JSON files found
        }
    }
}
