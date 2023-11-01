<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;

use Aponahmed\Cmsstatic\manage\Auth;

class LogoutController extends Controller
{
    private Auth $Auth;

    function __construct($auth)
    {
        $this->Auth = $auth;
        $this->init();

        $this->Auth->logout();
        $this->route->redirect('/' . self::$adminDir . '/login');
    }
}
