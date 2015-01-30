#!/usr/bin/php
<?php

/**
  *  1. Diff includes/ to check for new scripts since last commit
  *  2. Split the file names into an array
  *  3. Filter files by status
  *  4. Get the prefix number of the new files and assign them as the keys of an assoc array
  *      with the files paths as the values
  *  5. Sort by keys
  *  6. Connect to the database
  *  7. Run each of the scripts against the database
  *  8. Log that the scripts have been succesfully run or if any errors occurred
  */

class Migrate {

    protected $dbconn;

    /** Returns a list of files that have been changed since the last commit
     *  in the format:
     *  "A    includes/my_script.sql"
     *  "D    includes/my_script_old.sql"
     *  "M    includes/my_script_old.sql"
     *  where "A" is added,  "D" is deleted and "M" is modified
     */

    public function gitDiff()
    {
        return shell_exec('git diff HEAD^ HEAD --name-status includes/');
    }

    public function getFirstCommitHashCode($file)
    {   
        $hashCode = shell_exec('git rev-list HEAD '.$file.' | tail -n 1');
        
        // Strip all white space
        return preg_replace('/\s+/', '', $hashCode);
    }

    public function getFileFirstCommitDate($file)
    {
        $firstCommitHash = $this->getFirstCommitHashCode($file);
        $unixTimeStamp = shell_exec('git show -s --format="%at" ' .$firstCommitHash);

        // Strip whitespace
        return preg_replace('/\s+/', '', $unixTimeStamp);
    }
    // Split the output of gitDiff into an array
    public function splitOnNewLine($file_names)
    {
        return preg_split('/[\n\r]+/', $file_names, -1, PREG_SPLIT_NO_EMPTY);
    }

    // Filter by the status letter "A" or "D"
    public function filterByStatus($status, $filesArray)
    {
        // "use" keyword lets you inherit from the parent scope when defining
        // anonymous functions
        $newFiles = array_filter($filesArray, function($item) use($status){
            return $item[0] == $status;
        });
        return $newFiles;
    }

    // Remove the leading status letter and whitespace
    public function stripStatus($fileNames)
    {
        $result = array_map(function($item){

            // Remove first character and trim leading whitespace
            return ltrim(substr($item, 1));
        }, $fileNames);
            
        return $result;
    }

    public function getNumberPrefix($file)
    {
        // Find and return the number prefix
        $prefixes = array();
        preg_match_all('!\d+!', $file, $prefixes);
        $prefixNum = implode(' ', $prefixes[0]); 
        return (int)$prefixNum;

    }
    
    // Assign the prefix of each file as the key of a new array
    // with the file path as the value
    public function mapFilePrefix($files_array)
    {

        //$data[$item['id']]=$item['label'];
        $prefixes_assoc = array();
        foreach($files_array as $key => $file_name) {
            $prefix = $this->getNumberPrefix($file_name);
            $prefixes_assoc[$prefix] = $file_name;
        }

        return $prefixes_assoc;
    }

    public function mapTimeStampToKey($files_array)
    {
        $timestamps_assoc = array();
        foreach($files_array as $key => $file_name){
            $timeStamp = $this->getFileFirstCommitDate($file_name);

            // Append they key to make timestamps or files commited together unique
            // Cast the $key to string to concatenate then cast the new string to an int
            // The timestamp needs to be an int to be sorted correctly
            $timeStamp = (int)$timeStamp . (string)$key;
            $timestamps_assoc[$timeStamp] = $file_name;
        }

        return $timestamps_assoc;
    }

    // Sort by key to get chronological order
    public function sortBykey($assocArray)
    {
        // Sport assoc array in place, returns boolean
        ksort($assocArray, SORT_NUMERIC);
        return $assocArray;
    }

    // Create a database connection
    public function connectToDb()
    {
        try {

            $conn = new PDO('mysql:host=localhost;dbname=migration_scripts', 'root', 'Welcome1');
    
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

        $this->dbconn = $conn;
    }

    // Get the database connection
    public function getConnection(){
        return $this->dbconn;
    }

    // Run the scripts in order and log the time each one was run at to "finished_scripts.txt"
    public function runScripts($files)
    {
        $this->connectToDb();
        $dbConn = $this->getConnection();
        foreach($files as $key => $fileName) {
            $result = $dbConn->query(file_get_contents($fileName));
            print_r($result);

            $date = new DateTime();
            $date = (string)$date->format('Y-m-d H:i:s');
            file_put_contents("migration_log.txt", $date ."    ". $fileName."\n", FILE_APPEND);      
        }
    }

    public function init()
    {
        $fileNames = $this->gitDiff();
        $fileNamesArray = $this->splitOnNewLine($fileNames);
        $newFiles = $this->filterByStatus('A', $fileNamesArray);
        $statusTrimmed = $this->stripStatus($newFiles);
        $prefixedAssoc = $this->sortByKey($this->mapTimeStampToKey($statusTrimmed));
        $this->runScripts($prefixedAssoc);
    }
}

$migrate = new Migrate();
$migrate->init();
?>
