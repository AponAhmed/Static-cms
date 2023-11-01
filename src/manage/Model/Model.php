<?php

namespace Aponahmed\Cmsstatic\manage\Model;

use Aponahmed\Cmsstatic\Traits\Main;
use Exception;

class Model
{
    use Main;
    public $data;
    public $notfound;
    public $dataFile;
    public $modelDataDir;

    function __construct()
    {
        $this->init();
    }

    public function getData($slug)
    {
        self::silence($this->modelDataDir, true);
        if ($slug) {
            $filename = self::urlSlashFix($this->modelDataDir . '/' . $slug . '.json');
            $this->dataFile = $filename;
            if (file_exists($filename)) {
                $jsonData = file_get_contents($filename);
                $this->data = json_decode($jsonData, true);
                return $this->data;
            } else {
                $this->notfound = true;
                //throw new Exception('Data "' . $slug . '" does not exist');
            }
        } else {
            $this->data = array();
        }
    }

    function modified_at()
    {
        $filename = self::urlSlashFix($this->modelDataDir . '/' . $this->slug . '.json');
        if (file_exists($filename)) {
            $lastModifiedTimestamp = filemtime($filename);
            $lastModifiedDate = date('Y-m-d H:i:s', $lastModifiedTimestamp);
            return $lastModifiedDate;
        }
        return false;
    }

    function getFileBase()
    {
        if ($this->dataFile) {
            return pathinfo($this->dataFile, PATHINFO_FILENAME);
        }
    }

    // Method to delete a page by slug
    public function delete()
    {
        $filename = self::urlSlashFix($this->modelDataDir . '/' . $this->slug . '.json');
        if ($this->taxonomy) { //Terms Texonomy Update by deleted page
            foreach ($this->taxonomy as $taxonomy => $terms) {
                $taxoObj = new Taxonomy($taxonomy);
                foreach ($terms as $term) {
                    $taxoObj->remove($term, $this->slug);
                }
            }
        }
        if (file_exists($filename)) {
            unlink($filename);
            return true;
        } else {
            return false; // Page not found
        }
    }

    // Method to update the data array and save to a JSON file
    public function update($data)
    {
        if (isset($data['slug']) && $this->slug != $data['slug']) {
            $this->delete();
        }
        // Merge the existing data with the update data
        $this->data = array_merge($this->data, $data);
        // Save the updated data to the JSON file
        return $this->save();
    }

    public function save()
    {
        $slug = $this->data['slug'];
        $filename = self::urlSlashFix($this->modelDataDir . '/' . $slug . '.json');
        $jsonData = json_encode($this->data);

        // Save the data to the JSON file
        return file_put_contents($filename, $jsonData);
    }

    // Magic method to get individual properties directly from the instance
    public function __get($property)
    {
        if (isset($this->data[$property])) {
            return $this->data[$property];
        }
        return null; // Return null for non-existent properties
    }

    // Magic method to set individual properties directly to the instance
    public function __set($property, $value)
    {
        $this->data[$property] = $value;
    }
}
