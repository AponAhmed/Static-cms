<?php

namespace Aponahmed\Cmsstatic\manage\Model;

use Aponahmed\Cmsstatic\Traits\Main;
use Exception;

class Media
{
    use Main;
    public $file;
    public $subDir;
    private $directory;
    public $src;
    public $srcRatio;
    public $url;
    public $name;
    public $id = null;

    public function __construct($file, $dir = '')
    {
        $this->init();
        $this->file = $file;
        $this->subDir = $dir;
        $this->directory = self::urlSlashFix(self::$mediaDir . "/" . $this->subDir . "/" . $this->file);
        $this->setFileName();
        $this->id = self::urlSlashFix($this->subDir . "/" . $this->file);
        $this->getSrc();
        $this->getUrl();
    }

    /**
     * Getr Media Instanse by file directory path
     * @param string $path without media path 
     */
    static function getInstanse($path)
    {
        if (empty($path))
            return false;
        $pathInfo = pathinfo($path);
        $dir = $pathInfo['dirname'];
        $fileName = $pathInfo['basename'];
        return new self($fileName, $dir);
    }

    /**
     * Url to Media instance
     * @param string $url
     */
    public static function get($path, $dir = "")
    {
        $img = glob($path . ".*");
        if (isset($img[0])) {
            $pathInfo = pathinfo($img[0]);
            $fileName = $pathInfo['basename'];
            return new self($fileName, $dir);
        } else {
            return false;
        }
    }




    function getUrl()
    {

        $url = self::urlSlashFix(self::$imageBasePath . "/" . $this->subDir . "/" . $this->file);
        $urlPathInfo = pathinfo($url);
        $this->url = strtolower(self::urlSlashFix($urlPathInfo['dirname'] . "/" . $urlPathInfo['filename'] . "/"));
        return $this->url;
    }


    function getImage($size = null, $ratio = true, $attr = [])
    {
        $src = $this->getSrc();
        if ($size) {
            $src = $this->getSize($size, $ratio);
        }
        $width = $size ? "width=\"$size\"" : '';
        $height = !$ratio ? "height=\"$size\"" : '';
        $title = $this->name;
    
        if (isset($attr['title']) && !empty($attr['title'])) {
            $title = $attr['title'];
            // Remove 'title' from the $attr array, as we have already handled it separately
            unset($attr['title']);
        }
        // Generate additional attributes from $attr
        $additionalAttributes = '';
        foreach ($attr as $key => $value) {
            $additionalAttributes .= " $key=\"$value\"";
        }    
        return "<img src=\"$src\" $width $height title=\"$title\" alt=\"$title\" $additionalAttributes>";
    }
    

    function getSrc()
    {
        if (self::$virtualImage) {
            //Virtual Image Path
            $this->src = self::urlSlashFix(self::$virtualImgUri . "/" . $this->subDir . "/" . $this->file);
        } else {
            $this->src = self::urlSlashFix(self::$mediaUri . "/" . $this->subDir . "/" . $this->file);
        }
        $this->srcRatio = $this->src . "?l=r";
        return $this->srcRatio;
    }

    function getSize($size = 300, $ratio = false)
    {
        $srcSize = $this->src;
        if (self::$virtualImage) {
            $srcSize = self::urlSlashFix(self::$virtualImgUri . "/$size/" . $this->subDir . "/" . $this->file);
        }
        if ($ratio) {
            $srcSize .= "?l=r";
        }

        return $srcSize;
    }

    function setFileName()
    {
        // Replace hyphens or underscores with spaces and capitalize each word
        $text = ucwords(str_replace(array('-', '_'), ' ', $this->file));
        // Remove file extension (if any)
        $this->name = pathinfo($text, PATHINFO_FILENAME);
    }

    function rename($toName)
    {
        //$toName = self::slugify($toName);
        $parts = pathinfo($toName);
        // Slugify the name part
        $nameSlug = self::slugify($parts['filename']);
        // Reconstruct the filename with the slugified name and original extension
        $toName = $nameSlug . '.' . $parts['extension'];

        //first split extension 
        $renamedDir = self::urlSlashFix(self::$mediaDir . '/' . $this->subDir . '/' . $toName);
        if (file_exists($this->directory)) {
            if (@rename($this->directory, $renamedDir)) {
                $this->file = $toName;
                $this->getSrc();
                $this->getUrl();
            } else {
                throw new Exception('File ' . $toName . 'Error to rename');
            }
        }
        return $this;
    }

    function modified_at()
    {
        if (file_exists($this->directory)) {
            $lastModifiedTimestamp = filemtime($this->directory);
            $lastModifiedDate = date('Y-m-d H:i:s', $lastModifiedTimestamp);
            return $lastModifiedDate;
        }
        return false;
    }

    function delete()
    {
        if (file_exists($this->directory)) {
            return unlink($this->directory);
        }
    }
}
