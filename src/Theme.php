<?php

namespace Aponahmed\Cmsstatic;

use Aponahmed\Cmsstatic\manage\Controllers\ThemesController;
use Aponahmed\Cmsstatic\Traits\Main;

class Theme
{
    use Main;
    static $themsDirectoryName = 'themes';
    private $themeFullDir;
    public $ThemeDir = '';

    public function __construct($global)
    {
        $this->global = $global;
        $this->init();
        $this->initDir();
        $this->ThemeDir = self::urlSlashFix($this->themeFullDir . "/" . $this->GetSetting('theme', 'simple-theme') . "/");
        $this->global['theme_dir'] = $this->ThemeDir;
        $this->global['theme_url'] = self::urlSlashFix(self::$contentUri . "/" . self::$themsDirectoryName . "/" . $this->GetSetting('theme', 'simple-theme') . "/");
        $this->global['theme_custom_css'] = self::minifyCSS(ThemesController::getCustomCss(self::$dataDir));
        $this->getHeader();
        $this->getFooter();
    }

    static function minifyCSS($css)
    {
        // Remove comments (block and inline)
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        // Remove whitespace and newlines
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        return $css;
    }

    static function getThemes($contentDir = "", $contentUri = "", $currentTheme = '')
    {
        $dir = self::urlSlashFix($contentDir . "/themes/");

        $themeData = [];
        $folders = array_filter(glob($dir . '*'), 'is_dir'); // Get a list of subfolders
        foreach ($folders as $folder) {
            $packageJsonPath = $folder . '/package.json';
            $screenJpgPath = $folder . '/screen.jpg';
            $folderName = basename($folder);
            if (file_exists($packageJsonPath)) {
                $packageJson = json_decode(file_get_contents($packageJsonPath), true);
                if ($packageJson && isset($packageJson['name'], $packageJson['version'], $packageJson['description'], $packageJson['author'])) {
                    $current = false;
                    if ($currentTheme == $folderName) {
                        $current = true;
                    }
                    $themeInfo = [
                        'name' => $packageJson['theme'],
                        'version' => $packageJson['version'],
                        'description' => $packageJson['description'],
                        'current' => $current,
                        'author' => $packageJson['author'],
                    ];
                    if (file_exists($screenJpgPath)) {
                        $themeInfo['thumb'] = self::urlSlashFix($contentUri . "/themes/" . $folderName . "/screen.jpg"); // Set the actual path to the thumbnail image
                    }
                    $themeData[$folderName] = $themeInfo;
                }
            }
        }
        return $themeData;
    }


    function initDir()
    {
        $this->themeFullDir = self::urlSlashFix(self::$contentDir . "/" . self::$themsDirectoryName);
        if (!is_dir($this->themeFullDir)) {
            mkdir($this->themeFullDir, 0777, true);
            file_put_contents($this->themeFullDir . '/index.html', "Silence");
        }
    }

    /**
     * Get Custom Page Template of Themes
     */
    function getTemplates()
    {
        $directory = $this->ThemeDir;
        $files = scandir($directory);
        $templates = [];
        foreach ($files as $file) {
            if (is_file($directory . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $fileHandle = fopen($directory . '/' . $file, 'r');
                // move to the 7th byte
                fseek($fileHandle, 1);
                $str = fread($fileHandle, 256);   // read 8 bytes from byte 7

                $reGex = '/\s*(.*?)\s*:\s*(.*?)\s*(?=(?:\w+:|$))/m';
                preg_match_all($reGex, $str, $matches, PREG_SET_ORDER, 0);
                if (!empty($matches)) {
                    $info = [];
                    foreach ($matches as $match) {
                        $key = $match[1];
                        if ($key == 'Template Name') {
                            $key = 'name';
                        }
                        $info[$key] = $match[2];
                    }
                    $templates[$file] = $info;
                }
                fclose($fileHandle);
            }
        }
        return $templates;
    }

    function getHeader()
    {
        $headerTemplates = self::urlSlashFix($this->ThemeDir . "/header.php");
        file_exists($headerTemplates) ? $this->global['header_file'] = $headerTemplates : '';
    }

    function getFooter()
    {
        $footerTemplate = self::urlSlashFix($this->ThemeDir . "/footer.php");
        file_exists($footerTemplate) ? $this->global['footer_file'] = $footerTemplate : '';
    }

    function loadTemplate()
    {
        //if 404
        if (isset($this->global['error_404']) && $this->global['error_404']) {
            if (file_exists($this->ThemeDir . "/404.php")) {
                include_once($this->ThemeDir . "/404.php");
            } else {
                echo "404 Page Not Found";
            }
            return;
        } elseif (isset($this->global['media']) && $this->global['media']) {
            if (file_exists($this->ThemeDir . "/attachment.php")) {
                include_once($this->ThemeDir . "/attachment.php");
            } else {
                echo "Attachment template not found";
            }
        } else {
            //Page Template
            $page = $this->global['page'];
            $template = 'page.php';
            //Custom template set and Exists in theme
            if (!empty($page->template) && file_exists($this->ThemeDir . "/" . $page->template)) {
                $template = trim($page->template);
            }

            $templatePath = $this->ThemeDir . "/" . $template;
            if (file_exists($templatePath)) {
                include_once($templatePath);
            } else {
                echo "Page template Not Found ($template)";
            }
        }
    }
}
