<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;

use Aponahmed\Cmsstatic\Traits\Main;
use Exception;

class Controller
{
    use Main;
    private $viewDir = ROOT_DIR . "/manage/view/";
    private $assetUri;
    public $childSegment;

    public function __construct()
    {
        $this->init();
        $this->childSegment = $this->route->getSegment(2);
    }


    function view($file, $data = array())
    {
        global $ajax;
        if ($ajax) {
            $this->route->redirect(self::$siteurl);
        }

        $this->assetUri = self::urlSlashFix($this->route->domain . "/manage/assets/");

        $parts = $this->route->segments;
        unset($parts[0]);
        $parts = array_map(function ($val) {
            return ucfirst($val);
        }, $parts);

        //Global Variables
        $this->global['admin_assets_uri'] = $this->assetUri;
        $this->global['admin_title'] = implode(" > ", $parts);
        $this->global['admin_url'] = self::urlSlashFix(self::$siteurl . "/" . self::$adminDir);
        $this->global['site_url'] = self::$siteurl;
        $this->global['segments'] = $this->route->segments;
        $this->global['app'] = $this;
        //End Global Variables

        //Global Variables Settings
        $GLOBALS['STCMSADMIN'] = $this->global;
        //View Template Finder


        $filePart = array_filter(explode(".", $file));
        $file = implode("/", $filePart);
        $filePath = self::urlSlashFix($this->viewDir . "/" . $file . ".php");
        if (file_exists($filePath)) {
            extract($data);
            include $filePath;
        } else {
            echo "View file not found in $filePath";
        }
    }

    function getInput()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the raw JSON POST data
            $postData = file_get_contents('php://input');

            // Check if the data is not empty
            if (!empty($postData)) {
                // Decode the JSON data
                $data = json_decode($postData);

                // Check if decoding was successful
                if ($data !== null) {
                    return $data;
                }
            }
        }
        return null;
    }
}
