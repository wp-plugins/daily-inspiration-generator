<?php
// Website url to open
$daurl = 'http://pipes.yahoo.com/pipes/pipe.run?_id=5d70d840b02a3fa9300d46a1c45e115a&_render=rss';

// Get that website's content
$handle = fopen($daurl, "r");

// If there is something, read and return
if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);
        echo $buffer;
    }
    fclose($handle);
}
?>