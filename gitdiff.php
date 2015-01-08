#!/usr/bin/php
<?php
    $newFiles = shell_exec('git diff HEAD^ HEAD --name-only includes/');
    $filesArray = preg_split('/[\r\n]+/', $newFiles, -1, PREG_SPLIT_NO_EMPTY);
    $timeStampFileName = array();
    foreach($filesArray as $key => $fileName){
       $sqlScripts[$key] = file_get_contents($fileName);
       $timeStampFileName[date("H:i:s d/m/y", filemtime($fileName))] = $fileName;
       //echo $fileContents."\n";
    }
    
    // Sort chronologically
    //$timeStampFileName = ksort($timeStampFileName); 
    print_r($timeStampFileName);
    print_r($sqlScripts);

    try {
        $dbh = new PDO('mysql:host=localhost;dbname=migration_scripts', 'root', 'Welcome1');
        foreach($sqlScripts as $script) {
            $result = $dbh->query($script);
            foreach($result as $row){
                print_r($row);
            }
            //print_r($result);
        }
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
?>
