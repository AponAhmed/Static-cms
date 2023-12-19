<?php

namespace Aponahmed\Cmsstatic\manage\Model;


class Slider extends Model
{

    function __construct($file)
    {
        parent::__construct();
        $this->modelDataDir = self::urlSlashFix(self::$dataDir . "/sliders/");
        $this->getData($file);
    }

    function getImage($i)
    {
        $slug = $this->image[$i];
        return Media::getInstanse($slug)->getImage(210);
    }

    public static function getAll($dir)
    {
        $jsonFiles = [];
        // Check if the directory exists
        if (is_dir($dir)) {
            // Get all files in the directory
            $files = scandir($dir);

            foreach ($files as $file) {
                // Check if the file has a .json extension
                if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
                    // Read the JSON file and convert it to an object
                    // $jsonContent = file_get_contents($dir . '/' . $file);
                    // $jsonData = json_decode($jsonContent);
                    $slug = pathinfo($file, PATHINFO_FILENAME);
                    $jsonFiles[] = new self($slug);
                    // if ($jsonData !== null) {
                    //     $jsonFiles[] = $jsonData;
                    // }

                }
            }
        }
        return $jsonFiles;
    }

    /**
     * @param mixed $images The string of images path with seperated by (,) or linebreaks or Array
     * @return string
     */
    public static function makeSlider($images = null, $params = [], $app = null)
    {
        if (!$app) {
            return;
        }
        // Define default values for parameters
        $defaults = [
            'bullet' => false,
            'nav' => false,
            'caption' => false,
            'image_ratio' => 'r',
            'height' => 350,
            'autoslide' => "false",
            'random' => false,
            'sliderArea' => ['d' => "1:1:0", 't' => '1:1:0', 'm' => '1:1:0'] //here full width:slider:padding

        ];
        // Merge the provided parameters with the defaults
        $params = (object) array_merge($defaults, $params);

        $size = null;
        $ratio = true;
        $screenW = $app->getApproxDeviceWidth();
        $device = $app->getDeviceType();

        if ($device == 'desktop') {
            $size = $screenW - 246;
            //Area Calculation
            if (isset($params->sliderArea['d'])) {
                $areaRetioPart = explode(":", $params->sliderArea['d']);
                if ($areaRetioPart[0] > 0 &&  ($areaRetioPart[0] != $areaRetioPart[1])) {
                    $size = ceil(($size / $areaRetioPart[0]) * $areaRetioPart[1]);
                }
                if (isset($areaRetioPart[2])) { //Padding will be deduced from area 
                    $size -= ($areaRetioPart[2] * 2);
                }
            }
        } else {
            $size = $screenW - 62;
            if (isset($params->sliderArea['m'])) {
                $areaRetioPart = explode(":", $params->sliderArea['m']);
                if ($areaRetioPart[0] > 0 &&  ($areaRetioPart[0] != $areaRetioPart[1])) {
                    $size = ceil(($size / $areaRetioPart[0]) * $areaRetioPart[1]);
                }
                if (isset($areaRetioPart[2])) { //Padding will be deduced from area 
                    $size -= ($areaRetioPart[2] * 2);
                }
            }
        }




        if ($params->image_ratio != 'r') {
            $ratio = false;
        }
        $cls = "";
        if ($ratio) {
            $cls = " image-ratio";
        } else {
            $cls = " image-squre";
        }

        if ($params->caption) {
            $cls .= " has-captions";
        }
        $height = $params->height ? $params->height : 350;

        //Images Array
        if (!is_array($images)) {
            // Split the string by both commas and new lines
            $images = preg_split("/,|\n/", $images);
            $images = array_filter($images, 'trim');
            // Remove any empty elements from the resulting array
            $images = array_filter($images, 'strlen');
        }
        if ($params->random) {
            shuffle($images);
        }

        $html = '<div data-autoslide="' . $params->autoslide . '" class="slider-wrap' . $cls . '" style="height:100%">';

        $i = 0;
        foreach ($images  as $image) {
            $attr = [];
            $i++;
            if ($i == 1) {
                $attr = ['title' => page()->feature_image_title];
            } else {
                $attr['loading'] = 'lazy';
            }
            $image = Media::getInstanse($image);
            $html .= '<div class="slider-item">';
            $html .= '<div class="slider-image">';
            $html .= $image->getImage($size, $ratio, $attr);
            $html .= '</div>';

            if ($params->caption) {
                $html .= '<h2 class="image-caption">' . $image->name . '</h2>';
            } else {
                $html .= '<h2 class="image-caption-d">' . $image->name . '</h2>';
            }
            $html .= '</div>';
        }
        // if ($params->bullet) {
        //     $html .= '<div class="bullets"></div>';
        // }

        if ($params->nav) {
            $html .= '<button class="slider-arrow" aria-label="Image Slide Previous" id="prev-slide"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></button>';
            $html .= '<button class="slider-arrow" aria-label="Image Slide Next" id="next-slide"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></button>';
        }

        $html .= '</div>';
        return $html;
    }

    function ClickableGallery()
    {
        $screenW = $this->getApproxDeviceWidth();
        $device = $this->getDeviceType();
        $size = null;
        if ($device == 'desktop') {
            $size = ($screenW - 236) / 2;
        } else {
            $size = $screenW - 90;
        }

        $htmList = "<ul class=\"image-list\">";
        $tag = $this->title_tag;
        for ($i = 0; $i < count($this->image); $i++) {
            $image = Media::getInstanse($this->image[$i]);
            $link = "";
            if (isset($this->links[$i]) && !empty($this->links[$i])) {
                $link = trim($this->links[$i]);
            }
            $cls = $i == 0 ? 'current' : '';
            $n = $device != "desktop" ? "<span class='number-circle'>" . ($i + 1) . "</span>" : '';
            $htmList .= "<li class=\"image-item $cls\" data-url=\"$link\" data-src=\"" . $image->getSize($size) . "\" data-title=\"" . $image->name . "\"><$tag>$n $image->name</$tag></li>";
        }
        $htmList .= "</ul>" . "\n";

        $imageView = '';
        //First Image in View
        if (isset($this->image[0])) {
            $firstImage = Media::getInstanse($this->image[0]);
            $imageView = $firstImage->getImage($size);
        }

        if (isset($this->links[0]) && !empty($this->links[0])) {
            $link = $this->links[0];
            $imageView = '<a href="' . $link . '">' . $imageView . '</a>';
        }

        if ($device == 'desktop') {
            return '<div class="gallery-hoverable">
            <div class="gallery-detail">
            <div class="gallery-content">' . $this->single_content . '</div>
            ' . $htmList . '
            </div>
            <div class="view-image">
                <div class="loader"></div>
                ' . $imageView . '
            </div>
        </div>';
        } else {
            return '<div class="gallery-hoverable">
            <div class="gallery-detail">
                <div class="gallery-content">' . $this->single_content . '</div>
            </div>
            <div class="view-image">
                <div class="loader"></div>
                ' . $htmList . '
                ' . $imageView . '
            </div>
        </div>';
        }
    }

    public function html()
    {
        $size = null;
        $ratio = true;
        $screenW = $this->getApproxDeviceWidth();
        $device = $this->getDeviceType();
        if ($this->clickable) {
            return $this->ClickableGallery();
        }
        if ($this->type == 'full-width') {
            if ($device == 'desktop') {
                $size = $screenW - 236;
            } else {
                $size = $screenW - 60;
            }
        } else {
            $ratio = false;
            if ($device == 'desktop') {
                $size = ($screenW - 236) / 2;
            } else {
                $size = $screenW - 90;
            }
        }
        if ($this->image_ratio == 'r') {
            $ratio = true;
        }
        $cls = "";
        if ($ratio) {
            $cls = " image-ratio";
        } else {
            $cls = " image-squre";
        }

        $height = $this->height ? $this->height : 350;
        $html = '<div class="slider-wrap' . $cls . '" style="height:' . $height . 'px">';
        for ($i = 0; $i < count($this->image); $i++) {
            $attr = [];
            $image = Media::getInstanse($this->image[$i]);

            $html .= '<div class="slider-item">';
            if ($this->type !== 'two-column-single') {
                $html .= '<div class="slider-text">';
                $html .= '<' . $this->title_tag . ' class="slider-item-title">' . $this->image_title[$i] . '</' . $this->title_tag . '>';
                $html .= '<p>' . $this->image_description[$i] . '</p>';
                $html .= '</div>';
            }
            $html .= '<div class="slider-image">';
            if ($i > 0) {
                $attr['loading'] = 'lazy';
            }
            $html .= $image->getImage($size, $ratio, $attr);
            $html .= '</div>';

            $html .= '</div>';
        }
        if ($this->bullet) {
            $html .= '<div class="bullets"></div>';
        }

        if ($this->nav) {
            $html .= '<button class="slider-arrow" aria-label="Image Slide Previous" id="prev-slide"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></button>';
            $html .= '<button class="slider-arrow" aria-label="Image Slide Next" id="next-slide"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></button>';
        }

        $html .= '</div>';


        $classes = [];

        $classes[] = $this->type;
        if ($this->type == 'full-width') {
            $classes[] = "content-" . $this->content_pos;
        } else {
            $classes[] = "img-" . $this->img_pos;
        }
        $class = implode(" ", $classes);

        if ($this->type == 'two-column-single') {
            $singleSlide = '<div class="single-slide">';

            $singleSlide .= '<div class="slider-text">';
            $singleSlide .= $this->single_content;
            $singleSlide .= '</div>';
            $singleSlide .= $html;


            $singleSlide .= '</div>';

            $html = $singleSlide;
        }

        return "<div class='slider-outer $class'>" . $html . "</div>";
    }
}
