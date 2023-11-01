<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;

use Aponahmed\Cmsstatic\manage\Model\Settings;
use Aponahmed\Cmsstatic\Theme;
use Exception;

class SettingsController extends Controller
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
            case 'update':
                # code...
                $this->update();
                break;
            default:
                $settings = new Settings();
                $this->view('settings', ['settings' => $settings]);
                break;
        }
    }

    function update()
    {
        // Read the raw input data
        $inputData = file_get_contents('php://input');

        // Decode the JSON data
        $postData = json_decode($inputData, true);
        // Check if JSON decoding was successful
        if ($postData === null) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
            exit;
        }
        $settings = new Settings();
        try {
            $settings->update($postData);
            echo json_encode(['status' => 'success', 'message' => 'Settings updated successfully']);
        } catch (Exception $e) {
            //throw $th;
            echo json_encode(['status' => 'error', 'message' => 'Settings update failed']);
        }
    }
}
