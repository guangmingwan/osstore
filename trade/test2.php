<?php
$file = "../test.log";
$f = fopen($file,"w+");
fwrite($f,"test\r\n");
fclose($f);
