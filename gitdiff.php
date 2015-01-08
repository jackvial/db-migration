#!/usr/bin/php
<?php
    $newFiles = shell_exec('git diff HEAD^ HEAD --name-only includes/');
    $filesArray = preg_split('/[\r\n]+/', $newFiles, -1, PREG_SPLIT_NO_EMPTY);
    $timeStampFileName = array();
    foreach($filesArray as $key => $fileName){
       $timeStampFileName[date("H:i:s d/m/y", filemtime($fileName))] = $fileName;
       //echo $fileContents."\n";
    }
    
    $finScripts = 'finished_scripts.txt';
    $finContents = file_get_contents($finScripts);
    $finFileArray = explode(',', $finContents); 
    print_r($finFileArray);
    // Sort chronologically

    try {
        $dbh = new PDO('mysql:host=localhost;dbname=migration_scripts', 'root', 'Welcome1');
        foreach($timeStampFileName as $key => $fileName) {
            $result = $dbh->query(file_get_contents($fileName));
            file_put_contents('finished_scripts.txt', $fileName.',', FILE_APPEND);
            
        }
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
?>
