<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;

use \ZipArchive;

class SysupdateController extends Controller
{

    private static $remoteFile = 'https://siatexltd.com/static/Static-update.zip';

    function __construct($global = [])
    {
        $this->global = $global;
        parent::__construct();

        $blocksData = $this->GetSetting('footer_blocks');

        //Route in controller
        switch ($this->childSegment) {
            case 'update':
                $this->update();
                break;
            default:
                $this->versionInfo();
                break;
        }
    }

    function versionInfo()
    {
        echo "..Feature in Future..";
    }

    function update()
    {
        $uzipPath = self::urlSlashFix(ROOT_DIR . "/");
        $zipFilePath = self::urlSlashFix($uzipPath . "/update.zip"); // You can change the path as needed
        // Download the ZIP file
        file_put_contents($zipFilePath, fopen(self::$remoteFile, 'r'));

        // Unzip the downloaded file
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) === TRUE) {
            $zip->extractTo($uzipPath); // Unzip to the current directory
            $zip->close();
            echo 1;
        } else {
            echo 0;
        }
        unlink($zipFilePath);
        exit;
    }
}
