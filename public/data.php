<?php
require_once('../src/Db.php');
/**
 * @param string $fileName
 * @return array
 */
function getTweetData($fileName,$time=0) {
    $db = new Db($fileName);
    if($time==0) {
        $timeRange = $db->getTimeRange();
        $time = $timeRange['minTime'];
    }
    $tweets = $db->getTweets($time);
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
//test

$data = getTweetData('data/tweets.db',$offset);
$returnedData = json_encode($data);

echo $returnedData;