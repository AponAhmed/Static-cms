<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;

use Aponahmed\Cmsstatic\manage\Model\Menu;
use Aponahmed\Cmsstatic\manage\Model\Page;
use Exception;

class MenuController extends Controller
{
    function __construct($global = false)
    {
        $this->global = $global;
        parent::__construct();
        $this->setRoute();
    }


    function pageData()
    {
        $allPages = Page::all($this);
        return $allPages;
    }

    function setRoute()
    {
        switch ($this->childSegment) {
            case 'add':
                # code...
                $menu = new Menu('');
                $this->view('menu.editor', [
                    'type' => 'new',
                    'data' => $menu,
                    'pages' => $this->pageData()
                ]);
                break;
            case 'edit':
                $pageName = $this->route->getSegment(3);
                try {
                    //code...
                    $menu = new Menu($pageName);
                    $this->view('menu.editor', [
                        'type' => 'edit',
                        'data' => $menu,
                        'pages' => $this->pageData()
                    ]);
                } catch (Exception $e) {
                    //throw $th;
                    echo $e->getMessage();
                }
                break;
            case 'update':
                $this->update();
                break;
            case 'delete':
                # code...
                $pageName = $this->route->getSegment(3);
                try {
                    //code...
                    $page = new Menu($pageName);
                    if ($page->delete()) {
                        $this->redirect('/menu/', ['text' => 'Menu Deleted Successfully', 'type' => 'success']);
                    } else {
                        $this->redirect('/menu/', ['text' => 'Menu could not be deleted', 'type' => 'error']);
                    }
                } catch (Exception $e) {
                    //throw $th;
                    echo $e->getMessage();
                }
                break;
            default:
                $this->listController();
                break;
        }
    }

    function update()
    {
        // Read the raw input data
        $inputData = file_get_contents('php://input');

        // Decode the JSON data
        $postData = json_decode($inputData, true);

        // Check if JSON decoding was successful
        if ($postData === null) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
            exit;
        }

        $name = $postData['menu-name'];
        $data = $postData['data'];
        $fileOld = $postData['old-file'];
        $actionType = $postData['actionType'];

        if ($actionType == 'new') {
            $menu = new Menu(false);
        } else {
            $menu = new Menu($name);
            //delete existing
            if ($name != $fileOld) {
                $oldmenu = new Menu($fileOld);
                $oldmenu->delete();
            }
        }
        $menu->data = json_decode($data, true);
        if ($menu->save()) {
            echo json_encode(['status' => 'success', 'message' => 'Menu Updated Successfully']);
        }
    }

    function listController()
    {
        $menusDir = self::urlSlashFix(self::$dataDir . "/menus/");
        $menus = Menu::getMenus($menusDir);
        $this->view('menu.list', ['menus' => $menus]);
    }
}
