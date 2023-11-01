<?php

namespace Aponahmed\Cmsstatic;


class Route
{
    private static $UrlExtension = "/"; // Default value, can be set to "/", ".html", or false
    public $segments = [];
    public $domain = '';
    public $query = [];
    /**
     * Full Url
     * @var string
     */
    public $url;
    /**
     * Url Without Query String
     * @var string
     */
    public $uri;
    private array $config = [];

    public static function setUrlExtension($extension)
    {
        self::$UrlExtension = $extension;
    }

    public function __construct($config, public $homeSlug)
    {
        //$this->init();
        $this->config = $config;
        $this->url = urldecode($this->getCurrentUrl());
        $this->validateUrl();
        $this->parseUrl($this->url);
    }

    private function validateUrl()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $this->domain = $this->getConfig('app', 'siteurl');
        if (strpos($requestUri, $this->domain) !== false) {
            $rqUri = str_replace($this->domain, "", $requestUri);
            $redirUri = $this->domain . "/" . $rqUri;
            $redirUri = preg_replace('/([^:])(\/{2,})/', '$1/', $redirUri);
            $this->redirect($redirUri, [], '301');
        }
    }

    private function getCurrentUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $requestUri = $_SERVER['REQUEST_URI'];

        return $protocol . '://' . $host . $requestUri;
    }

    function isImageUrl($url)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'ico']; // Add more extensions if needed

        $urlParts = parse_url($url);
        if (isset($urlParts['path'])) {
            $path = pathinfo($urlParts['path']);
            if (isset($path['extension']) && in_array(strtolower($path['extension']), $imageExtensions)) {
                return true; // URL points to an image
            }
        }

        return false; // URL does not point to an image
    }

    private function parseUrl($url)
    {
        // Remove the protocol part (http/https) if present
        $url = preg_replace('#^https?://#', '', $url);

        // Separate the query part from the URL, if any
        if (strpos($url, '?') !== false) {
            list($url, $queryString) = explode('?', $url, 2);
            parse_str($queryString, $this->query);
        }

        // Separate the domain part from the URL
        $this->uri = $this->getCurrentProtocol() . $url;
        $urlParts = explode('/', $url);

        // Remove empty segments caused by consecutive slashes
        $urlParts = array_filter($urlParts, function ($segment) {
            return !empty($segment);
        });
        $urlParts = array_map(function ($segment) {
            return urldecode($segment);
        }, $urlParts);
        unset($urlParts[0]); //Remove Domain from URL


        //var_dump($urlParts); //
        //exit;

        if (!$this->getConfig('app', 'siteurl')) {
            $protocol = $this->getCurrentProtocol();
            $this->domain = $protocol . array_shift($urlParts);
        } else {
            $this->domain = $this->getConfig('app', 'siteurl');
        }


        if (isset($urlParts[1]) && $urlParts[1] == $this->homeSlug) {
            // var_dump($this->domain);
            $this->redirect($this->domain);
        }

        // Check for URL extension setting
        if (count($urlParts) > 0 && !$this->isImageUrl($this->url)) {
            if (self::$UrlExtension === '/') {
                // Handle trailing slash
                if (preg_match('#(\.html)$#', $url)) {
                    $url = rtrim(preg_replace('#(\.html|/)$#', '', $url), '/');
                    $urlSuf = implode('/', $urlParts);
                    $urlSuf = rtrim(preg_replace('#(\.html)$#', '', $urlSuf), '/');
                    $this->redirect($this->domain . '/' . $urlSuf . "/");
                } else {
                    if (substr($url, -1) !== '/') {
                        $this->redirect($this->domain . '/' . implode('/', $urlParts) . "/");
                    }
                }
            } elseif (self::$UrlExtension === '.html') {
                // Handle .html extension
                if (!preg_match('#\.html$#', $url)) {
                    $this->redirect($this->domain . '/' . implode('/', $urlParts) . '.html');
                }
            } else {
                // Handle no extension or slash
                if (preg_match('#(\.html|/)$#', $url)) {
                    $urlSuf = implode('/', $urlParts);
                    $urlSuf = rtrim(preg_replace('#(\.html|/)$#', '', $urlSuf), '/');
                    //var_dump($urlSuf);
                    $this->redirect($this->domain . '/' . $urlSuf);
                    //$this->redirect($url);
                }
            }
        }

        // Remaining segments become the path segments
        $this->segments = array_values($urlParts);
    }

    function getCurrentProtocol()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            return 'https://';
        } else {
            return 'http://';
        }
    }

    public function getSegments()
    {
        return $this->segments;
    }

    public function getSegment($index)
    {
        if (isset($this->segments[$index])) {
            return $this->segments[$index];
        }
        return false;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function redirect($url, $message = array(), $code = 0)
    {
        if ($message != "") {
            $_SESSION['message'] = $message;
        }
        header('Location: ' . $url, true, $code);
        exit;
    }

    public function getConfig($section, $key, $defaultValue = null)
    {
        if (isset($this->config[$section][$key])) {
            return $this->config[$section][$key];
        }

        return $defaultValue;
    }
}
