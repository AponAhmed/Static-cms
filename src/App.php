<?php

namespace Aponahmed\Cmsstatic;

use Aponahmed\Cmsstatic\Hook;
use Aponahmed\Cmsstatic\manage\Auth;
use Aponahmed\Cmsstatic\View;
use Aponahmed\Cmsstatic\Traits\Main;
use Aponahmed\Cmsstatic\manage\Manage;
use Aponahmed\Cmsstatic\Utilities\FrontendControll;
use Aponahmed\Cmsstatic\Utilities\Singleton;
use Exception;

class App extends Singleton
{
    use Main;
    public Object $property;
    private View $view;
    public $perform;
    public $auth;

    public function __construct($props = null)
    {
        global $ajax, $_p, $Auth;
        $this->perform = $_p;
        $this->auth = new Auth();
        $Auth = $this->auth;
        $this->global = [];
        $this->init();
        $this->property = (object) $props;
        if (self::$_phpV < 8) {
            throw new Exception('PHP version not meets requirements, its recommended to upgrade to 8.0 or more.');
        }

        if ($this->route->getSegment(0) == self::$adminDir || $this->route->getSegment(0) == 'ajax') {
            if ($this->route->getSegment(0) == 'ajax') {
                $ajax = true;
            }
            include_once 'functions/admin-function.php';
            Manage::getInstance($this->auth);
        } elseif (self::$virtualImage && $this->route->getSegment(0) == self::$virtualImgDir) {
            $image = new ImageLoader($this);
            $image->setQuality(100);
            $image->render();
        } else {
            if (self::$debug) {
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);
            }
            $this->view();
        }
    }

    private function view()
    {
        $this->perform->start('view', "View Rander", ['file' => __FILE__, 'line' => __LINE__]);
        $this->view = View::getInstance($this->global);
        $this->view->rander();
        $this->perform->end('view');

        $this->global = $this->view->getGlobal();
        $this->perform->end('system');
        new FrontendControll($this);
    }
}
