<?php
require_once('../init_autoloader.php');
require_once('Db.php');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$config = array(
    'access_token' => array(
        'token'  => '170839498-MldUAZKuSq9qSm9oesXXG2XHEsGEKGLIOCSnKrPC',
        'secret' => 'cXDfheKi71mMWqsO2EzSNOfSk9LqpR1DMEdnjD1bYRRaE',
    ),
    'oauth_options' => array(
        'consumerKey' => 'EQpBJmAUR5R5ZgSmKDFFA',
        'consumerSecret' => 'XJHnRslgCIkPxGBc4seSo8FHQQQBgdCcPN8LUA',
    ),
    'http_client_options' => array(
        'adapter' => 'Zend\Http\Client\Adapter\Curl',
        'curloptions' => array(
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ),
    ),
);


$tweetGather = new \ZendService\Twitter\Twitter($config);

// Verify your credentials:
$response = $tweetGather->account->verifyCredentials();
if (!$response->isSuccess()) {
    var_dump($response->getContent());
    die('Something is wrong with my credentials!');
}


if (is_string($_REQUEST['search'])) {
    $searchTerm = urldecode($_REQUEST['search']);
    $searchDb = strtolower(str_replace(' ','_',$searchTerm));
} else {
    $searchDb = 'tweets';
    $searchTerm = 'New Year';
}

$db = new Db("../data/$searchDb.db");

$lastTwitId=0;
if (array_key_exists('last_twit_id', $_REQUEST) && is_string($_REQUEST['last_twit_id'])) {
 $lastTwitId = $_REQUEST['last_twit_id'];
} else {
    $db->createTables();
}


// Search for something:
$options = array(
    'geocode'=> '51.45,0.05,40000km', 
    'since_id' => $lastTwitId,
);

$searchResponse = $tweetGather->searchTweets($searchTerm,$options);


foreach ($searchResponse->statuses as $tweet) {
    $twitTime = $tweet->created_at;
       
    $data = array(
        'text' => $tweet->text,
        'time' => strtotime($twitTime),
        'lat'  => 0,
        'lon' => 0,
        'twit_id' => $tweet->id_str
    );
    
    if ($tweet->coordinates){
        $data['lat'] = $tweet->coordinates->coordinates[1];
        $data['lon'] = $tweet->coordinates->coordinates[0];
        
        if($tweet->id_str != $lastTwitId) {
            echo "<li>{$tweet->id_str}</li>";
            $db->saveTweet($data);
        }
    } 
}
