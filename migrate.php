#!/usr/bin/php
<?php

class Migrate {

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
            $time_stamped[date("H:i:s d/m/y", filemtime($file_name))] = $file_name;
        }
        return $time_stamped;
    }

    public function sortBykey($assocArray)
    {
        $sortedArray = ksort($assocArray);
        return $sortedArray;
    }
}
?>
