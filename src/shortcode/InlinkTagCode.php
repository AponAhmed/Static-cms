<?php

namespace Aponahmed\Cmsstatic\shortcode;

use Aponahmed\Cmsstatic\AppInterface\Shortcode;
use Aponahmed\Cmsstatic\manage\Model\Page;
use Aponahmed\Cmsstatic\ShortcodeParser;
use Aponahmed\Cmsstatic\Utilities\CsvFileManager;

/**
 * Parpose of this class is add External links in [exlink] shortcode 
 */
class InlinkTagCode  implements Shortcode
{
    private Page $page;
    private $currentPage;
    private CsvFileManager $csv;

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

        if (!$this->currentPage) {
            $slugC = "";
        } else {
            $slugC = $this->currentPage->slug;
        }

        if ($this->attributes['slug']) {
            if ($this->attributes['slug'] == 'all') {
                $this->page = Page::random($this->app, [$slugC]);
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
        if (!$page) {
            return false;
        }

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
        $randUrl = $this->pageLink($this->page, [$this->page->slug]);
        if (!$randUrl) {
            return $this->innerHtml;
        }
        if (strpos($randUrl, $url) === false) {
            $randUrl = $this->app->urlSlashFix($url . "/" . $randUrl);
        }
        if (!empty($randUrl)) {
            return  "<a href=\"$randUrl\" title=\"$this->innerHtml\">$this->innerHtml</a>";
            $url = $randUrl;
        } else {
            return $this->innerHtml;
        }
    }
}
