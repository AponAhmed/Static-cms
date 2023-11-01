<?php

namespace Aponahmed\Cmsstatic;

use Aponahmed\Cmsstatic\shortcode\InnerLinkBuilder;
use Aponahmed\Cmsstatic\Traits\Main;
use Aponahmed\Cmsstatic\Utilities\Singleton;

class View extends Singleton
{
    use  Main;

    private PageLoader $page;
    private $html = '';
    private $globals = [];
    public Hook $hook;
    private $perform;

    public function __construct($global)
    {
        global $hook, $_p;
        $this->perform = $_p;
        $this->hook = $hook;
        $this->global = $global;
        $this->init();
        $this->before_shortcode(); //shortcode-tag of inner-link

        $this->hook->add_filter('the_content', [$this, 'content_shortcode_filter']);
        $this->hook->add_filter('pre_rander', [$this, 'all_shortcode_apply'], 5); //Pages {}
        $this->hook->add_filter('pre_rander', [$this, 'render_filter'], 10);
        $this->hook->add_filter('pre_rander', [$this, 'title_shortcode_apply'], 11);
        $this->hook->add_filter('pre_rander', [$this, 'shortcode_core'], 12);
        $this->hook->add_filter('pre_rander', [$this, 'replace_final'], 13);
    }

    function content_shortcode_filter($content)
    {
        return $content;
    }

    function shortcode_core($str)
    {
        $shortcodeParser = new ShortcodeParser($str);
        return $shortcodeParser->getContent();
    }

    function before_shortcode()
    {
        new  InnerLinkBuilder();
    }

    function replace_final($string)
    {
        $replaceArr = [
            'usa'   => 'USA',
            'bd'    => 'BD',
            'uk'    => 'UK',
            'uae'   => 'UAE',
            'oem'   => 'OEM',
            'odm'   => 'ODM',
        ];

        // Create an array to store the original case of the search terms
        $originalCase = [];

        // Convert the keys of $replaceArr to lowercase
        $replaceArrLower = array_change_key_case($replaceArr, CASE_LOWER);

        // Iterate through the search terms and store their original case
        foreach ($replaceArr as $searchTerm => $replaceValue) {
            $originalCase[strtolower($searchTerm)] = $searchTerm;
        }
        // Define a regular expression pattern to match words within HTML content
        $pattern = '/<[^>]*>(*SKIP)(*F)|\b(?:' . implode('|', array_keys($replaceArrLower)) . ')\b/i';
        // Use preg_replace_callback to replace the matched words inside HTML content
        $string = preg_replace_callback($pattern, function ($match) use ($replaceArrLower, $originalCase) {
            $lowerCaseMatch = strtolower($match[0]);
            $replacement = $replaceArrLower[$lowerCaseMatch];
            return $originalCase[$lowerCaseMatch] === $lowerCaseMatch ? $replacement : $originalCase[$lowerCaseMatch];
        }, $string);

        return $string;
    }


    function prepareView()
    {
        $this->perform->start('PageLoader', "Page Loader", ['file' => __FILE__, 'line' => __LINE__]);
        $this->page = new PageLoader($this->route->getSegments(), $this->global, $this->hook);
        ob_start();
        $this->page->render();
        $this->perform->end('PageLoader');
        //exit;
        $this->global = $this->page->getGlobal();
        //var_dump($this->global);
        $this->html = ob_get_clean();
        $this->perform->end('PageLoader');
    }

    function segmentReplace($segments, $content)
    {
        $n = count($segments);
        $finds = [];
        $replace = [];
        for ($i = 0; $i < $n; $i++) {
            $scodeIndex = $i + 1;
            $code = "{segment-$scodeIndex}";
            $val = $segments[$i];
            $val = str_replace('-', ' ', $val);
            $val = ucwords($val);

            $finds[] = $code;
            $replace[] = $val;
        }
        $content = str_replace($finds, $replace, $content);
        return $content;
    }

    function render_filter($htm)
    {
        //Segment filter
        $segments = $this->route->getSegments();
        $n = count($segments);
        if ($n > 1) {
            $htm = $this->segmentReplace($segments, $htm);
        }
        //Here Default Segments Replacement
        if (property_exists($this->page->data, 'default_segments')) {
            $defSegments = explode(",", $this->page->data->default_segments);
            $htm = $this->segmentReplace($defSegments, $htm);
        }
        //Replace non existing
        $re = '/\{segment-(\d+)\}/m';
        $htm = preg_replace($re, "", $htm);
        //echo "<pre>";
        return $htm;
    }

    function title_shortcode_apply($html)
    {
        $title = "";
        if (isset($this->global['title'])) {
            $title = $this->global['title'];
        }
        $re = '/\{title\}/m';
        $html = preg_replace($re, $title, $html);
        return $html;
    }

    /**
     * Single Tag Filter - remove trailing slashes from ending tags
     */
    function filterTags()
    {

        // Use a regular expression to match all self-closing tags and remove the trailing slash
        // $this->html = preg_replace('/<([^>]+?)\/>/', '<$1>', $this->html);
        $tagsToFilter = array('br', 'hr', 'img');
        // Loop through each tag and replace it without the trailing slash
        foreach ($tagsToFilter as $tag) {
            $this->html = preg_replace("/<$tag(.*?)\/>/", "<$tag$1>", $this->html);
        }
    }

    public function rander()
    {
        $this->prepareView();
        $this->html = $this->hook->apply_filters('pre_rander', $this->html);
        $this->filterTags();
        $this->responseHeaders();

        echo $this->html;
    }


    function all_shortcode_apply($html = "")
    {

        $shortcodes = ['title', 'slug', 'h1', 'meta-title', 'meta-desc', 'meta-key'];
        foreach ($shortcodes as $code) {
            $regex = "/\{$code\}/m";
            $propertyName = str_replace("-", "_", $code);
            $replace = "";
            if ((isset($this->global['page'])) && property_exists($this->global['page'], $propertyName)) {
                $replace = $this->global['page']->$propertyName;
                if ($code == 'slug') {
                    $replace = ucwords(str_replace("-", " ", $replace));
                }
            }
            $html = preg_replace($regex, $replace, $html);
        }
        return $html;
    }
}
