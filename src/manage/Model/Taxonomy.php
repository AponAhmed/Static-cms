<?php

namespace Aponahmed\Cmsstatic\manage\Model;

use Aponahmed\Cmsstatic\Traits\Main;

class Taxonomy
{
    use Main;
    private static $directory; // Static property to store the directory name
    private $fileName; // Property to store the current file name
    private $data = []; // Property to store the data
    public $new = true;

    public function __construct($fileName)
    {
        $this->init();
        self::$directory = trim(self::urlSlashFix(self::$dataDir . "/taxonomy/"), "/"); // Set the static directory name
        $this->fileName = $fileName;

        if (file_exists(self::$directory . '/' . $this->fileName . '.json')) {
            $this->new = false;
        }
        self::silence(self::$directory, true);
        $this->loadData();
    }

    public static function create($data, $slug)
    {
        $taxonomy = new self($slug);
        $taxonomy->setData($data);
        return $taxonomy->save();
    }



    public function save()
    {
        $jsonString = json_encode($this->data);
        $filePath = self::$directory . '/' . $this->fileName . '.json';

        file_put_contents($filePath, $jsonString);
        return true;
    }

    public function update($newData)
    {
        $this->data = array_merge($this->data, $newData);
        return $this->save();
    }

    public function get()
    {
        return isset($this->data) ? $this->data : [];
    }

    public function getObjects($key)
    {
        return isset($this->data[$key]) ? array_filter($this->data[$key]) : [];
    }

    public function remove($key, $value)
    {
        $indx = array_search($value, $this->data[$key]);
        if ($indx !== false) {
            unset($this->data[$key][$indx]);
            $this->data[$key] = array_values($this->data[$key]);
        }
        return $this->save();
    }

    public function removeTerm($name = null)
    {
        if ($name && array_key_exists($name, $this->data) !== false) {
            unset($this->data[$name]);
            return $this->save();
        }
    }

    public function set($key, $value)
    {
        if (isset($this->data[$key]) && is_array($this->data[$key])) {
            // Merge the existing array value with the new array value and filter duplicates
            $this->data[$key] = array_merge($this->data[$key], [$value]);
            $this->data[$key] = array_unique($this->data[$key]);
        } else {
            // If the key doesn't exist or the existing value is not an array, set the new value
            $this->data[$key] = $value;
        }

        return $this->save();
    }


    private function loadData()
    {
        $filePath = self::$directory . '/' . $this->fileName . '.json';
        if (file_exists($filePath)) {
            $jsonString = file_get_contents($filePath);
            $this->data = json_decode($jsonString, true);
        }
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}
