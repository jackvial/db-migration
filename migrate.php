#!/usr/bin/php
<?php

/* 
    1. Diff includes/ to check for new scripts since last commit
    2. Split the file names into an array
    3. Get the timstamps of the new files and assign them as the keys of an assoc array
        with the files paths as the values
    4. Sort by keys to get chronological order
    5. Connect to the database
    6. Run each of the scripts against the database
    7. Log that the scripts have been succesfully run or if any errors occurred
*/

class Migrate {

    protected $dbconn;

    public function gitDiff(){
        return shell_exec('git diff HEAD^ HEAD --name-only includes/');
    }

    public function splitOnNewLine($file_names)
    {
        return preg_split('/[\n\r]+/', $file_names, -1, PREG_SPLIT_NO_EMPTY);
    }
    
    public function mapFileTimeStamps($files_array)
    {
        $time_stamped = array();
        foreach($files_array as $key => $file_name){
            $timeStamp = (int) date("ymdHis", filemtime($file_name));
            $time_stamped[$timeStamp] = $file_name;
        }
        return $time_stamped;
    }

    public function sortBykey($assocArray)
    {
        // Sport assoc array in place, returns boolean
        ksort($assocArray, SORT_STRING);
        return $assocArray;
    }

    public function connectToDb()
    {
        try {
            $conn = new PDO('mysql:host=localhost;dbname=migration_scripts', 'root', 'Welcome1');
    
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

        $this->db = $conn;
    }

    public function getConnection(){
        return $this->db;
    }

    public function init()
    {
        $fileNames = $this->gitDiff();
        $fileNamesArray = $this->splitOnNewLine($fileNames);
        $timeStampedArray = $this->sortBykey($this->mapFileTimeStamps($fileNamesArray));
        return $timeStampedArray;
    }
}

$migrate = new Migrate();
print_r($migrate->init());
?>
