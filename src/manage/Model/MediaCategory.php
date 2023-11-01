<?php

namespace Aponahmed\Cmsstatic\manage\Model;

use Aponahmed\Cmsstatic\Traits\Main;
use Exception;

class MediaCategory
{
    use Main;
    public $name;
    private $directory;
    public $subDir;
    public $count;
    public $fromAll = false;
    public $url;
    function __construct($name = "", $dir = "")
    {
        $this->init();
        $this->name = $name; //name of folder
        $this->subDir = $dir;
        $this->directory = self::urlSlashFix(self::$mediaDir . "/" . $this->subDir . "/" . $this->name . "/");
        $this->count = $this->count();
        $this->getUrl();
    }

    function getMediaAll($limit = 12, $random = false, $excerpt = [])
    {

        $images = [];
        $privateDirs = $this->privateDirs();

        // Define a recursive function to scan nested directories
        $scanDirectory = function ($directory) use (&$images, &$scanDirectory, $privateDirs, $excerpt) {
            $files = array_diff(scandir($directory), ['.', '..', 'index.html']);
            foreach ($files as $file) {
                $fullPath = $directory . "/" . $file;

                if (is_dir($fullPath) && !in_array($file, $privateDirs)) {
                    // If it's a directory, recursively call the function
                    $scanDirectory($fullPath);
                } elseif ($this->isImageFile($fullPath)) {
                    $name = pathinfo($file, PATHINFO_FILENAME);
                    if (in_array($name, $excerpt)) {
                        continue;
                    }
                    // If it's a valid image, add its path to the list
                    $images[] = $fullPath;
                }
            }
        };

        // Start the recursive scan from the main directory
        $scanDirectory($this->directory);

        // Shuffle the images if random is true
        if ($random) {
            shuffle($images);
        }

        // Limit the number of images if needed
        if ($limit && count($images) > $limit) {
            $images = array_slice($images, 0, $limit);
        }

        $imageCount = [];
        if (count($images)) {
            foreach ($images as $image) {
                $path = str_replace(self::$mediaDir, "", $image);
                $media = Media::getInstanse(self::urlSlashFix($path));
                $imageCount[] = $media;
            }
        }
        return $imageCount;
    }


    function get_excerpt()
    {
        global $excerpt;
        $page = page();

        $slug = current_slug();
        if ($slug) {
            $excerpt[] = $slug;
        }

        if (property_exists($page, 'featureimages')) {
            $fetureImage = $page->featureimages;
            if (!is_array($fetureImage)) {
                // Split the string by both commas and new lines
                $fetureImage = preg_split("/,|\n/", $fetureImage);
                $fetureImage = array_filter($fetureImage, 'trim');
                // Remove any empty elements from the resulting array
                $fetureImage = array_filter($fetureImage, 'strlen');
            }
            //$fetureImage = $page->featureimages;
            foreach ($fetureImage as $image) {
                $excerpt[] = pathinfo($image, PATHINFO_FILENAME);
            }
        }
        return $excerpt;
    }


    function getMedia($limit = 12, $random = false, array $excerpt = [])
    {
        $excerpt = array_merge($excerpt, $this->get_excerpt());
        if ($this->fromAll) {
            return $this->getMediaAll($limit, $random, $excerpt);
        }

        $imageCount = [];
        if (is_dir($this->directory)) {
            $files = array_diff(scandir($this->directory), ['.', '..', 'index.html']);
            if ($random) {
                shuffle($files);
            }
            $files = array_slice($files, 0, $limit);
            foreach ($files as $file) {

                if ($file == 'index.html') {
                    continue;
                }
                $fullPath = $this->directory . DIRECTORY_SEPARATOR . $file;

                $name = pathinfo($file, PATHINFO_FILENAME);

                if (in_array($name, $excerpt)) {
                    continue;
                }
                // Check if the file is a valid image based on its exif_imagetype
                if (is_file($fullPath) && $this->isImageFile($fullPath)) {
                    $imageCount[] = new Media($file, $this->name);
                }
            }
        }
        return $imageCount;
    }

    public function getUrl()
    {
        $this->url = self::urlSlashFix($this->route->domain . "/" . self::$adminDir . "/media/" . $this->subDir . "/" . $this->name . "/");
        return $this->url;
    }

    function count()
    {
        $imageCount = 0;
        if (is_dir($this->directory)) {
            $files = scandir($this->directory);

            foreach ($files as $file) {
                if ($file == 'index.html') {
                    continue;
                }
                $fullPath = $this->directory . DIRECTORY_SEPARATOR . $file;

                // Check if the file is a valid image based on its exif_imagetype
                if (is_file($fullPath) && $this->isImageFile($fullPath)) {
                    $imageCount++;
                }
            }
        }
        return $imageCount;
    }

    function rename($toName)
    {
        $toName = self::slugify($toName);
        $renamedDir = self::urlSlashFix(self::$mediaDir . '/' . $this->subDir . '/' . $toName);
        if (is_dir($this->directory)) {
            if (@rename($this->directory, $renamedDir)) {
                $this->name = $toName;
                $this->getUrl();
            } else {
                throw new Exception('Directory ' . $toName . 'Error to rename');
            }
        }
        return $this;
    }


    function delete()
    {
        return  $this->deleteDirectory($this->directory);
    }

    function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return false;
        }

        // List all the files and directories inside the target directory
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if (is_dir($path)) {
                    // Recursive call to delete subdirectories and their contents
                    $this->deleteDirectory($path);
                } else {
                    // Delete individual file
                    unlink($path);
                }
            }
        }

        // Delete the target directory
        return rmdir($dir);
    }

    function save()
    {
        if (!is_dir($this->directory)) {
            self::silence($this->directory, true);
            return is_dir($this->directory);
        }
    }
}
