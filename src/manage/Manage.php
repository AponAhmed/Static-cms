<?php

namespace Aponahmed\Cmsstatic\manage;

use Aponahmed\Cmsstatic\Traits\Main;
use Aponahmed\Cmsstatic\manage\Auth;
use Aponahmed\Cmsstatic\manage\Controllers\LoginController;
use Aponahmed\Cmsstatic\manage\Controllers\HomeController;
use Aponahmed\Cmsstatic\manage\Controllers\LogoutController;
use Aponahmed\Cmsstatic\Theme;
use Aponahmed\Cmsstatic\Utilities\Singleton;

class Manage extends Singleton
{
    use Main;
    public $property;
    private $controller; //Dynamic controller

    public function __construct(private Auth $auth, $property = false)
    {
        $this->global['admin'] = true;
        //Themes Control in Admin
        $this->themeInit();
        $this->init();
        $this->property = $property;
        $this->loader();
    }


    function themeInit()
    {
        $theme = new Theme($this->global);
        if (file_exists($theme->ThemeDir . "/functions.php")) {
            include($theme->ThemeDir . "/functions.php");
        }
    }


    function publicUrls()
    {
        global $publicUrls;
        $seg = $this->route->segments;
        unset($seg[0]);
        $curUrl = self::urlSlashFix(implode("/", $seg) . '/');
        if (in_array($curUrl, $publicUrls)) {
            return true;
        }
        return false;
    }

    function loader()
    {

        $isPublicUrl = $this->publicUrls();

        if ($this->auth->is_loggedin() || $isPublicUrl) {
            if (count($this->route->segments) > 1) {
                //Dynamic loading logic Controller
                $controller = $this->route->getSegment(1);
                if ($controller == 'logout') {
                    $this->controller = new LogoutController($this->auth);
                } else {
                    $controllerName = ucfirst($controller) . "Controller";
                    $classWNamespace = "Aponahmed\Cmsstatic\manage\Controllers\\" . $controllerName;
                    //$this->controller = new $controllerName();
                    if (class_exists($classWNamespace)) {
                        $this->controller = new $classWNamespace($this->global);
                    } else {
                        echo $classWNamespace . " not found in class namespace ";
                    }
                }
            } else {
                $this->controller = new HomeController($this->global);
            }
        } else {
            $this->controller = new LoginController($this->auth);
        }
        //echo "<pre>";
        //var_dump($this);
    }
}
