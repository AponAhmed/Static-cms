<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;

use Aponahmed\Cmsstatic\manage\Model\Menu;
use Aponahmed\Cmsstatic\manage\Model\Settings;

class FooterController extends Controller
{
    public static $blocks = [
        [
            'id' => 'footer_about_us',
            'title' => "About Us",
            'type' => 'text',
            'col' => 4,
        ],
        [
            'id' => 'footer_products_links',
            'title' => "Products",
            'type' => 'menus',
            'col' => 4,
        ],
        [
            'id' => 'footer_contact',
            'title' => "Contact Us",
            'type' => 'text',
            'col' => 4,
        ]
    ];

    function __construct($global = [])
    {
        $this->global = $global;
        parent::__construct();

        $blocksData = $this->GetSetting('footer_blocks');

        //Route in controller
        switch ($this->childSegment) {
            case 'store':
                # code...
                $this->storeData();
                break;
            default:
                # code...
                $this->view('footer-manager.form', [
                    'c' => $this,
                    'menu' => new Menu(false),
                    'data' => $blocksData
                ]);
        }
    }

    function storeData()
    {
        $blockData = $_POST['blocks'];
        $settings = new Settings();
        if ($settings->update(['footer_blocks' => $blockData])) {
            $this->redirect('/footer/', ['text' => 'Footer Data Updated Successfully', 'type' => 'success']);
        } else {
            $_SESSION['message'] = ['text' => 'Footer Data could not be Updated', 'type' => 'error'];
        }
    }
}
