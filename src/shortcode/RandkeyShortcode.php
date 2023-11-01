<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\ShortcodeParser;


class RandkeyShortcode implements Shortcode
{
    public $attributes;
    private $keyFile;
    private $keys;

    public function __construct($attributes, public ShortcodeParser $app)
    {

        $this->keyFile = $app::urlSlashFix($app::$dataDir . "/randKey.txt");
        $default = [
            'n'     => "1",
            'key'   => "",
            'h'     => "2",
            'hl'    => "2",
            'pl'    => "80",
            'sl'    => "3",
            'col'   => "1",
            'show'  => "false",
            'link'  => "false"
        ];
        $this->attributes = array_merge($default, $attributes);
    }


    function filter()
    {
        if (file_exists($this->keyFile)) {
            $this->getKey();
            return $this->generate();
        }
        return '';
    }

    function getKey()
    {
        $str = stripslashes(file_get_contents($this->keyFile));
        $keys = array_unique(array_filter(explode(",", $str)));
        //var_dump(count($keys));
        //exit;
        $this->keys = $keys;
    }

    function generate()
    {
        $blocks = $this->blockGenerator();

        if (is_numeric($this->attributes['h'])) {
            $hTag = "h" . $this->attributes['h'];
        } else {
            $hTag = $this->attributes['h'];
        }

        $colW = 12 / $this->attributes['col'];

        $html = "";
        $html .= "<div class='box-row key-data-wrap'>";
        foreach ($blocks as $block) {
            $selPrefix = "";
            $para = trim($selPrefix . " " . $block['p']);

            $headingTxt = $block['h'];
            $html .= "<div class=\"key-data box box-$colW\">";
            $html .= "<$hTag class='key-heading'>$headingTxt</$hTag>"; //Header
            $html .= "<p>$para</p>";
            $html .= "</div>"; //key-data end
        }
        $html .= "</div>";
        return $html;
    }


    function getLink($key)
    {
        $links = [];
        if (is_array($links)) {
            shuffle($links);
            if (is_array($links) && count($links) > 0) {
                $txt = ucfirst(trim($key));
                return "<a target=\"_blank\" title=\"$txt\" href=\"" . $links[0] . "\" class='rand-key-link'>" . $txt . "</a>";
            }
        }
    }

    /**
     * 
     * @param array $keys
     * @param int $blockLimit number of Block
     * @param int $pl length of Paragraph
     * @param int $hl length of Header
     * @param int $sl length of Sentence
     */
    function blockGenerator()
    {
        $keys = $this->keys;
        $arg = $this->attributes;

        $hl = $arg['hl'];
        $sl = $arg['sl'];
        $blockLimit = $arg['n'];

        $keySents = false;

        if (isset($arg['key']) && !empty($arg['key'])) {
            $keySents = $this->findSentencesContainingKeyword($keys, $arg['key']);
            $keys = array_diff($keys, $keySents);
        }

        if ($arg['link'] == "true") {
            $numberOfLink = 1; //Number of link in a paragraph ;
        } elseif ($arg['link'] == "false") {
            $numberOfLink = 0; //Number of link in a paragraph ;
        } else {
            $numberOfLink = $arg['link'];
        }
        $pl = $this->randTollerace($arg['pl']);
        $blocks = [];
        $block = ['hSet' => false, 'pSet' => false, 'linkSet' => false, 'capSet' => false];

        $sentancePhrseCount = 0;
        $bc = 0;

        $lCount = $numberOfLink;

        $numberOfCap = 2;
        $cCount = $numberOfCap;

        $keyCount = 0;
        $keyUsed = 0;
        $kePos = false;
        foreach ($keys as $k => $key) {
            $keyCount++;
            if ($keySents && is_array($kePos) && in_array($keyCount, $kePos)) {
                if (isset($keySents[$keyUsed])) {
                    $key = $keySents[$keyUsed];
                }
                $keyUsed += 1;
            }

            //$key = "($k)" . $key;
            if ($block['hSet'] !== true) {
                $block['h'][] = $key;
                if ($block['hSet'] === false) {
                    $block['hSet'] = 1;
                } else {
                    $block['hSet']++;
                }
                if ($block['hSet'] == $hl) {
                    $block['hSet'] = true;
                }
                continue;
            }
            //Headding
            //Paragraph

            if ($block['pSet'] !== true) {
                if ($block['pSet'] === false) {

                    if ($keySents) {
                        $kePos = $this->generateUniqueRandomNumbers($arg['pl'], $arg['key-limit']);
                        $keyCount = 0; //Keyword seter;
                    }

                    $block['kStart'] = $k;
                    $block['kEndCalc'] = ($block['kStart'] + $pl) - 1;
                    $block['linkSet'] = $this->pickRandKeysIndex($block['kStart'] + 1, $block['kEndCalc'], $numberOfLink);
                    $block['capSet'] = $this->pickRandKeysIndex($block['kStart'], $block['kEndCalc'], $numberOfCap);
                    $block['pSet'] = 1;
                } else {
                    $block['pSet']++;
                }
                if ($block['pSet'] == $pl) {
                    $block['kEnd'] = $k;
                    $block['pSet'] = true;
                }

                // //Add Capital String with Key(Random Before After)
                // if (in_array($k, $block['capSet']) && $cCount > 0) {
                //     $PS = rand(1, 2);
                //     $capStr = $this->get_rendCapStr();
                //     if ($PS == 1) {
                //         $key .= " $capStr";
                //     } else {
                //         $key = "$capStr " . $key;
                //     }
                //     $cCount--;
                // }

                // //Add Link in string Key
                // if (in_array($k, $block['linkSet']) && $lCount > 0) {
                //     $link = $this->getLink($key);
                //     $key = $link; //Link
                //     $lCount--;
                // }

                $block['p'][] = $key;
                //Line Break
                $sentancePhrseCount++;
                if ($sentancePhrseCount == $sl) {
                    $block['p'][] = "<br>";
                    $sentancePhrseCount = 0;
                }
            }

            if ($block['pSet'] === true) {
                //Each Block Info
                $info = [
                    'p' => [
                        'index' => $block['kStart'] . ' - ' . $block['kEndCalc'],
                        'total' => $pl,
                    ],
                    'linkIndex' => $block['linkSet'],
                    'capIndex' => $block['capSet']
                ];
                //echo "<pre>";
                //var_dump($info);
                //echo "</pre>";

                $block['h'] = implode(" - ", $block['h']);
                $p = implode(", ", $block['p']) . ".";
                $pfilter = str_replace(
                    ["<br>,", ", <br>.", ", , ,", ", ,", "<br> ,", ",<br>.", ".  ."],
                    ["<br>", '.', ",", ",", "<br>", ".", "."],
                    $p
                );

                $pfilter = preg_replace('/,+/', ', ', $pfilter);
                // Remove multiple commas and spaces between them
                $pfilter = preg_replace('/,+(?=\s*,)/', ',', $pfilter);
                // Remove comma if followed by a dot
                $pfilter = preg_replace('/,(?=\s*\.)|,(?=\s*<br>)/', '.', $pfilter);
                // Remove comma if preceded by a <br>
                $pfilter = preg_replace('/<br>\s*,/', '<br>', $pfilter);
                // Remove consecutive dots even if surrounded by <br>, comma, space, or multiple spaces
                $pfilter = preg_replace('/(?:<br>\s*|,\s*|\s+|\s*\])\K\.{2,}(?=\s*(?:<br>|,|\s|$|\]))/', '.', $pfilter);

                $block['p'] = $pfilter;
                $bc++;
                $blocks[$bc] = $block;
                //Reset Block---------------
                $lCount = $numberOfLink;
                $cCount = $numberOfCap;
                $block = ['hSet' => false, 'pSet' => false, 'linkSet' => false, 'capSet' => false];
                $pl = $this->randTollerace($pl);
            }
            if ($bc >= $blockLimit) {
                break;
            }
            //Add Block into Blocks
        }
        return $blocks;
    }

    function pickRandKeysIndex($min = 0, $max = 25, $number = 3)
    {
        if ($number < 1) {
            return [];
        }
        if ($max == $min) {
            return [$min];
        }

        $arr = [];
        for ($i = $min; $i <= $max; $i++) {
            $arr[] = $i;
        }
        //var_dump($arr);
        if ($number >= count($arr)) {
            return $arr;
        }
        shuffle($arr);
        $arr = array_chunk($arr, $number);
        $itms = $arr[0];

        if (is_array($itms)) {
            return $itms;
        } else {
            return [];
        }
    }

    function findSentencesContainingKeyword($sentences, $keyword)
    {
        $matchingSentences = [];

        foreach ($sentences as $sentence) {
            if (stripos($sentence, $keyword) !== false) {
                $matchingSentences[] = $sentence;
            }
        }

        return $matchingSentences;
    }

    /**
     * +/- Tollerace
     * @param int $val number
     * @param int $p Percentage 
     *      */
    function randTollerace($val, $p = 10)
    {
        $pVal = ceil($val / $p);
        return rand(($val - $pVal), ($val + $pVal));
    }

    function generateUniqueRandomNumbers($number, $getCount)
    {
        $randomNumbers = [];

        while (count($randomNumbers) < $getCount) {
            $randomNumber = rand(1, $number);

            if (!in_array($randomNumber, $randomNumbers)) {
                $randomNumbers[] = $randomNumber;
            }
        }

        sort($randomNumbers);

        return $randomNumbers;
    }
}
