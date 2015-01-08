#!/usr/bin/php
<?php

class Migrate {

    public function gitDiff(){
       $new_files = shell_exec('git diff HEAD^ HEAD --name-only includes/'); 
    }

    public function init(){
        return 'is alive!';
    }
}
?>
