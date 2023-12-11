<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\manage\Model\MediaCategory;
use Aponahmed\Cmsstatic\ShortcodeParser;

class ImageShortcode implements Shortcode
{
    public $attributes;
    static $gap = 50;
    static $containerWidth = 1140;

    public function __construct($attributes, public ShortcodeParser $app)
    {
        $default = [
            'dir' => '/', //Image Directory
            'col' => 4,
            'tcol' => 3,
            'mcol' => 2,
            'link' => 'no',
            'fromAll' => 'no',
            'limit' => 8,
            'cart' => 'yes',
            'title' => '', //hide, none
            'rand' => true
        ];
        $this->attributes = array_merge($default, $attributes);
        if ($this->attributes['dir'] == '' || $this->attributes['dir'] == "/") {
            $this->attributes['fromAll'] = 'yes';
        }
    }

    function getImages()
    {
        $dir = trim($this->attributes['dir'], '/');
        if ($dir == 'current' && is_attachment()) { //Current Product Directory
            $dir = getMediaDirectory();
        }

        $dirParts = explode("/", $dir);
        if (count($dirParts) == 1) {
            $dir = "";
        }

        $part = explode("/", $dir);
        array_pop($part);
        $dir = implode("/", $part);

        $directory = new MediaCategory(end($dirParts), $dir);
        if ($this->attributes['fromAll'] == 'yes') {
            $directory->fromAll = true;
        }
        $rand = isset($this->attributes['rand']) && $this->attributes['rand'] == 'yes' ? true : false;
        return $directory->getMedia($this->attributes['limit'], $rand);
    }

    function colCalc($str = "box-", $n = 4)
    {
        $class = "";
        if (!empty($n)) {
            $colW = 12 / $n;
            if (is_float($colW)) {
                $colW = number_format($colW, 1);
                $colW = str_replace(".", "-", $colW);
            }
            $class = " $str$colW";
        }
        return $class;
    }


    function build()
    {
        $images = $this->getImages();
        $class = "box";
        //Colum For Mobile
        $class .= $this->colCalc("m", $this->attributes["mcol"]);
        $class .= $this->colCalc("t", $this->attributes["tcol"]);
        $class .= $this->colCalc("box-", $this->attributes["col"]);

        $scwidth = $this->app::getApproxDeviceWidth();

        $width = false;
        $device = $this->app::getDeviceType();
        switch ($device) {
            case 'mobile':
                $scwidth = $scwidth - (($this->attributes["mcol"]) * self::$gap);
                $width = round($scwidth / $this->attributes["mcol"]);
                break;
            case 'tablet':
                $scwidth = $scwidth - (($this->attributes["tcol"]) * self::$gap);
                $width = round($scwidth / $this->attributes["tcol"]);
                break;
            default:
                //Here 196 is the (1366 - self::$containerWidth) only for max window > self::$containerWidth
                $scwidth = $scwidth - ((($this->attributes["col"] - 1) * self::$gap) + 196);
                $width = round($scwidth / $this->attributes["col"]);
        }

        $html = "<div class=\"box-row image-grid-wrap\">";
        foreach ($images as $image) {
            $src = $image->getSize($width);
            $html .= "<div class=\"$class\">";
            $html .= "<div class='image-thumb'>";
            if ($this->attributes['link'] == 'yes') {
                $html .= "<a href=\"$image->url\"><img src=\"$src\" title=\"$image->name\" alt=\"$image->name\"></a>";
            } else {
                $html .= "<img src=\"$src\" title=\"$image->name\" alt=\"$image->name\">";
            }
            $html .= "</div>";
            $html .= "<div class='thumb-caption'>";
            if ($this->attributes['title'] !== "none") {
                $titleCls = $this->attributes['title'];
                $html .= "<h3 class='image-title $titleCls'>$image->name</h3>";
            }
            if ($this->attributes['cart'] == 'yes') {
                $html .= "<div class='add2cart-wrap'><button data-id='$image->id' data-name='$image->name' class='add2cart' type='button'>Get Price</button></div>";
            }
            $html .= "</div>";
            $html .= "</div>";
        }
        $html .= "</div>";

        return $html;
    }

    public function filter()
    {
        return $this->build();
    }
}
