<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\manage\Model\StaticPage;
use Aponahmed\Cmsstatic\manage\Model\Taxonomy;
use Aponahmed\Cmsstatic\ShortcodeParser;


class StaticShortcode implements Shortcode
{
    public $attributes;
    private $Taxonomy;
    private $objects;
    public static $default = [
        "type"  => "default",
        "cat"   => "",
        "col"   => "3",
        "tcol"  => "2",
        "mcol"  => "1",
        "icon"  => "no",
        "img"   => "no",
        "class"         => "",
        "align"         => "center",
        "order"         => "Desc",
        "title-tag"     => "h3",
        "icon-bg"       => "bg-e",
        "icon-color"    => "color-primary",
        "icon-rounded"  => "rounded-full"
    ];

    public function __construct($attributes, public ShortcodeParser $app)
    {
        $this->attributes = array_merge(self::$default, $attributes);
        $this->Taxonomy = new Taxonomy('static-category');
        $this->getObjects();
    }

    function getObjects()
    {
        $slugs = $this->Taxonomy->getObjects($this->attributes['cat']);
        if (!empty($slugs)) {
            foreach ($slugs as $slug) {
                $this->objects[] = new StaticPage($slug);
            }
        }
    }

    function titleTag($title)
    {
        return "<" . $this->attributes['title-tag'] . " class=\"post-title\">" . $title . "</" . $this->attributes['title-tag'] . ">";
    }

    function singleColumn($obj)
    {
        $colW = 12 / $this->attributes['col'];
        $tcolW = 12 / $this->attributes['tcol'];
        $mcolW = 12 / $this->attributes['mcol'];

        $html = "<div class=\"box box-$colW t$tcolW m$mcolW\">";
        $innerClass = $this->attributes['class'];
        $innerClass .= " align-" . $this->attributes['align'];
        $html .= "   <div class=\"column-inner $innerClass\">";

        if ($this->attributes['icon'] == 'yes' || $this->attributes['img'] == 'yes') {
            $html .= "<div class=\"column-media\">";
            if ($this->attributes['icon'] == 'yes' && $obj->svg) {
                $iconCls = "";
                $iconAttr = "";
                if ($this->attributes['icon-bg'] && strpos($this->attributes['icon-bg'], '#') === false) {
                    $iconCls .= " " . $this->attributes['icon-bg'];
                } else {
                    $bg = $this->attributes['icon-bg'];
                    $iconAttr .= " style=\"background:$bg\"";
                }

                if ($this->attributes['icon-rounded'])
                    $iconCls .= " " . $this->attributes['icon-rounded'];
                if ($this->attributes['icon-color'] && strpos($this->attributes['icon-color'], '#') === false) {
                    $iconCls .= " " . $this->attributes['icon-color'];
                } else {
                    $color = $this->attributes['icon-color'];
                    $obj->svg = str_replace('<svg', "<svg style=\"color: $color;fill:$color;stroke: $color;\"", $obj->svg);
                }
                $html .= "<div $iconAttr class=\"icon-svg $iconCls\">$obj->svg</div>";
            }

            $html .= "</div>"; //column-media
        }

        $html .= "<div class=\"column-content\">";
        $html .= "<div class=\"column-title\">" . $this->titleTag($obj->title) . "</div>"; //column-title
        $html .= "<div class=\"column-description\">$obj->content</div>"; //column-Description
        $html .= "</div>"; //column-content
        $html .= "</div>"; //column-inner
        $html .= "</div>"; //box
        return $html;
    }

    function singleRow($obj)
    {
        $html = "<div class=\"box box-12 row-type\">";
        $innerClass = $this->attributes['class'];
        $innerClass .= " align-" . $this->attributes['align'];
        $html .= "   <div class=\"column-inner $innerClass\">";

        if ($this->attributes['icon'] == 'yes' || $this->attributes['img'] == 'yes') {
            $html .= "<div class=\"column-media\">";
            if ($this->attributes['icon'] == 'yes' && $obj->svg) {
                $iconCls = "";
                if ($this->attributes['icon-bg'])
                    $iconCls .= " " . $this->attributes['icon-bg'];
                if ($this->attributes['icon-rounded'])
                    $iconCls .= " " . $this->attributes['icon-rounded'];

                $html .= "<div class=\"icon-svg $iconCls\">$obj->svg</div>";
            }

            $html .= "</div>"; //column-media
        }

        $html .= "<div class=\"column-content\">";
        $html .= "<div class=\"column-title\">" . $this->titleTag($obj->title) . "</div>"; //column-title
        $html .= "<div class=\"column-description\">$obj->content</div>"; //column-Description
        $html .= "</div>"; //column-content
        $html .= "</div>"; //column-inner
        $html .= "</div>"; //box
        return $html;
    }

    function defaultBuild()
    {
        if (count($this->objects) > 0) {
            $htm = "<div class=\"box-row\">";
            foreach ($this->objects as $obj) {
                $htm .= $this->singleColumn($obj);
            }
            $htm .= "</div>";
            return $htm;
        }
    }

    function rowBuild()
    {
        if (count($this->objects) > 0) {
            $htm = "<div class=\"box-row\">";
            foreach ($this->objects as $obj) {
                $htm .= $this->singleRow($obj);
            }
            $htm .= "</div>";
            return $htm;
        }
    }


    function filter()
    {
        if (method_exists($this, $this->attributes['type'] . "Build")) {
            $method = $this->attributes['type'] . "Build";
            return $this->$method();
        } else {
            return "<pre>Builder Not Available for This Type(" . $this->attributes['type'] . ") in:" . __FILE__ . ".<br><em>Create a method as '" . $this->attributes['type'] . "Build()'</em></pre>";
        }
    }
}
