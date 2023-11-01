<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\manage\Model\Page;
use Aponahmed\Cmsstatic\ShortcodeParser;
use Aponahmed\Cmsstatic\Utilities\CsvFileManager;

/**
 * Parpose of this class is add internal local links in [link] shortcode 
 */
class LinkShortcode  implements Shortcode
{
    private Page $page;
    private Page $currentPage;
    private CsvFileManager $csv;

    private static $enable = true;
    public array $attributes;
    public string $innerHtml;

    public function __construct($attributes, public ShortcodeParser $app)
    {
        $default = [
            'multiple' => "yes",
            'slug' => 'all',
        ];
        $this->attributes = array_merge($default, $attributes);
        $this->currentPage = page_object();

        if ($this->attributes['slug']) {
            if ($this->attributes['slug'] == 'all') {
                $this->page = Page::random($this->app, [$this->currentPage->slug]);
            } else {
                $this->page = new Page($this->attributes['slug']);
            }
        } else {
            $this->page = $this->currentPage;
        }
        //Csv file
        $this->csv = new CsvFileManager($this->app::urlSlashFix($this->app::$contentDir . '/' . 'csvs/'));
    }

    /**
     * Get Page Link Random with recurtional 
     */
    function pageLink($page, $ex = [])
    {
        if (!$this->attributes['multiple'] != 'yes') {
            $ex[] = $page->slug;
        }
        $ex = array_unique($ex);
        if ($page->multiple_page && $this->attributes['multiple'] == 'yes') {
            $urlStructure = $page->url_structure;
            $file = $page->multiple_data_file;

            $rowData = $this->csv->getRandomRowData($file . ".csv");
            if (is_array($rowData)) {
                foreach ($rowData as $k => $val) {
                    $placeholder = '{col' . ($k + 1) . '}';
                    $urlStructure = str_replace($placeholder, $this->app->slugify($val), $urlStructure);
                }
            }
            return $urlStructure;
        } else {
            $otherPage = Page::random($this->app, $ex);

            //Get Random page Url
            if ($this->attributes['multiple'] == 'yes') {
                return $this->pageLink($otherPage, $ex);
            } else {
                return $otherPage->getLink();
            }
        }
    }

    function filter()
    {

        $url = $this->app::$siteurl;            //Home Page URL

        if (!self::$enable) {
            return $url;
        }

        $randUrl = $this->pageLink($this->page, [$this->page->slug]);
        if (!empty($randUrl)) {
            $url = $randUrl;
        }

        return $url;
    }
}
