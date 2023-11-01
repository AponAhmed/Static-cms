<?php

namespace Aponahmed\Cmsstatic;

use Exception;

class ImageLoader
{
    private App $app;
    private $actualRequestedPath;
    private $width;
    private $extension;
    private $quality = 100; // Default quality
    public $tall = false;
    private $webp = true;


    public function __construct($app)
    {
        $this->app = $app;
        $this->parseImageUrl($this->app->route->uri);
        //var_dump($this->app);
        $this->webp = $this->app->getSetting('virtualwebp');
    }
    public function setQuality($quality)
    {
        if ($quality < 0 || $quality > 100) {
            throw new Exception('Invalid quality value. It must be between 0 and 100.');
        }
        $this->quality = $quality;
    }

    private function parseImageUrl($imageUrl)
    {
        $pattern = "/^(.*)" . $this->app::$virtualImgDir . "\/(\d+)\/((.*)\.(\w+))$/"; // Regular expression pattern
        $pattern2 = "/^(.*)" . $this->app::$virtualImgDir . "\/((.*)\.(\w+))$/"; // Regular expression pattern

        if (preg_match($pattern, $imageUrl, $matches)) {
            $this->actualRequestedPath = $matches[3];
            $this->width =  $matches[2];
            $this->extension = $matches[5];
        } elseif (preg_match($pattern2, $imageUrl, $matches)) {
            $this->actualRequestedPath = $matches[2];
            $this->extension = $matches[4];
            $this->width = false;
        }
    }

    private function recreateImage($originalImagePath = "", $newWidth = false, $extension = "")
    {
        $originalImage = null;

        if (!file_exists($originalImagePath)) {
            //throw new Exception('Image file not found');
            $extension = 'png';
            $originalImagePath = $this->app::urlSlashFix(ROOT_DIR . "/manage/assets/icons/image-not-found.png");
        }

        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $originalImage = imagecreatefromjpeg($originalImagePath);
                break;
            case 'png':
                $originalImage = imagecreatefrompng($originalImagePath);
                break;
            case 'gif':
                $originalImage = imagecreatefromgif($originalImagePath);
                break;
            case 'webp':
                $originalImage = imagecreatefromwebp($originalImagePath);
                break;
                // Add more cases for other image types if needed
        }

        if (!$originalImage) {
            throw new Exception('Failed to create original image');
        }

        $layout = 's'; // Here 's' is the layout of the image square and 'r' is the layout of the Ratio;
        if (isset($this->app->route->query['l']) && !empty($this->app->route->query['l'])) {
            $layout = $this->app->route->query['l'];
        }
        switch ($layout) {
            case 'r':
                $newImage = $this->imagedAspectRatio($originalImage, $newWidth, $extension);
                break;
            default:
                $newImage = $this->imagedSquare($originalImage, $newWidth, $extension);
                # code...
                break;
        }
        ob_start();


        if ($this->webp  && $extension != 'gif') {
            imagewebp($newImage, null, $this->quality); // Use quality property
        } else {
            switch ($extension) {
                case 'jpeg':
                case 'jpg':
                    imagejpeg($newImage, null, $this->quality); // Use quality property
                    break;
                case 'png':
                    imagepng($newImage, null, round(9 * $this->quality / 100)); // Calculate compression level
                    break;
                case 'gif':
                    imagegif($newImage);
                    break;
                case 'webp':
                    imagewebp($newImage, null, $this->quality); // Use quality property
                    break;
                    // Add more cases for other image types if needed
            }
        }


        $imageData = ob_get_clean();

        imagedestroy($originalImage);
        imagedestroy($newImage);

        return $imageData;
    }


    function imagedSquare($originalImage, $newWidth, $extension)
    {
        $originalWidth = imagesx($originalImage);
        $originalHeight = imagesy($originalImage);

        if (!$newWidth) {
            $newWidth = $originalWidth;
        }

        $newHeight = $newWidth; // Height of the new square image

        // Create a new square image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // Allocate white color and fill the new image with white or transparent color
        if ($extension == 'png') {
            // Allocate a transparent color
            $transparentColor = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparentColor);
        } elseif ($extension == 'gif') {
            // Create a new square image with a transparent background (color index 0)
            $newImage = imagecreate($newWidth, $newHeight);
            $transparentColorIndex = imagecolorallocate($newImage, 0, 0, 0); // Allocate color index 0 (black) as transparent
            imagecolortransparent($newImage, $transparentColorIndex); // Se
        } else {
            $whiteColor = imagecolorallocate($newImage, 255, 255, 255);
            imagefill($newImage, 0, 0, $whiteColor);
        }

        // Calculate the scaling factor to fit the original image within the new square
        $scaleFactor = min($newWidth / $originalWidth, $newHeight / $originalHeight);

        // Calculate the dimensions of the scaled original image
        $scaledWidth = ceil($originalWidth * $scaleFactor);
        $scaledHeight = ceil($originalHeight * $scaleFactor);

        // Calculate the position to center the scaled original image within the new image
        $offsetX = ceil(($newWidth - $scaledWidth) / 2);
        $offsetY = ceil(($newHeight - $scaledHeight) / 2);

        //var_dump($offsetX,$offsetY,$scaledWidth,$scaledHeight);
        //exit;
        // Copy and resample the scaled original image onto the new image
        imagecopyresampled($newImage, $originalImage, $offsetX, $offsetY, 0, 0, $scaledWidth, $scaledHeight, $originalWidth, $originalHeight);

        return $newImage;
    }

    function imagedAspectRatio($originalImage, $newWidth, $extension)
    {
        $originalWidth = imagesx($originalImage);
        $originalHeight = imagesy($originalImage);
        // Calculate new height while preserving aspect ratio
        $aspectRatio = $originalWidth / $originalHeight;

        if (!$newWidth) {
            $newWidth = $originalWidth;
        }
        $newHeight = round($newWidth / $aspectRatio);

        // Check if the image is taller than it is wide
        $this->tall = ($originalHeight > $originalWidth);

        // Update width and height properties based on the new conditions
        if ($this->tall) {
            $height = $newWidth;
            $width = round($newWidth * $aspectRatio);
        } else {
            $height = $newHeight;
            $width = $newWidth;
        }

        $newImage = imagecreatetruecolor($width, $height);

        // Allocate white color and fill the new image with white or transparent color
        if ($extension == 'png') {
            $transparentColor = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparentColor);
        } elseif ($extension == 'gif') {
            // Create a new square image with a transparent background (color index 0)
            $newImage = imagecreate($newWidth, $newHeight);
            $transparentColorIndex = imagecolorallocate($newImage, 0, 0, 0); // Allocate color index 0 (black) as transparent
            imagecolortransparent($newImage, $transparentColorIndex); // Se
        } else {
            $whiteColor = imagecolorallocate($newImage, 255, 255, 255);
            imagefill($newImage, 0, 0, $whiteColor);
        }

        imagecopyresampled($newImage, $originalImage, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);
        return $newImage;
    }


    public function render()
    {
        if (
            isset($this->app->route->query['q']) &&
            $this->app->route->query['q'] != "" &&
            $this->app->route->query['q'] > 0 &&
            $this->app->route->query['q'] < 100
        ) {
            $this->quality = (int) $this->app->route->query['q'];
        }

        $imagePath = $this->app::$mediaDir . $this->actualRequestedPath;

        try {
            $imageData = $this->recreateImage(
                $imagePath,
                $this->width,
                $this->extension
            );
            if (!$this->app::$debug) {
                ob_get_clean();
            }
            // Set cache policy headers
            if ($this->webp) {
                header("Content-Type: image/webp");
            } else {
                header("Content-Type: image/{$this->extension}");
            }
            $this->app->responseHeaders();
            //header("Cache-Control: max-age=3600, public"); // Cache the image for 1 hour (adjust max-age as needed)
            //header("Expires: " . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT'); // Expires in 1 hour
            echo $imageData;
            die();
        } catch (Exception $error) {
            echo $error->getMessage();
        }
    }
}

