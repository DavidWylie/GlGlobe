<?php

$dataList = glob('..\data\*.db');
$files = array();
foreach( $dataList as $filePath) {
   $file = basename($filePath,'.db');
   $file = str_replace('_',' ',$file);
   $files[] = ucwords($file);
}
$data = json_encode($files);
echo $data;

