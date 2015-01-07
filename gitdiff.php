#!/usr/bin/php
<?php
    $newFiles = shell_exec('git diff HEAD^ HEAD --name-only includes/');
    var_dump($newFiles);

    $filesArray = preg_split('/[\r\n]+/', $newFiles, -1, PREG_SPLIT_NO_EMPTY);
    var_dump($filesArray);
        
        
    foreach($filesArray as $fileName){
       $fileContents = file_get_contents($fileName);
       echo $fileContents."\n";
    }
?>
