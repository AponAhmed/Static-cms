<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;

use Aponahmed\Cmsstatic\manage\Model\Settings;
use Aponahmed\Cmsstatic\Utilities\ApiRequest;
use Exception;

class ContactsController extends Controller
{
    function __construct($global = [])
    {
        $this->global = $global;
        parent::__construct();


        //Route in controller
        switch ($this->childSegment) {
            case 'remove':
                $this->removeFile();
                break;
            case 'upload':
                $this->uploadFile();
                break;
            case 'send':
                # code...
                $this->send();
                break;
            default:
                # code...
                $this->settings();
        }
    }

    function settings()
    {
        $settings = new Settings();
        $this->view('contact.settings', ['settings' => $settings]);
    }

    function removeFile()
    {
        $uploadDirectory = ROOT_DIR . '/contents/contact-uploads/';
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (isset($_POST['filename']) && !empty($_POST['filename'])) {
            $filename = trim($_POST['filename']);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (in_array($ext, $allowedExtensions) && file_exists($uploadDirectory . $filename)) {
                $filePath = $uploadDirectory . $filename;
                unlink($filePath);
            } else {
                echo "You are not allowed to perform this action";
            }
        }
    }


    function uploadFile()
    {
        // Set the directory where uploaded images will be stored
        $uploadDirectory = ROOT_DIR . '/contents/contact-uploads/';
        $UrlPrefix = self::$siteurl . "/contents/contact-uploads/";

        // Check if the directory exists, if not, create it
        if (!file_exists($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
            $this->silence($uploadDirectory);
        }

        // Define the allowed image file types
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        // Define the maximum file size (1MB in bytes)
        $maxFileSize = 1024 * 1024; // 1MB

        // Initialize the response array
        $response = ['success' => false, 'message' => ''];

        // Check if a file was uploaded
        if (isset($_FILES['image'])) {
            $file = $_FILES['image'];

            // Check if the uploaded file is a valid image
            if (in_array($file['type'], $allowedTypes)) {
                // Check if the file size is within the allowed limit
                if ($file['size'] <= $maxFileSize) {
                    // Generate a unique filename
                    $fileName = uniqid() . '_-_' . $file['name'];
                    $filePath = $uploadDirectory . $fileName;

                    // Move the uploaded file to the desired location
                    if (move_uploaded_file($file['tmp_name'], $filePath)) {
                        // File was successfully uploaded
                        $response['success'] = true;
                        $response['message'] = 'File uploaded successfully.';
                        $response['name'] = $fileName;
                        $response['url'] = self::urlSlashFix($UrlPrefix . "/" . $fileName);
                    } else {
                        // Error occurred while moving the file
                        $response['message'] = 'Error uploading file.';
                    }
                } else {
                    // File size exceeds the allowed limit
                    $response['message'] = 'File size exceeds the allowed limit (1MB).';
                }
            } else {
                // Invalid file type
                $response['message'] = 'Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.';
            }
        } else {
            // No file was uploaded
            $response['message'] = 'No file uploaded.';
        }

        // Return the response as JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function attachmentsHtml($attachments)
    {
        $UrlPrefix = self::urlSlashFix(self::$siteurl . "/contents/contact-uploads/");
        $htm = "
        <div style=\"margin-top:20px;margin-bottom: 10px;padding-bottom:5px;border-bottom:1px solid #eee;\"><strong>" . count($attachments) . " Attachments</strong></div>
        <div style='display:flex;flex-wrap: wrap;'>";
        foreach ($attachments as $attachment) {
            $info = explode("_-_", $attachment);
            $name = $info[1];
            $url = $UrlPrefix . $attachment;
            $htm .= "<div style=\"max-width:350px;margin:2px;width:100%;background:#f7f7f7;border:1px solid #e3e1e1;padding:4px 10px;border-radius: 3px;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;\"><a style=\"text-decoration: none;\" href=\"$url\">$name</a></div>";
        }
        $htm .= "</div>";
        return $htm;
    }



    function send()
    {
        // Usage:
        $apiEndpoint = $this->GetSetting('contact_api_path');
        $apiKey = $this->GetSetting('contact_api_key');
        $data = $_POST;

        $attachmentHtm = "";
        if (isset($data['attachments']) && count($data['attachments']) > 0) {
            $attachmentHtm = $this->attachmentsHtml($data['attachments']);
        }

        //$subject = stripslashes($data['subject']);
        $message = nl2br($data['message']); //Orginal Message   
        $clientIP = $this->getClientIP();
        //$country = $this->convertip($clientIP);

        //Tracking
        $traking = "\n<b>Name:</b> $data[name]";
        $traking .= "<br/>\n<b>Email:</b> $data[email]";
        $traking .= "<br/>\n<b>Date:</b> " . date("F d, Y");
        $traking .= "<br/><b>Website:</b> " . self::$siteurl;
        $traking .= "<br/>";
        $message = stripslashes($traking . "<br>" . $message);
        $message .= "<br>" . $attachmentHtm;


        //Replace Data
        $data['message'] = $message;
        $data['ip'] = $clientIP;
        $apiRequest = new ApiRequest($apiEndpoint, $apiKey);
        try {
            $apiResponse = $apiRequest->send($data);
            echo $apiResponse;
        } catch (Exception $e) {
            echo  $e->getMessage();
        }
    }

    public static function form()
    {
        $htmlString = '
                    <div class="contactForm">
                        <form id="contactForm">
                            <div class="input-wrap gac-name-in"><label>Your Name (required)</label><input name="name" type="text" class="contactFormField" required=""> </div>
                            <div class="input-wrap gac-email-in"><label>Your Email (required)</label><input name="email" type="email" class="contactFormField" required=""> </div>
                            <div class="input-wrap gac-subject-in"><label>Subject</label><input name="subject" type="text" class="contactFormField"> </div>
                            <div class="input-wrap gac-message-in"><label>Message (required)</label><textarea class="contactFormField" name="message" required=""></textarea></div>
                            <div class="input-wrap gac-upload-in">
                                <label>Upload Referance <span class="hint">Only image file are allowed and a file should less then 1 MB</span></label>
                                <div class="fileUploader">
                                    <div id="previewArea"></div>
                                    <label for="uploadTriger" class="browse"><svg viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M256 112v288M400 256H112"></path></svg></label>
                                    <input type="file" multiple="true" style="display:none" id="uploadTriger">
                                </div>
                            </div>
                            <div class="question">
                                <label id="question"></label>
                                <input id="ans" class="contactFormField" type="text" required="">
                            </div>

                            <div class="contact-footer">
                                <button type="submit" id="submitBtn" class="contactFormButton btn btn-primary">Send</button>
                                <span class="contactMsg"></span>
                            </div>
                        </form>
                    </div>';

        return $htmlString;
    }

    function convertip($ip)
    {
        //?fields=country,city,lat,lon
        $path = $this->GetSetting('ip_api_path');
        $url = str_replace('{ip}', $ip, $path);
        $content = file_get_contents($url);
        $ob = json_decode($content);
        if (isset($ob->status) && $ob->status == 'success') {
            return $ob->city . "," . $ob->country;
        }
    }
}
