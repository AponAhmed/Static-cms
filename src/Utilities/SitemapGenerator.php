<?php

namespace Aponahmed\Cmsstatic\Utilities;

use Exception;
use SimpleXMLElement;

class SitemapGenerator
{
    private $rootDir;
    private $dir;
    private $fileName;
    public $links = [];
    private $siteUrl;

    function __construct($rootDir, $siteUrl)
    {
        $this->rootDir = $rootDir;
        $this->siteUrl = $siteUrl;
    }

    function getCount()
    {
        return count($this->links);
    }

    public function addLink($path, $lastModified = null, $changeFrequency = null, $priority = null)
    {
        // Extract 'path' values from existing links
        $existingPaths = array_column($this->links, 'path');

        // Check if the path already exists
        if (in_array($path, $existingPaths)) {
            return false; // Path already exists, so don't add it again
        }

        // If the path doesn't exist, add it to $this->links
        $link = [
            'path' => $path,
            'lastModified' => $lastModified,
            'changeFrequency' => $changeFrequency,
            'priority' => $priority,
        ];

        $this->links[] = $link;
        return true;
    }


    public function generateSitemap($dir, $fileName)
    {
        $this->dir = $dir;
        $this->fileName = $fileName;
        //var_dump($this->links);
        //$this->links = array();
        //return;
        if (!empty($this->links)) {
            return $this->saveSitemap();
        }
    }

    private function saveSitemap()
    {
        $sitemapFileName = $this->getSitemapFileName();

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        foreach ($this->links as $link) {
            $url = $xml->addChild('url');
            $url->addChild('loc', $link['path']);

            if (!empty($link['lastModified'])) {
                $url->addChild('lastmod', $link['lastModified']);
            }

            if (!empty($link['changeFrequency'])) {
                $url->addChild('changefreq', $link['changeFrequency']);
            }

            if (!empty($link['priority'])) {
                $url->addChild('priority', $link['priority']);
            }
        }

        $xml->asXML(urlSlashFix($this->rootDir . "/" . $sitemapFileName));
        $this->links = [];
        return $sitemapFileName;
    }

    private function getSitemapFileName()
    {
        if (!is_dir($this->rootDir . "/" . $this->dir)) {
            mkdir($this->rootDir . "/" . $this->dir, 0777, true);
            chmod($this->rootDir . "/" . $this->dir, 0777);
        }
        return $this->dir . '/' . $this->fileName;
    }

    public function createIndexSitemap($data, $filename = 'sitemap.xml', $date = false)
    {
        $indexXML = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>');
        foreach ($data as $sitemapFile) {
            $sitemap = $indexXML->addChild('sitemap');
            $sitemap->addChild('loc',  urlSlashFix($this->siteUrl  . '/' . $sitemapFile));
            if ($date) {
                $sitemap->addChild('lastmod', $date);
            }
        }
        try {
            $indexXML->asXML($this->rootDir . "/" . $filename);
            return urlSlashFix($this->siteUrl . "/" . $filename);
        } catch (Exception $e) {
            return false;
        }
    }
}
