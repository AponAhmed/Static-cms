<?php

namespace Aponahmed\Cmsstatic\shortcode;

class TabType
{

    public $html;
    private $attr;
    public array $blocks = [];

    public function __construct($str, $attr)
    {
        $this->html = $str;
        $this->attr = $attr;
        $this->blocks = $this->parse();
        //        var_dump($this->blocks);
        //        exit;
    }

    function makeHtmlId($string)
    {
        // Remove all characters except alphanumeric and hyphen
        $string = preg_replace('/[^a-z0-9-]+/i', '-', $string);
        // Convert to lowercase
        $string = strtolower($string);
        // Remove leading and trailing hyphens
        $string = trim($string, '-');
        // Return the resulting string
        return $string;
    }

    function singleItem($qa, $cls = "")
    {
        $tag = isset($this->attr['h']) ? "h" . $this->attr['h'] : "h3";

        $html = '<div id="' . $this->makeHtmlId($qa['heading']) . '" class="qa-single ' . $cls . '">';
        $html .= "<$qa[tag]>" . $qa['heading'] . "</$qa[tag]>";
        $html .= "<p>$qa[content]</p>";
        $html .= '</div>';
        return $html;
    }

    function UiBuild()
    {
        $linkItems = '';
        $faqItems = "";
        $n = 0;
        foreach ($this->blocks as $block) {
            $n++;

            $icls = "";
            $lcls = "";
            if ($n == 1) {
                $icls = 'blink-once';
                $lcls = ' class="current" ';
            }

            $faqItems .= $this->singleItem($block, $icls);
            $linkItems .= '<li' . $lcls . '><a title="' . $block['heading'] . '" href="#' . $this->makeHtmlId($block['heading']) . '">' . $block['heading'] . '</a></li>';
        }

        $sidebarTitle = 'Key Points';
        if (isset($this->attr['title']) && !empty($this->attr['title'])) {
            $sidebarTitle = $this->attr['title'];
        }
        $ol = isset($this->attr['number']) ? $this->attr['number'] : false;
        $ol = $ol == 'yes' ? "ol" : "ul";
        $htm = "";
        $rightSidebar = isset($this->attr['sidebar']) ? 'sidebar-' . $this->attr['sidebar'] : 'sidebar-right';

        $htm .= "<div class='qa-wrap tab-type-wrap $rightSidebar'>";
        $htm .= "<div class='wa-sidebar'>";
        $htm .= "<h2 class='sidebar-title'>$sidebarTitle</h2>";
        $htm .= "<$ol>$linkItems</$ol>";
        $htm .= "</div>"; //sidebar
        $htm .= "<div class='wa-content has-title'>";
        $htm .= $faqItems;
        $htm .= "</div>"; //content
        $htm .= "</div>"; //Wrap

        return $htm;
    }

    private function parse()
    {
        $doc = new \DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $this->html);

        $headingTags = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
        $blocks = array();

        foreach ($headingTags as $headingTag) {
            $headings = $doc->getElementsByTagName($headingTag);

            foreach ($headings as $heading) {
                $contentBlock = '';
                $node = $heading->nextSibling;

                while ($node && !in_array($node->nodeName, $headingTags)) {
                    if ($node->nodeType === XML_ELEMENT_NODE || $node->nodeType === XML_TEXT_NODE) {
                        $contentBlock .= $doc->saveHTML($node); // Capture inner HTML
                    }
                    $node = $node->nextSibling;
                }

                $contentBlock = trim($contentBlock);

                if (!empty($contentBlock)) {
                    $blocks[] = array(
                        'tag' => $headingTag,
                        'heading' => $heading->nodeValue,
                        'content' => $contentBlock,
                    );
                }
            }
        }
        return $blocks;
    }
}

class HTMLIndexer
{

    private $htmlText;
    private $headings;
    private $navHtml;
    private $attr;

    public function __construct($htmlText, $atts)
    {
        $this->attr = $atts;
        $this->htmlText = $htmlText;
        $this->headings = [];
        $this->navHtml = '';
        $this->processHTML();
    }

    public function processHTML()
    {
        $this->findHeadingRegex();

        $this->replaceHeadings();
        $this->buildNavHTML();
    }

    private function findHeadingRegex()
    {
        $headingTags = [];

        //Regex way
        $re = '/(<(h[1-6])[^>]*>)(.*?)(<\/(h[1-6])>)/';
        $c = preg_match_all($re, $this->htmlText, $matches, PREG_SET_ORDER, 0);
        if ($c) {
            foreach ($matches as $el) {
                $uniqueId = $this->generateUniqueId();
                $this->headings[] = [
                    'id' => $uniqueId,
                    'full-tag' => $el[0]
                ];
            }
        }
    }

    private function findHeadings()
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($this->htmlText);
        libxml_clear_errors();

        $headingTags = [];

        $h1Tags = iterator_to_array($dom->getElementsByTagName('h1'));
        $h2Tags = iterator_to_array($dom->getElementsByTagName('h2'));
        $h3Tags = iterator_to_array($dom->getElementsByTagName('h3'));
        $h4Tags = iterator_to_array($dom->getElementsByTagName('h4'));
        $strongTags = iterator_to_array($dom->getElementsByTagName('strong'));
        $headingTags = array_merge($headingTags, $h1Tags, $h2Tags, $h3Tags, $h4Tags, $strongTags);

        foreach ($headingTags as $heading) {
            //if ($heading->tagName === 'strong' && !$this->hasValidParent($heading)) {
            //    continue; // Skip <strong> tags without a valid parent
            //}

            $uniqueId = $this->generateUniqueId();
            $fullTag = $dom->saveHTML($heading);
            $this->headings[] = [
                'id' => $uniqueId,
                'full-tag' => $fullTag
            ];
        }
    }

    private function hasValidParent($element)
    {
        $validParents = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
        $parent = $element->parentNode;

        while ($parent !== null && $parent instanceof \DOMElement) {
            if (in_array($parent->tagName, $validParents)) {
                return true;
            }
            $parent = $parent->parentNode;
        }

        return false;
    }

    private function replaceHeadings()
    {
        foreach ($this->headings as $heading) {
            $this->htmlText = str_replace($heading['full-tag'], $this->addIdToHeading($heading['full-tag'], $heading['id']), $this->htmlText);
        }
    }

    private function addIdToHeading($fullTag, $uniqueId)
    {
        return preg_replace('/(<(h[1-6])[^>]*>)(.*?)(<\/(h[1-6])>)/', '$1<span id="' . $uniqueId . '"></span>$3$4', $fullTag);
    }

    private function buildNavHTML()
    {
        $sidebarTitle = 'Key Points';
        if (isset($this->attr['title']) && !empty($this->attr['title'])) {
            $sidebarTitle = $this->attr['title'];
        }
        $ol = isset($this->attr['number']) ? $this->attr['number'] : false;
        $ol = $ol == 'yes' ? "ol" : "ul";

        $nav = "<h2 class='sidebar-title'>$sidebarTitle</h2><$ol>";
        foreach ($this->headings as $heading) {
            $nav .= '<li class="nav-list-item" id="nav-' . $heading['id'] . '"><a href="#' . $heading['id'] . '">' . strip_tags($heading['full-tag']) . '</a></li>';
        }
        $nav .= "</$ol>";
        $this->navHtml = $nav;
    }

    private function generateUniqueId()
    {
        return substr(md5(uniqid(mt_rand(), true)), 0, 8);
    }

    public function getOutput()
    {
        $rightSidebar = isset($this->attr['sidebar']) ? 'sidebar-' . $this->attr['sidebar'] : 'sidebar-right';

        $output = '<div class="qa-wrap ' . $rightSidebar . ' ">';
        $output .= '<div class="wa-sidebar nav-index">' . $this->navHtml . '</div>';
        $output .= '<div class="wa-content content-index has-title">' . $this->htmlText . '</div>';
        $output .= '</div>';
        return $output;
    }
}

class InnerLinkBuilder
{

    private $attributes;
    private $QAs;
    private $generalMode = false;
    //-------------
    //output
    private $links = "";

    public function __construct()
    {
        global $hook;
        $hook->add_filter('pre_rander', [$this, 'innerLink'], 3); //final_output
    }

    function makeHtmlId($string)
    {
        // Remove all characters except alphanumeric and hyphen
        $string = preg_replace('/[^a-z0-9-]+/i', '-', $string);
        // Convert to lowercase
        $string = strtolower($string);
        // Remove leading and trailing hyphens
        $string = trim($string, '-');
        // Return the resulting string
        return $string;
    }

    function attrParse($attStr)
    {

        $this->attributes = [];
        if (!empty($attStr)) {
            preg_match_all('/(\w+)\s*=\s*"([^"]*)"/', $attStr, $AttMatches);
            foreach ($AttMatches[1] as $i => $attribute) {
                $value = $AttMatches[2][$i];
                $this->attributes[$attribute] = $value;
            }
        }
    }

    function QAParse($str)
    {
        $this->QAs = [];
        if (!empty($str)) {
            //$re = '/Q:\s*(.+?)\s+A:\s*((?:(?!Q:|A:|<\/?<ul>).)+)/s';
            //$re = '/Q:\s*(.+?)<br\s*>A:\s*((?:(?!Q:|A:|<\/?p>).)+)/s';
            $re = '/Q:\s*(.+?)\s+A:\s*((?:(?!Q:|A:|<\/?p>).)+)/s';
            $cc = preg_match_all($re, $str, $QAmatches); //'/Q:\s*(.+)\s+A:\s*(.+)/'            
            if (!$cc) {
                $this->generalMode = true;
            }
            foreach ($QAmatches[1] as $i => $question) {
                $answer = trim($QAmatches[2][$i]);
                $question = trim(str_replace("<br>", "", $question));
                $this->QAs[] = array('question' => $question, 'answer' => $answer);
            }
        }
    }

    function innerLink($content)
    {
        $re = '/((<p>|)\[innerLink(.*?)(#(.*?)|)\](<\/p>|))(.*?)((<p>|)\[\/innerLink\](<\/p>|))/s';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);

        if (count($matches) > 0) {
            $fullMatch = $matches[0][0];
            $text = trim($matches[0][7]);
            $attStr = trim($matches[0][3]);
            //---------------------------
            $this->attrParse($attStr);
            //var_dump($this->attributes,$this->generalMode);
            //var_dump($this->QAs);
            //exit;
            $this->QAParse($text);
            if ($this->generalMode) {
                if (isset($this->attributes['type']) && $this->attributes['type'] == 'single') {
                    $processor = new TabType($text, $this->attributes);
                    $htm = $processor->UiBuild();
                } else {
                    $processor = new HTMLIndexer($text, $this->attributes);
                    $htm = $processor->getOutput();
                }
            } else {
                $htm = $this->UiBuild();
            }
            $content = str_replace($fullMatch, $htm, $content);
        }
        return $content;
    }

    function singleItem($qa, $cls = "")
    {
        $tag = isset($this->attributes['h']) ? "h" . $this->attributes['h'] : "h3";

        $html = '<div id="' . $this->makeHtmlId($qa['question']) . '" class="qa-single ' . $cls . '">';
        $html .= "<$tag>$qa[question]</$tag>";
        $html .= "<p>$qa[answer]</p>";
        $html .= '</div>';
        return $html;
    }

    function UiBuild()
    {
        $linkItems = '';
        $faqItems = "";
        $n = 0;
        foreach ($this->QAs as $qa) {
            $n++;
            $icls = "";
            $lcls = "nav-list-item";
            if ($n == 1) {
                $icls = 'blink-once';
                $lcls = ' current';
            }

            $faqItems .= $this->singleItem($qa, $icls);
            $linkItems .= '<li class="' . $lcls . '" id="nav-' . $this->makeHtmlId($qa['question']) . '"><a title="' . $qa['question'] . '" href="#' . $this->makeHtmlId($qa['question']) . '">' . $qa['question'] . '</a></li>';
        }

        $rightSidebar = isset($this->attributes['sidebar']) ? 'sidebar-' . $this->attributes['sidebar'] : 'sidebar-right';

        $htm = "";
        $htm .= "<div class='qa-wrap $rightSidebar'>";
        $htm .= "<div class='wa-sidebar'>";
        $htm .= "<ul>$linkItems</ul>";
        $htm .= "</div>"; //sidebar
        $htm .= "<div class='wa-content'>";
        $htm .= $faqItems;
        $htm .= "</div>"; //content
        $htm .= "</div>"; //Wrap

        return $htm;
    }
}
