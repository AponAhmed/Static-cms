<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;

use Aponahmed\Cmsstatic\manage\Model\MediaCategory;
use Aponahmed\Cmsstatic\manage\Model\Page;
use Aponahmed\Cmsstatic\Utilities\CsvFileManager;
use Aponahmed\Cmsstatic\Utilities\SitemapGenerator;
use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SimpleXMLElement;

class SitemapController  extends Controller
{

    private CsvFileManager $csvFileManager;
    private $maxLink = 10;
    private $sitemaps;
    private $fileIndex;
    private SitemapGenerator $map;
    private string $dir;
    private $fileName;
    private $modDate;
    private array $report = [];
    private array $usedUrl = [];


    function __construct($global = false)
    {
        $this->global = $global;
        parent::__construct();
        $this->csvDirSet();

        $this->dir = $this->GetSetting('sitemap_dir', 'sitemaps'); // Child sitemap Files Directory 
        $this->fileName = $this->GetSetting('sitemap_file_name', 'sitemap'); //Index File
        $this->maxLink = $this->GetSetting('sitemap_max_link', 1000); //Index File
        $this->modDate = $this->GetSetting('sitemap_mod_date', false); //Index File

        //Route in controller
        switch ($this->childSegment) {
            case 'csv-data':
                $this->get_csvdata();
                break;
            case 'csv-upload':
                $this->get_csvUpload();
                break;
            case 'csv-update':
                $this->update_csvdata();
                break;
            case 'generate':
                $this->generateSitemap();
                break;
            default:
                $this->settings();
                break;
        }
    }


    function MediaSitemap()
    {
        $this->report['prooducts'] = ['count' => 0, 'skiped' => 0];
        //Media sitemap here
        $this->fileIndex = null;
        $mediaDir = new MediaCategory();
        $images = $mediaDir->getMediaAll(false);
        foreach ($images as $image) {
            $this->map->addLink($image->url,  $this->dateAtom($image->modified_at()), 'weekly', '0.9');
            $check = $this->isUsed($image->url);
            if (!$check) {
                $this->report['prooducts']['count'] += 1;
            } else {
                $this->report['prooducts']['skiped'] += 1;
                $this->report['prooducts']['skipedUrl'][] = $image->url;
                $this->report['prooducts']['skiped_cause'][] = 'Dupplicate in the row';
            }
            $this->maxSave('prooducts');
        }
        if ($this->map->getCount() > 0) { //rest of the links are saved
            $indx = $this->fileIndex ? "-$this->fileIndex" : "";
            $this->sitemaps[] = [
                'count' => $this->map->getCount(),
                'url' => $this->map->generateSitemap($this->dir, "prooducts$indx.xml")
            ];
            $this->fileIndex++;
        }
    }

    function dateAtom($date)
    {
        if ($this->modDate) {
            $date = $this->modDate;
        }
        return  date(DATE_ATOM, strtotime($date));
    }

    public function removeDirectory($directoryPath)
    {
        if (is_dir($directoryPath)) {
            // Use recursive iterator to remove all files and subdirectories
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directoryPath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($files as $fileinfo) {
                $file = $fileinfo->getRealPath();
                if ($file !== false) {
                    if ($fileinfo->isDir()) {
                        rmdir($file);
                    } elseif ($fileinfo->isFile()) {
                        unlink($file);
                    }
                }
            }
            // Finally, remove the main directory
            rmdir($directoryPath);
            return true; // Directory removed successfully
        } else {
            return false; // Directory doesn't exist
        }
    }

    function deleteSitemaps()
    {
        if ($this->dir != "") {
            return $this->removeDirectory(self::urlSlashFix(ROOT_DIR . "/" . $this->dir));
        } else {
            return false;
        }
    }

    function isUsed($url)
    {
        if (!in_array($url, $this->usedUrl)) {
            $this->usedUrl[] = $url;
            return false;
        }
        return true;
    }

    function generateSitemap()
    {
        //Find All Pages
        $this->deleteSitemaps();
        try {
            $pages = Page::all($this);
            $csv = new CsvFileManager(self::urlSlashFix(self::$contentDir . "/csvs/"));
            //$csv->removeDuplicateRows(); //One Time execuatable///There some error maybe
            //loop 
            $this->map = new SitemapGenerator(ROOT_DIR, self::$siteurl);

            //Home Page
            $homeSlug = $this->GetSetting('home_page');
            $homePage = new Page($homeSlug);
            $r = $this->map->addLink($homePage->getLink(), $this->dateAtom($homePage->modified_at()), 'weekly', '1.0');

            $this->report[$homePage->slug] = ['count' => 1, 'skiped' => 0];


            $priority = '0.9';
            foreach ($pages as $page) {
                if ($page->slug == $homeSlug) {
                    continue;
                }

                $this->report[$page->slug] = ['count' => 0, 'skiped' => 0];

                $check = $this->isUsed($page->getLink());
                if (!$check) {
                    $this->map->addLink($homePage->getLink(), $this->dateAtom($homePage->modified_at()), 'weekly', $priority);
                    $this->report[$page->slug]['count'] += 1;
                } else {
                    $this->report[$page->slug]['skiped'] += 1;
                    //$this->report[$page->slug]['skipedUrl'][] = $page->getLink();
                    //$this->report[$page->slug]['skiped_cause'][] = 'Dupplicate in the row';
                }

                //-> if multiple page enable then get data file
                if ($page->multiple_page) {

                    //-> Data file
                    $dataFile = $page->multiple_data_file;
                    $data = $csv->readCsv($dataFile . ".csv");
                    //-> Get Url Structure
                    $url = $page->url_structure;
                    //If Url Structure Not provided with domain name
                    //var_dump($url, self::$siteurl, strpos($url, self::$siteurl));
                    
                    if (strpos($url, self::$siteurl) === false) {
                        $url = self::urlSlashFix(self::$siteurl . "/" . $url);
                    }

                    //Default Ignore 
                    $defaultSegmentsStr = $page->default_segments;
                    $defaultSegments = explode(',', $defaultSegmentsStr);
                    $defaultSegments = array_filter($defaultSegments, 'trim');
                    $defaultUrl = $url;
                    foreach ($defaultSegments as $k => $segment) {
                        $placeholder = '{col' . ($k + 1) . '}';
                        $defaultUrl = str_replace($placeholder, $this->slugify($segment), $defaultUrl);
                    }
                    //End Default Ignore

                    //-> loop multiplexing 
                    $n = 0;
                    foreach ($data as $row) {
                        $n++;
                        if ($n == 1)
                            continue;
                        // Replace {col1}, {col2}, etc. with corresponding values from data
                        $url_with_data = $url;
                        foreach ($row as $i => $value) {
                            $value = str_replace("&nbsp;", "", $value);
                            $placeholder = '{col' . ($i + 1) . '}';
                            // Convert value to lowercase and create a slug
                            $slug_value = self::slugify($value);
                            $url_with_data = str_replace($placeholder, $slug_value, $url_with_data);
                            $url_with_data = self::urlSlashFix($url_with_data . "/");
                        }
                        if ($defaultUrl == $url_with_data) {
                            continue;
                        }
                        $check = $this->isUsed($url_with_data);
                        if (!$check) {
                            $r = $this->map->addLink($url_with_data,  $this->dateAtom($page->modified_at()), 'weekly', $priority);
                            $this->report[$page->slug]['count'] += 1;
                        } else {
                            $this->report[$page->slug]['skiped'] += 1;
                            //$this->report[$page->slug]['skipedUrl'][] = $url_with_data;
                            //$this->report[$page->slug]['skiped_cause'][] = 'Dupplicate in the row';
                        }

                        $this->maxSave('pages'); //Maximum number of links to be saved single file
                    }
                }
                $this->maxSave('pages'); //Maximum number of links to be saved single file
            }

            if ($this->map->getCount() > 0) { //rest of the links are saved
                $indx = $this->fileIndex ? "-$this->fileIndex" : "";
                $this->sitemaps[] = [
                    'count' => $this->map->getCount(),
                    'url' => $this->map->generateSitemap($this->dir, "pages$indx.xml")
                ];
                $this->fileIndex++;
            }


            $this->MediaSitemap();
            //index Sitemap
            $files = array();
            foreach ($this->sitemaps as $inf) {
                $files[] = $inf['url'];
            }

            $report['total'] = count($this->usedUrl);
            $report['pages'] = $this->report;

            $file = $this->map->createIndexSitemap($files, $this->fileName . ".xml", $this->dateAtom(date('m/d/Y')), $this);
            echo json_encode(['error' => false, 'message' => 'Generated successfully', 'file' => $file, 'reports' => $report]);
        } catch (Exception $e) {
            echo json_encode(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    function maxSave($name)
    {
        if ($this->map->getCount() >= $this->maxLink) {
            $indx = $this->fileIndex ? "-$this->fileIndex" : "";
            $this->sitemaps[] = [
                'count' => $this->map->getCount(),
                'url' => $this->map->generateSitemap($this->dir, $name . $indx . ".xml")
            ];
            $this->fileIndex++;
        }
    }

    function get_csvUpload()
    {
        $dir = self::urlSlashFix(self::$contentDir . "/csvs/");
        if (isset($_FILES['file']) && !empty($_FILES['file'])) {
            $file = $_FILES['file'];
            $allowTypes = array('csv');
            $fileName = $file['name'];
            $targetFilePath = $dir . $fileName;
            $fileInfo = pathinfo($targetFilePath);
            if (in_array($fileInfo['extension'], $allowTypes)) {
                if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                    echo json_encode(['error' => false, 'fname' => $fileInfo['filename']]);
                } else {
                    echo json_encode(['error' => true, 'fname' => '']);
                }
            }
        }
        exit;
    }

    function csvDirSet()
    {
        $dir = self::urlSlashFix(self::$contentDir . "/csvs/");
        $this->silence($dir, true);
        $this->csvFileManager = new CsvFileManager($dir);
    }

    function get_csvdata()
    {
        if (isset($_POST['name']) && !empty($_POST['name'])) {
            $fileName = trim($_POST['name']);
            echo $this->csvFileManager->readCsv($fileName . ".csv", true);
        } else {
            echo "Filename must be provided";
        }
        exit;
    }

    function update_csvdata()
    {
        if (isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['val']) && !empty($_POST['val'])) {
            $fileName = trim($_POST['name']);
            $res = $this->csvFileManager->writeCsv($fileName . ".csv", stripslashes($_POST['val']), true);
            if ($res) {
                echo json_encode(['errors' => false, 'message' => 'File Updated Succeeded']);
            }
        } else {
            echo json_encode(['errors' => true, 'message' => 'Filename and file data must be provided']);
        }
        exit;
    }

    function settings()
    {
        $data = [
            'csvs' => $this->csvFileManager,
            'settings' => $this
        ];

        $this->view('sitemap.settings', $data);
    }
}
