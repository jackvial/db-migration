#!/usr/bin/php -q
<?php
$systems = file('system_list.txt');
foreach($systems as $system)
{
    $port = 80;
    $fp = fsockopen($system, $port, $errno, $errstr, 10);

    if(!$fp){
        echo "Post $port not available on $system";
    } else {
        echo "Port $port is open on $system";
    }
    fclose($fp);
}
?>
