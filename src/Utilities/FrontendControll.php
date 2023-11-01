<?php

namespace Aponahmed\Cmsstatic\Utilities;

use Aponahmed\Cmsstatic\App;

class FrontendControll
{
    public function __construct(private App $App)
    {
        if ($this->App->auth->is_loggedin()) {
            $this->ui();
        }
    }

    function ui()
    {
        echo '<div style="position:fixed;left:0;top:50%;transform:translateY(-50%);display: flex;background: #041224;border-radius: 0 3px 3px 0;">';
        if (isset($this->App->global['page-instanse'])) {
            $page = $this->App->global['page-instanse'];
            $editUrl = $this->App::urlSlashFix($this->App::$siteurl . "/" . $this->App::$adminDir . "/pages/edit/" . $page->slug) . "/";
            echo '<a href="' . $editUrl . '" style="line-height: 1;padding: 5px;color: #fff;" title="Edit Page">
                    <svg style="width:20px;height:20px" viewBox="0 0 512 512">
                        <path style="stroke: #fff;" d="M384 224v184a40 40 0 01-40 40H104a40 40 0 01-40-40V168a40 40 0 0140-40h167.48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
                        <path style="fill: #fff;" d="M459.94 53.25a16.06 16.06 0 00-23.22-.56L424.35 65a8 8 0 000 11.31l11.34 11.32a8 8 0 0011.34 0l12.06-12c6.1-6.09 6.67-16.01.85-22.38zM399.34 90L218.82 270.2a9 9 0 00-2.31 3.93L208.16 299a3.91 3.91 0 004.86 4.86l24.85-8.35a9 9 0 003.93-2.31L422 112.66a9 9 0 000-12.66l-9.95-10a9 9 0 00-12.71 0z"/>
                    </svg>
                </a>
            ';
        }
        echo '</div>';
        //echo $this->App->perform->log();
    }
}
