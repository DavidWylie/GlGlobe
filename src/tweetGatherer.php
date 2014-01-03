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
    die('Something is wrong with my credentials!');
}

// Search for something:
$searchResponse = $tweetGather->search->tweets('new year');
$db = new Db('../data/tweet.db');

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
        
    }
    echo "<pre>";  
    var_dump($tweet->text);
    var_dump($tweet->user->location);
    var_dump($tweet->coordinates);
    echo "</pre>";
    $db->saveTweet($data);
}

//testing comments