<?php

namespace Aponahmed\Cmsstatic\Traits;

use Exception;
use Aponahmed\Cmsstatic\Route;

trait Main
{
    private static $configFile = ROOT_DIR . '/.env';
    public static $adminDir = 'admin';
    public Route $route;
    public array $global;
    public static $debug = false;
    public static $siteurl;
    public static $contentDir;
    public static $contentUri;
    public static $dataDir;
    public static $mediaDir;
    public static $mediaUri;

    //Virtual Image src
    public static $virtualImage = false;
    public static $virtualImgDir;
    public static $virtualImgUri;

    //Attachment page
    public static $imageBaseDir;
    public static $imageBasePath;
    //Settings Data file 
    public static $settingsFile;
    public static $settings;

    public $config;
    public static $_phpV;

    public function init()
    {
        self::$_phpV = phpversion();
        $this->parseConfigFile(self::$configFile);

        //Debugger Property Initial
        self::$debug = $this->getConfig('app', 'debug', false);
        self::$adminDir = $this->getConfig('app', 'adminDir', 'admin');

        self::$contentDir = self::urlSlashFix(ROOT_DIR . "/" . $this->getConfig('app', 'contentDir', 'contents') . "/");
        self::$dataDir = self::urlSlashFix(self::$contentDir . "/data/");
        self::$mediaDir = self::urlSlashFix(self::$contentDir . "/media/");

        //Settings Data
        self::$settingsFile = self::urlSlashFix(self::$dataDir . '/settings.json');
        self::GetSettings();

        self::silence(self::$dataDir);
        self::silence(self::$contentDir);
        self::silence(self::$mediaDir, true);

        //Debugger Property Initial
        self::$debug = $this->GetSetting('debug', false);
        //uris
        self::$siteurl = self::urlSlashFix($this->GetSetting('siteurl'));
        self::$contentUri = self::urlSlashFix(self::$siteurl . "/" . $this->getConfig('app', 'contentDir', 'contents') . "/");
        self::$mediaUri = self::urlSlashFix(self::$contentUri . "/media/"); //Without Virtual Image Path


        //Virtual Image path setup
        $vImage = $this->GetSetting('virtualimage', false);
        if ($vImage == 1) {
            self::$virtualImage = true;
            self::$virtualImgDir = $this->GetSetting('virtualdir', 'img');
            self::$virtualImgUri = self::urlSlashFix(self::$siteurl . "/" . self::$virtualImgDir . "/");
        }
        self::$imageBaseDir = $this->GetSetting('imagebase', 'images');
        self::$imageBasePath = self::urlSlashFix(self::$siteurl . "/" . self::$imageBaseDir . "/");

        if (class_exists('Aponahmed\Cmsstatic\Route')) {
            $this->route = new Route($this->getAllConfig(), $this->GetSetting('home_page', 'home'));
        }
        $this->wwwFix();
    }

    function wwwFix()
    {
        $urlHas = false;
        $domainHas = false;
        if (strpos($this->route->url, 'www') !== false) {
            $urlHas = true;
        }
        if (strpos(self::$siteurl, 'www') !== false) {
            $domainHas = true;
        }

        if (!$domainHas && $urlHas) {
            //Redirect To non www
            $this->route->redirect(str_replace('www.', '', $this->route->url), 301);
        }

        if ($domainHas && !$urlHas) {
            $this->route->redirect(str_replace('://', '://www.', $this->route->url), 301);
        }
    }

    function isImageFile($filePath)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'webp', 'png', 'gif', 'bmp'];
        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        return in_array($fileExtension, $allowedExtensions);
    }

    function privateDirs()
    {
        $privateDirStr = $this->GetSetting('privateDirs', 'assets');
        $dirArray = explode(",", $privateDirStr);
        $dirArray = array_filter($dirArray, 'trim');
        return $dirArray;
    }

    public function redirect($to, $message = "")
    {
        $url = $this->route->domain;
        if ($this->global['admin']) {
            $url .= "/" . self::$adminDir;
        }
        $url .= "/" . $to;
        $this->route->redirect(self::urlSlashFix($url), $message);
    }

    private function parseConfigFile($filePath)
    {
        if (!file_exists($filePath)) {
            throw new Exception("Config file not found: $filePath");
        }

        $configData = parse_ini_file($filePath, true);

        if ($configData === false) {
            throw new Exception("Error parsing config file: $filePath");
        }

        $this->config = $configData;
    }

    function getGlobal()
    {
        return $this->global;
    }

    function setGlobal($globals)
    {
        $this->global = $globals;
    }

    public function GetSetting($name, $defaultValue = null)
    {
        if (isset(self::$settings[$name]) && !empty(self::$settings[$name])) {
            if (is_array(self::$settings[$name])) {
                return array_filter(self::$settings[$name]);
            } else {
                return trim(self::$settings[$name]);
            }
        } else {
            return $this->getConfig('app', $name, $defaultValue);
        }
    }

    public function getConfig($section, $key, $defaultValue = null)
    {
        if (isset($this->config[$section][$key]) && !empty($this->config[$section][$key])) {
            return trim($this->config[$section][$key]);
        }
        return $defaultValue;
    }

    public function getConfigSection($section)
    {
        if (isset($this->config[$section])) {
            return $this->config[$section];
        }

        return [];
    }

    public function getAllConfig()
    {
        return $this->config;
    }


    function filterHeaderString($inputString)
    {
        //Expairs-header string
        $pattern = '/\[(.+)\]\s+\+\s+(\d+)\s+/m';
        if (preg_match($pattern, $inputString, $matches)) {
            if ($matches[1] == 'time') {
                $time = time();
            } else {
                $time = strtotime($matches[1]);
            }
            $offsetSeconds = intval($matches[2]);
            $newTimestamp = $time + $offsetSeconds;
            $inputString = str_replace($matches[0], gmdate('D, d M Y H:i:s', $newTimestamp), $inputString);
        }

        $find = ['[time]', '[siteurl]'];
        $replce = [gmdate('D, d M Y H:i:s', time()), self::$siteurl];

        $inputString = str_replace($find, $replce, $inputString);
        return $inputString;
    }

    public function responseHeaders()
    {
        if (isset($this->global['error_404']) && $this->global['error_404'] === true) {
            http_response_code(404);
        }
        $headers = $this->GetSetting('responseHeaders');
        $lines = preg_split('/\r\n|\r|\n/', $headers);
        foreach ($lines as $line) {
            $line = $this->filterHeaderString($line);
            header($line);
        }
    }

    private static function GetSettings()
    {
        if (file_exists(self::$settingsFile)) {
            self::$settings = json_decode(file_get_contents(self::$settingsFile), true);
        }
    }

    static function silence($dir, $create = false)
    {
        if ($create && !is_dir($dir)) {
            mkdir($dir, 0777, true);
            chmod($dir, 0777);
        }

        $dir = trim(self::urlSlashFix($dir), "/");
        if (is_dir($dir) && !file_exists($dir . "/index.html")) {
            return file_put_contents($dir . "/index.html", 'Silence');
        }
    }

    static function urlSlashFix($url)
    {
        return preg_replace('/([^:])(\/{2,})/', '$1/', $url);
    }

    public static function getDeviceType()
    {
        $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))|(windows(?!.*phone))(.*touch)/i', $userAgent)) {
            return 'tablet';
        } elseif (preg_match('/(mobi(le)?|android|iphone)/i', $userAgent)) {
            return 'mobile';
        } else {
            return 'desktop';
        }
    }

    public static function getApproxDeviceWidth()
    {
        if (self::getDeviceType() == 'desktop') {
            return 1366;
        } else if (self::getDeviceType() == 'mobile') {
            return 414;
        } else {
            return 768;
        }
    }

    public static function slugify($str)
    {
        // Convert the string to lowercase
        $str = html_entity_decode(strtolower($str), ENT_HTML5, 'UTF-8');
        // Replace spaces with dashes
        $str = str_replace([' ', '&', "'"], ['-', 'and', ""], $str);
        // Remove special characters
        $str = preg_replace('/[^a-z0-9\-]/', '', $str);
        // Remove consecutive dashes
        $str = preg_replace('/-+/', '-', $str);
        // Trim dashes from the beginning and end
        $str = trim($str, '-');
        return $str;
    }

    function getClientIP()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
                return $_SERVER["HTTP_X_FORWARDED_FOR"];
            if (isset($_SERVER["HTTP_CLIENT_IP"]))
                return $_SERVER["HTTP_CLIENT_IP"];
            return $_SERVER["REMOTE_ADDR"];
        }
        if (getenv('HTTP_X_FORWARDED_FOR'))
            return getenv('HTTP_X_FORWARDED_FOR');

        if (getenv('HTTP_CLIENT_IP'))
            return getenv('HTTP_CLIENT_IP');
        return getenv('REMOTE_ADDR');
    }
}
