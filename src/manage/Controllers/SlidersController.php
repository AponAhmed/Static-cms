<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;

use Aponahmed\Cmsstatic\manage\Model\Slider;
use Aponahmed\Cmsstatic\Theme;
use Aponahmed\Cmsstatic\Utilities\BuiltIn;
use Exception;

class SlidersController extends Controller
{
    function __construct($global = false)
    {
        $this->global = $global;
        parent::__construct();
        BuiltIn::metabox();
        //Route in controller
        switch ($this->childSegment) {
            case 'add':
                # code...
                $this->view('slider.new');
                break;
            case 'edit':
                # code...
                $sliderName = $this->route->getSegment(3);
                try {
                    //code...
                    $slider = new Slider($sliderName);
                    $this->view('slider.edit', ['slider' => $slider]);
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
                $name = $this->route->getSegment(3);
                try {
                    //code...
                    $page = new Slider($name);
                    if ($page->delete()) {
                        $this->redirect('/sliders/', ['text' => 'Slider Deleted Successfully', 'type' => 'success']);
                    } else {
                        $this->redirect('/sliders/', ['text' => 'Slider could not be deleted', 'type' => 'error']);
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

    function store()
    {
        $data = $_POST['data'];
        $pageSlug = isset($_POST['existing-slug']) ? $_POST['existing-slug'] : false;
        $page = new Slider($pageSlug);
        if ($page->update($data)) {
            $act = $pageSlug ? 'Updated' : 'Saved';
            $this->redirect('/sliders/edit/' . $page->slug . "/", ['text' => 'Slider ' . $act . ' Successfully', 'type' => 'success']);
        } else {
            $_SESSION['message'] = ['text' => 'Slider could not be Updated', 'type' => 'error'];
        }
    }

    function listView()
    {
        $sliderDir = self::urlSlashFix(self::$dataDir . "/sliders/");
        $sliders = Slider::getAll($sliderDir);
        //echo "<pre>";
        //var_dump($sliders);
        //exit;
        $this->view('slider.list', ['sliders' => $sliders]);
    }
}
