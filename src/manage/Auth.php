<?php

namespace Aponahmed\Cmsstatic\manage;

use Aponahmed\Cmsstatic\Traits\Main;

class Auth
{
    use Main;
    private $accessData = [];
    private $accessDataPath;
    private $sessionPrefix = 'static_app';


    public function __construct()
    {
        $this->init();
        $this->accessDataPath = self::$dataDir . "/auth.env";

        if (!file_exists($this->accessDataPath)) {
            file_put_contents($this->accessDataPath, '[
                {
                  "userName": "admin",
                  "password": "1qazxsw2", // Replace "hashed_password" with the actual password hash
                  "name": "John Doe"
                }
            ]');
        } else {
            $this->accessData = json_decode(file_get_contents($this->accessDataPath), true);
        }
    }
    public function checkUserAccess($userName, $password)
    {
        foreach ($this->accessData as $user) {
            if ($user["userName"] === $userName && $user["password"] === $this->hashPassword($password)) {
                return true;
            }
        }

        return false;
    }

    private function hashPassword($password)
    {
        // Implement your password hashing logic here (e.g., using bcrypt)
        // For demonstration purposes, let's assume the password is stored as plain text in the JSON data, so no actual hashing is performed.
        return $password;
    }

    // Method to log in the user and set the session data
    public function login($userData)
    {
        // $userData is an array containing user-specific data
        $this->writeSession('login', $userData);
    }

    // Method to check if the user is logged in or not
    public function is_loggedin()
    {
        return $this->getSession('login');
    }

    public function logout()
    {
        $this->removeSession('login');
    }

    private function getSession($attr)
    {
        if (isset($_SESSION[$this->sessionPrefix][$attr])) {
            return $_SESSION[$this->sessionPrefix][$attr];
        } else {
            return false;
        }
    }

    private function writeSession($attr, $val)
    {
        $_SESSION[$this->sessionPrefix][$attr] = $val;
    }

    private function removeSession($attr)
    {
        unset($_SESSION[$this->sessionPrefix][$attr]);
    }
}
