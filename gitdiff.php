#!/usr/bin/php
<?php
    $newFiles = shell_exec('git diff HEAD^ HEAD --name-only includes/');
    echo $newFiles;
?>
