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
}
?>