<?php
require_once('../src/Db.php');
/**
 * @param string $fileName
 * @return array
 */
function getTweetData($fileName,$time,$interval) {
    $db = new Db($fileName);
    if($time==0) {
        $timeRange = $db->getTimeRange();
        $time = $timeRange['minTime'];
    }
    $tweets = $db->getTweets($time,$interval);
    $parsedData= array();
    foreach ($tweets as $tweet) {
        $parsedData[] = [$tweet['lat'],$tweet['lon'],0.4];
    }
    return $parsedData;
}

if(is_numeric($_REQUEST['offset'])) {
   $offset = $_REQUEST['offset']; 
} else {
    $offset = 0;
}

if(is_numeric($_REQUEST['interval'])) {
   $interval = $_REQUEST['interval']; 
} else {
    $interval = 0;
}

if (is_string($_REQUEST['search'])) {
    $searchDb = $_REQUEST['search'];
    $searchDb = str_replace(' ','_', $searchDb);
    $searchDb = strtolower($searchDb);
} else {
    $searchDb = 'tweets';
}

$data = getTweetData("../data/$searchDb.db",$offset,$interval);
$returnedData = json_encode($data);

echo $returnedData;
