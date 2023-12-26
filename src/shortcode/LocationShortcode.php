<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\ShortcodeParser;
use Aponahmed\Cmsstatic\Utilities\CsvFileManager;


class LocationShortcode implements Shortcode
{
    public $attributes;
    private CsvFileManager $csv;

    public function __construct($attributes, public ShortcodeParser $app)
    {
        $this->csv = new CsvFileManager($this->app::urlSlashFix($this->app::$contentDir . '/' . 'csvs/'));
        $default = [];
        $this->attributes = array_merge($default, $attributes);
    }

    function filter()
    {
        $html = "<div class=\"what-location\">";
        $html .= "Select your location : <select>";
        $rowData = $this->csv->readCsv("city-usa.csv");
        if (is_array($rowData)) {
            foreach ($rowData as $val) {
                $str = $val[0] . ", " . $val[1];
                $html .= "<option>$str</option>";
            }
        }
        $html .= "</select></div>";
        return $html;
    }
}
