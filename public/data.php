<?php

/**
 * @param string $fileName
 * @return array
 */
function getTweetData($fileName) {
    $data = file($fileName);
    $parsedData = array();
    foreach ($data as $tweet) {
        $jsonData = json_decode($tweet);
        $longitude = $jsonData->latLon->lon;
        $latitude = $jsonData->latLon->lat;
        $dateTime = new \DateTime($jsonData->date);
        $parsedData[$dateTime->getTimestamp()] = [$latitude,$longitude,0.4];
    }
    ksort($parsedData);
    return array_values($parsedData);
}

if(is_numeric($_REQUEST['offset'])) {
   $offset = $_REQUEST['offset']; 
} else {
    $offset = 0;
}

$data = getTweetData(__DIR__ . '/data/goodmorning4.json');
$returnedData = json_encode($data[$offset]);
echo $returnedData;