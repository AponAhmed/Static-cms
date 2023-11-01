<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;

use Aponahmed\Cmsstatic\manage\Model\StaticPage;
use Aponahmed\Cmsstatic\manage\Model\Taxonomy;
use Aponahmed\Cmsstatic\Theme;
use Aponahmed\Cmsstatic\Utilities\BuiltIn;
use Exception;

class StaticController extends Controller
{
    function __construct($global = false)
    {
        $this->global = $global;
        parent::__construct();
        BuiltIn::metabox($this);
        //Route in controller
        switch ($this->childSegment) {
            case 'add':
                # code...
                $page = new StaticPage('');
                $this->view('static.new', ['page' => $page]);
                break;
            case 'categories':
                # code...
                $taxonomy = new Taxonomy('static-category');
                $this->view('static.terms', ['terms' => $taxonomy->get()]);
                break;
            case 'edit':
                # code...
                $pageName = $this->route->getSegment(3);
                try {
                    //code...
                    $page = new StaticPage($pageName);
                    $this->view('static.edit', ['page' => $page]);
                } catch (Exception $e) {
                    //throw $th;
                    echo $e->getMessage();
                }
                break;
            case 'store':
                # code...
                $this->store();
                break;
            case 'delete':
                # code...
                $pageName = $this->route->getSegment(3);
                try {
                    //code...
                    $page = new StaticPage($pageName);
                    if ($page->delete()) {
                        $this->redirect('/static/', ['text' => 'Static Page Deleted Successfully', 'type' => 'success']);
                    } else {
                        $this->redirect('/static/', ['text' => 'Static Page could not be deleted', 'type' => 'error']);
                    }
                } catch (Exception $e) {
                    //throw $th;
                    echo $e->getMessage();
                }
                break;
            default:
                # code...
                $this->listView();
        }
    }

    function taxoManage()
    {
        if (isset($_POST['taxo'])) {
            foreach ($_POST['taxo'] as $taxo => $terms) {
                $data = $_POST['data'];
                $pageSlug = isset($_POST['existing-slug']) ? $_POST['existing-slug'] : $data['slug'];
                $taxonomy = new Taxonomy($taxo);
                foreach ($terms as $term) {
                    $taxonomy->set($term, $pageSlug);
                }
            }
        }

        if (isset($_POST['termuncheck'])) {
            foreach ($_POST['termuncheck'] as $taxo => $terms) {
                $data = $_POST['data'];
                $pageSlug = isset($_POST['existing-slug']) ? $_POST['existing-slug'] : $data['slug'];
                $taxonomy = new Taxonomy($taxo);
                foreach ($terms as $term => $slug) {
                    $taxonomy->remove($term, $slug);
                }
            }
        }
    }

    function store()
    {
        $data = $_POST['data'];
        $data['taxonomy'] = isset($_POST['taxo']) ? $_POST['taxo'] : [];
        $pageSlug = isset($_POST['existing-slug']) ? $_POST['existing-slug'] : false;
        $page = new StaticPage($pageSlug);
        if ($page->update($data)) {
            $this->taxoManage();
            $act = $pageSlug ? 'Updated' : 'Saved';
            $this->redirect('/static/edit/' . $page->slug . "/", ['text' => 'Static Page ' . $act . ' Successfully', 'type' => 'success']);
        } else {
            $_SESSION['message'] = ['text' => 'Static Page could not be Updated', 'type' => 'error'];
        }
    }

    function listView()
    {
        $pageFileDir = self::urlSlashFix(self::$dataDir . "/static/");

        self::silence($pageFileDir, true);
        // Use scandir to get the list of files in the directory
        $files = scandir($pageFileDir);
        // Exclude ".", ".." and "index.html" from the list
        $files = array_diff($files, array('.', '..', 'index.html'));

        $pageData = [];

        //Order By Last Modified time DESC
        usort($files, function ($a, $b) use ($pageFileDir) {
            $am = filemtime($pageFileDir . $a);
            $bm = filemtime($pageFileDir . $b);
            return $bm - $am;
        });

        foreach ($files as $file) {
            $jsonString = file_get_contents($pageFileDir . $file);
            $dataObject = json_decode($jsonString);

            $modifiedTimestamp = filemtime($pageFileDir . $file);
            $modifiedDate = date("Y-m-d H:i:s", $modifiedTimestamp);
            $dataObject->modified_at = $modifiedDate;
            $dataObject->time = $modifiedTimestamp;
            // Convert the JSON string to a PHP object using json_decode
            $dataObject->snippet = $this->snippet($dataObject->content);

            $fileName = str_replace('.json', '', $file);
            $pageData[$fileName] = $dataObject;
        }


        $this->view('static.list', ['pages' => $pageData]);
    }

    function snippet($text)
    {
        // Remove HTML tags
        $withoutHtmlTags = strip_tags($text);
        // Remove empty spaces
        $trimmedText = trim($withoutHtmlTags);
        // Get the first 50 characters or less
        if (strlen($trimmedText) > 50) {
            $trimmedText = substr($trimmedText, 0, 50) . "...";
        }
        return $trimmedText;
    }
}
