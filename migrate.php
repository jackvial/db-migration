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

    public function init(){
        return 'is alive!';
    }
}
?>
