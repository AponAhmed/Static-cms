<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;

use Aponahmed\Cmsstatic\manage\Model\Media;
use Aponahmed\Cmsstatic\manage\Model\MediaCategory;
use Aponahmed\Cmsstatic\Traits\Main;
use Exception;

class MediaController extends Controller
{
    use Main;

    public $subDir;
    function __construct($global)
    {
        $this->global = $global;
        parent::__construct();
        $this->subDir = "";
        //var_dump(self::getApproxDeviceWidth());
        //exit;
        $this->router();
    }

    function router()
    {
        $segments = $this->route->segments;
        if (end($segments) == 'upload') {
            $this->UploadSubdirSet($segments);
            $this->view('media.upload', ['upload2Dir' => $this->subDir]);
        } else {
            switch ($this->childSegment) {
                case 'new-dir':
                    # code...
                    $this->newDirectory();
                    break;
                case 'store':
                    # code...
                    $this->upload();
                    break;
                case 'rename':
                    # code...
                    $this->rename();
                    break;
                case 'delete':
                    # code...
                    $this->delete();
                    break;
                default:
                    # code...
                    $this->gridView();
            }
        }
    }

    function upload()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"]) && isset($_POST["dir"])) {
            // Check if the file was uploaded without errors
            if ($_FILES["image"]["error"] == UPLOAD_ERR_OK) {
                $additionalDir = $_POST["dir"]; // Get the additional directory from the AJAX request
                $targetDir = self::urlSlashFix(self::$mediaDir . "/" . $additionalDir); // Concatenate the additional directory with the base target directory

                // Create the target directory if it doesn't exist
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $pathInfo = pathinfo(basename($_FILES["image"]["name"]));
                $originalFileName = self::slugify($pathInfo['filename']);
                $targetFile = $targetDir . '/' . $originalFileName . "." . $pathInfo['extension'];

                if (file_exists($targetFile)) {
                    $randomNumber = rand(10, 20);
                    $targetFile = $targetDir . '/' . $randomNumber . "-" . $originalFileName . "." . $pathInfo['extension'];
                }


                // Move the uploaded file to the desired directory
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                    // Prepare the response data
                    $response = array(
                        'filename' => $originalFileName,
                        'imageUrl' => $targetFile,
                        'size' => $_FILES["image"]['size'],
                    );

                    // Send the response as JSON
                    header('Content-Type: application/json');
                    echo json_encode($response);
                } else {
                    // Error moving the uploaded file
                    http_response_code(500);
                    echo json_encode(array('error' => 'Error moving the uploaded file.'));
                }
            } else {
                // Error in the uploaded file
                http_response_code(400);
                echo json_encode(array('error' => 'Error in the uploaded file.'));
            }
        } else {
            // Invalid request
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request.'));
        }

        exit;
    }

    // Function to rename a directory
    public static function renameDirectory($subDir, $fromName, $toName)
    {
        $dir = new MediaCategory($fromName, $subDir);
        try {
            $dir->rename($toName);
            return $dir->url;
        } catch (Exception $e) {
            return false;
        }
    }

    // Function to rename a file
    public static function renameFile($subDir, $fromName, $toName)
    {
        $file = new Media($fromName, $subDir);
        try {
            $file = $file->rename($toName);
            return $file->url;
        } catch (Exception $e) {
            return false;
        }
    }

    // Function to get the file extension from a filename
    public static function getFileExtension($filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    //Ajax Request Handler
    function rename()
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

        // Access the required fields
        $type = $postData['type'];
        $fromName = $postData['from-name'];
        $toName = $postData['to-name'];
        $subDir = $postData['subDir'];

        // Perform renaming using the performRename method
        $success = false;
        if ($type === 'dir') {
            $success = self::renameDirectory($subDir, $fromName, $toName);
        } elseif ($type === 'file') {
            $success = self::renameFile($subDir, $fromName, $toName);
        }

        // Respond to the client based on the success of the operation
        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Renaming successful', 'url' => $success]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Renaming failed']);
        }
    }

    function delete()
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

        $fileName = $postData['fileName'];
        $subDir = $postData['subDir'];
        $type = $postData['type'];

        if ($type == 'file') {
            $media = new Media($fileName, $subDir);
            if ($media->delete()) {
                echo json_encode(['status' => 'success', 'message' => 'Image File Deleted successfully']);
            }
        } else {
            $mediaDir = new MediaCategory($fileName, $subDir);
            if ($mediaDir->delete()) {
                echo json_encode(['status' => 'success', 'message' => 'Image Directory Deleted successfully']);
            }
        }
    }

    function UploadSubdirSet($segments)
    {
        unset($segments[0]); //remove Admin 
        unset($segments[1]); //remove media
        array_pop($segments); //Upload remove
        $this->subDir = implode("/", $segments);
    }


    function gridView()
    {

        $segments = $this->route->getSegments();
        unset($segments[0]); //remove Admin 
        unset($segments[1]); //remove media


        $currentMediaPath = self::$mediaDir;
        if (isset($segments[2]) && $segments[2] != 'add') {
            foreach ($segments as $folder) {
                $this->subDir = self::urlSlashFix($this->subDir . '/' . $folder);
                $currentMediaPath = self::urlSlashFix(self::$mediaDir . "/" . $this->subDir  . "/");
            }
        }

        $resources = $this->getResource($currentMediaPath);
        //echo "<pre>";
        //var_dump($resources);
        $this->view('media.grid', $resources);
    }

    function getResource($directory)
    {
        $files = scandir($directory);
        $imageFiles = array();
        $folders = array();

        foreach ($files as $file) {
            $fullPath = $directory . DIRECTORY_SEPARATOR . $file;
            if ($file == 'index.html') {
                continue;
            }

            if (is_dir($fullPath)) {
                self::silence($fullPath);

                if ($file !== '.' && $file !== '..') {
                    $folders[] = new MediaCategory($file, $this->subDir);
                }
            } elseif ($this->isImageFile($fullPath)) {
                $imageFiles[] = new Media($file, $this->subDir);
            }
        }

        return array(
            'dirs' => $folders,
            'files' => $imageFiles,
        );
    }

    function newDirectory()
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
        if (isset($postData['name'])) {
            $dirName = self::slugify($postData['name']);
            $dir = new MediaCategory($dirName, $postData['subDir']);
            if ($dir->save()) {
                echo json_encode(['status' => 'success', 'url' => $dir->url, 'name' => $dir->name]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'could not create directory']);
            }
        }
    }
}
