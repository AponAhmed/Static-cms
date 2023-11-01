<?php

namespace Aponahmed\Cmsstatic\manage\Model;


class Menu extends Model
{
    function __construct($file)
    {
        parent::__construct();
        $this->modelDataDir = self::urlSlashFix(self::$dataDir . "/menus/");
        $this->getData($file);
        $this->getModified();
    }

    function getModified()
    {
        if (!$this->notfound) {
            $modifiedTimestamp = filemtime($this->dataFile);
            $modifiedDate = date("Y-m-d H:i:s", $modifiedTimestamp);
            $this->modified_at = $modifiedDate;
            return $this->modified_at;
        }
    }

    static function getSelect($name, $current = "")
    {
        $dir = self::urlSlashFix(self::$dataDir . "/menus/");
        $menus = self::getMenus($dir);
        $select = "<select name='$name'>";
        $select .= "<option value=''>Select Menu</option>";
        if (count($menus) > 0) {
            foreach ($menus as $slug => $menu) {
                $selected = $slug == $current ? "selected" : "";
                $select .= "<option value='$slug' $selected>$menu->name</option>";
            }
        }
        $select .= "</select>";
        echo $select;
    }

    public function getJson()
    {
        if ($this->menu_links) {
            return json_encode($this->menu_links);
        } else {
            return json_encode([]);
        }
    }



    public static function getMenus($dir)
    {
        $menuInstances = [];
        // Normalize the directory path
        $dir = rtrim($dir, '/') . '/';
        // Get a list of JSON files in the directory
        $jsonFiles = glob($dir . '*.json');
        foreach ($jsonFiles as $jsonFile) {
            // Extract the file name without the directory path and extension
            $menuFileName = pathinfo($jsonFile, PATHINFO_FILENAME);
            // Create a new instance of Menu and add it to the array
            $fileName = str_replace('.json', '', $menuFileName);
            $menuInstances[$fileName] = new self($menuFileName);
        }
        return $menuInstances;
    }



    /**Frontend */

    function itemInitiator($link)
    {

        $title = $link['title'];
        $url = $link['url'];
        $class = $link['className'];
        $newWindow = $link['newWindow'] ? 'target="_blank"' : '';
        $child = '';


        if (isset($link['child']) && count($link['child']) > 0) {
            $class .= " has-child ";
            $child = "<ul class=\"nav-sub\">";
            foreach ($link['child'] as $link) {
                $child .= $this->itemInitiator($link);
            }
            $child .= "</ul>";
        }
        //Current Menu Item (Active Link)
        $currentLink = end($this->route->segments);
        if (!$currentLink && $url == trim(site_url(), '/')) {
            $class .= " current-link";
        } else {
            $urlPart = explode("/", self::urlSlashFix(trim($url, "/")));

            if (end($urlPart) == $currentLink) {
                $class .= " current-link";
            }
        }
        //var_dump($currentLink);

        $liTemplate = "<li class='nav-item $class'><a $newWindow href=\"$url\">$title</a>$child</li>";
        return $liTemplate;
    }

    function get_menu_items($arg = [])
    {
        $menuLinks = $this->menu_links;
        if (is_array($menuLinks)) {
            $cls = "";
            if (isset($arg['class'])) {
                $cls .= " $arg[class]";
            }

            $htm = "<ul class=\"$cls\">";
            foreach ($menuLinks as $link) {
                $htm .= $this->itemInitiator($link);
            }
            $htm .= "</ul>";
            return $htm;
        }
        return "";
    }
}
