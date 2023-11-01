<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;

use Aponahmed\Cmsstatic\manage\Model\Settings;
use Aponahmed\Cmsstatic\Theme;

class ThemesController extends Controller
{

    function __construct($global = false)
    {
        $this->global = $global;
        parent::__construct();
        $this->setRoute();
    }

    function setRoute()
    {
        switch ($this->childSegment) {
            case 'active':
                # code...
                $this->activeTheme();
                break;
            case 'edit':
                # code...
                $this->themeFileEdit();
                break;
            case 'custom-css':
                # code...
                $this->view('themes.custom-css', ['css' => self::getCustomCss(self::$dataDir)]);
                break;
            case 'store-css':
                # code...
                $this->storeCss();
                break;
            default:
                $ThemesData = Theme::getThemes(self::$contentDir, self::$contentUri, $this->GetSetting('theme'));
                $this->view('themes.list', ['themes' => $ThemesData]);
                break;
        }
    }

    
    /**
     * Returns the Custom Css String
     * @param string $dataDir The directory of Data
     * @return string The CSS String;
     */
    public static function getCustomCss($dataDir)
    {
        $customCssFile = self::urlSlashFix($dataDir . "/custom.css");
        if (file_exists($customCssFile)) {
            return file_get_contents($customCssFile);
        }
        return "";
    }

    function storeCss()
    {
        $customCssFile = self::urlSlashFix(self::$dataDir . "/custom.css");
        $css = $_POST['css'];
        $res = file_put_contents($customCssFile, $css);
        if ($res) {
            echo json_encode(['error' => false, 'message' => 'Custom CSS Updated']);
        } else {
            echo json_encode(['error' => true, 'message' => 'Custom CSS could not updated']);
        }
    }



    function activeTheme()
    {
        //Get Data From Request then update Option called `theme`
        $rqTheme = 'theme1';
        $settings = new Settings();
        $settings->update(['theme' => $rqTheme]);
    }

    function themeFileEdit()
    {
        $this->view('themes.edit');
    }
}
