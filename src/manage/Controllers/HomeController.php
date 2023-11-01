<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;


class HomeController extends Controller
{
    function __construct($global = false)
    {
        $this->global = $global;
        parent::__construct();
        //Route in controller
        switch ($this->childSegment) {
            case 'add':
                # code...
                $this->view('page.new');
                break;
            default:
                # code...
                $this->view('home');
        }
    }
}
