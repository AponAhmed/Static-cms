<?php

namespace Aponahmed\Cmsstatic;

use Aponahmed\Cmsstatic\manage\Model\Media;
use Aponahmed\Cmsstatic\manage\Model\Page;
use Aponahmed\Cmsstatic\Traits\Main;
use Aponahmed\Cmsstatic\Theme;


class PageLoader
{
    use Main;
    private $pageDir;
    private $segments;
    public $slug;
    public $pagePath;
    public $mediaPath;
    public Theme $theme;
    public object $data;
    public Hook $hook;

    public function __construct($segments, $global, $hook)
    {
        $this->hook = $hook;
        $this->init();
        $this->segments = $segments;
        $this->global = $global;

        $this->global['segments'] = $this->segments;
        $this->pageDir = self::urlSlashFix(self::$dataDir . "/pages/");


        if (!is_dir($this->pageDir)) {
            mkdir($this->pageDir, 0777, true);
            file_put_contents($this->pageDir . "index.html", 'Silence');
        }

        $homePageSlug = $this->GetSetting('home_page', 'home');

        if (count($this->segments) > 0) {
            $this->slug = end($this->segments);
        } else {
            $this->global['is_home'] = true;
            $this->slug = $homePageSlug;
        }

        $this->setPageFile();
        $this->getTitle();
    }

    function getTitle()
    {
        if (isset($this->global['error_404']) && $this->global['error_404']) {
            $this->global['slug_text'] = "404 Page Not Found";
            $this->global['title'] = "404 Page Not Found";

            return $this->global['slug_text'];
        }
        // Remove hyphens and replace with spaces
        $text = str_replace('-', ' ', $this->slug);

        // Convert to title case (optional, depends on your preference)
        $text = ucwords($text);
        $this->global['slug_text'] = $text;
        return $this->data->title;
    }

    /**
     * Private Media directories prevent to open and redirect to home page
     * @param string $dir
     * @return void
     */
    function privateAttachmentDirsAction($dir)
    {
        $dirStr = $this->GetSetting('privateDirs');
        $dirs = array_filter(explode(',', $dirStr), 'trim');
        if (in_array($dir, $dirs)) {
            $this->route->redirect(self::$siteurl, [], 301);
        }
    }

    function setAttachmentDAta()
    {
        $segments = $this->segments;
        unset($segments[0]); //remove attachment base segment
        $segments = array_values($segments);
        $fileName = end($segments);
        unset($segments[count($segments) - 1]); //remove last attachment name segment
        $dir = implode("/", $segments);

        //Private Directory Redirect to 301
        $this->privateAttachmentDirsAction($dir);

        $dirWiEx = self::urlSlashFix(self::$mediaDir . "/" . $dir . "/" . $fileName);
        $media = Media::get($dirWiEx, $dir);
        if ($media) {
            $this->global['media'] = $media;
            $this->data = (object) [
                'title' => $media->name,
                'content' => '',
                'meta_title' => $media->name . " - " . $this->GetSetting('name'),
                'meta_desc' => $media->name . ', [randphrase n="8"]',
                'meta_key' => $media->name,
                'attachments' => $media
            ];
        } else {
            $this->handle404(true);
        }
    }

    function setPageFile()
    {
        global $page;
        $this->global['slug'] = $this->slug;

        if ($this->route->getSegment(0) == self::$imageBaseDir) {
            $this->setAttachmentDAta();
            $this->global['page'] = $this->data;
        } else {
            // $page = new Page($this->slug);
            // if ($page->notfound) {
            //     $this->global['page_data_file_looking'] = $this->pagePath;
            //     $this->global['page_data_file'] = false;
            //     $this->global['error_404'] = true;
            //     $this->data = (object) ['title' => '404'];
            // } else {
            //     $this->global['page_data_file'] = $page->dataFile;
            //     $this->data = $page;
            //     //var_dump($this->data->title);
            //     $this->global['page'] = $this->data;
            // }

            //Here redirect Default Page link of Multiple page
            $pageInstance = new Page($this->slug);
            if ($pageInstance->multiple_page && $this->route->url == $pageInstance->multiple_default()) {
                $this->route->redirect($pageInstance->getLink(), '', 301);
            }
            $this->global['page-instanse'] = $pageInstance;

            $this->pagePath = self::urlSlashFix($this->pageDir . "/" . $this->slug . ".json");
            if (file_exists($this->pagePath)) {
                $this->global['page_data_file'] = $this->pagePath;
                $contents = file_get_contents($this->pagePath);
                $this->data = json_decode($contents);
                $page = $this->data;
                $this->global['page'] = $this->data;
            } else {
                $this->handle404();
            }
        }
    }

    function handle404($attachment = false)
    {
        $redirect = $this->GetSetting('redirect_404');
        $redirectCode = $this->GetSetting('redirect_404_code');
        if ($redirect) {
            header('Location: ' . self::$siteurl, true, $redirectCode);
            exit;
        }

        $this->global['page_data_file_looking'] = $this->pagePath;
        $this->global['page_data_file'] = false;
        $this->global['error_404'] = true;
        if ($attachment) {
            $this->data = (object) ['title' => '404 Attachment Not Found'];
        } else {
            $this->data = (object) ['title' => '404 Page Not Found'];
        }
    }

    public function getData()
    {
        if ($this->data) {
            return $this->data;
        }
        return false;
    }

    public function storeData($data = array(), $slug = "")
    {
        if ($data) {
            $this->data = (object) $data;
            $dataStr = json_encode($data);
        }
    }



    function render()
    {
        $this->theme = new Theme($this->global, $this->hook);
        //$templates = $this->theme->getTemplates();
        //var_dump($templates);
        //exit;
        $this->global = $this->theme->getGlobal();
        //$this->global['hook'] = $this->hook;
        $this->global['url'] = $this->route->uri;
        $this->global['app'] = $this;
        // echo "<pre>";
        // var_dump($this->global['hook']);
        // exit;
        $GLOBALS['STCMS'] = $this->global;
        //Common functions
        $Page = isset($this->global['page']) ? $this->global['page'] : false;
        include_once 'functions.php';
        //Themes functions
        if (file_exists($this->theme->ThemeDir . "/functions.php")) {
            include_once $this->theme->ThemeDir . "/functions.php";
        }

        $this->theme->loadTemplate();
    }
}
