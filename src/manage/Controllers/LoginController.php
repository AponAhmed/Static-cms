<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;

use Aponahmed\Cmsstatic\manage\Auth;

class LoginController extends Controller
{
    private Auth $Auth;

    function __construct($auth)
    {
        $this->Auth = $auth;
        $this->init();

        if (isset($_POST['username']) && $_POST['username'] != "" && isset($_POST['password']) && $_POST['password'] != "") {
            if ($this->Auth->checkUserAccess($_POST['username'], $_POST['password'])) {
                $this->Auth->login($_POST['username']);
                $this->route->redirect('/' . self::$adminDir);
            } else {
                $this->view('login', ['error' => 'Invalid username or password']);
            }
        } else {
            $this->view('login', ['auth' => $this->Auth]);
        }
    }
}
