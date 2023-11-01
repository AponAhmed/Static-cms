<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;

class ImagebrowseController extends Controller
{
    function __construct($global)
    {
        $this->global = $global;
        parent::__construct();
        $this->router();
    }

    private function router()
    {
        switch ($this->childSegment) {
            default:
                # code...
                $this->BuildData();
        }
    }

    function BuildData()
    {
        echo "";
    }
}
