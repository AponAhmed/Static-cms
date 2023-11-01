<?php

namespace Aponahmed\Cmsstatic\Utilities;

class CsvFileManager
{
    private $dir;

    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    /**
     * Get a list of all CSV files in the directory.
     *
     * @return array
     */
    public function listCsvFiles()
    {
        $ignored = array('.', '..', '.svn', '.htaccess', 'index.html');

        $files = array();
        foreach (scandir($this->dir) as $file) {
            $filePath = pathinfo($file);
            $fileName = $filePath['filename'];
            if (in_array($file, $ignored))
                continue;
            $files[$fileName] = filemtime($this->dir . '/' . $file);
        }

        arsort($files);
        return  array_keys($files);
    }


    /**
     * Read data from a CSV file and return it as an array.
     *
     * @param string $filename
     * @return array|false
     */
    public function readCsv($filename, $string = false)
    {
        $filePath = $this->dir . '/' . $filename;
        if (file_exists($filePath)) {
            if ($string) {
                return file_get_contents($filePath);
            } else {
                $csvData = file($filePath, FILE_IGNORE_NEW_LINES);

                if ($csvData !== false) {
                    return array_map('str_getcsv', $csvData);
                }
            }
        }
        return false;
    }

    /**
     * Retrieves a random row from a CSV file.
     *
     * @param string $csvFilePath The path to the CSV file.
     * @return mixed An array representing the random row data if found, or an error message if the file is not found.
     */
    function getRandomRowData($filename)
    {
        $filePath = $this->dir . '/' . $filename;
        // Check if the file exists
        if (file_exists($filePath)) {
            // Read all rows from the CSV file into an array
            $csvData = array_map('str_getcsv', file($filePath));
            // Get the total number of rows
            $totalRows = count($csvData);
            // Generate a random index between 0 and $totalRows - 1
            $randomIndex = rand(0, $totalRows - 1);
            // Retrieve the random row from the array
            $randomRow = $csvData[$randomIndex];
            // Return the random row data
            return $randomRow;
        } else {
            return "CSV file not found.";
        }
    }


    /**
     * Update all CSV files by removing rows with the same column value.
     */
    public function removeDuplicateRows()
    {
        $csvFiles = $this->listCsvFiles();
        foreach ($csvFiles as $filename) {
            // Read the CSV file
            $csvData = $this->readCsv($filename . ".csv");

            if ($csvData !== false) {
                // Find and remove duplicate rows based on a specific column (e.g., the first column)
                $uniqueRows = [];
                foreach ($csvData as $row) {
                    $key = $row[0]; // Change this to the column you want to check for duplicates
                    if (!isset($uniqueRows[$key])) {
                        $uniqueRows[$key] = $row;
                    }
                }

                // Write the unique rows back to the CSV file
                $this->writeCsv($filename . ".csv", $uniqueRows, false);
            }
        }
    }


    /**
     * Write data to a CSV file.
     *
     * @param string $filename
     * @param mixed $data
     * @param bool $content
     * @return bool
     */
    public function writeCsv($filename, $data, $content = false)
    {
        $filePath = $this->dir . '/' . $filename;
        if ($content) {
            return file_put_contents(urlSlashFix($filePath), stripslashes($data));
        } else {
            // Convert each row to a CSV string
            $csvStrings = array_map(function ($row) {
                return implode(',', $row);
            }, $data);

            // Join the CSV strings with newline characters
            $csvContent = implode(PHP_EOL, $csvStrings);

            // Write the updated CSV data back to the file
            return file_put_contents(urlSlashFix($filePath), $csvContent) !== false;
        }
    }



    /**
     * Delete a CSV file.
     *
     * @param string $filename
     * @return bool
     */
    public function deleteCsv($filename)
    {
        $filePath = $this->dir . '/' . $filename;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }
}
