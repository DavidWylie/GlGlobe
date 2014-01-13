<?php
require_once('../init_autoloader.php');

$config = array(
    'access_token' => array(
        'token' => '36223830-2OZfBBjwiFo98lBgonopUUfzlJXM4BYnLZWJoKWBl',
        'secret' => 'jsuF3UB8sv2N8kCdpNLZEyp1FNotlznQs0QKDtWJ2vqfW',
    ),
    'oauth_options' => array(
        'consumerKey' => 'p8IJVo4xSMC4mldyelwadg',
        'consumerSecret' => 'cl6Sdtxt9Qn6WSAVjt63vA6PalvnnYsVzVwaZVdYZ8',
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

if (is_string($_REQUEST['search'])) {
    $searchTerm = urldecode($_REQUEST['search']);
} else {
    $searchTerm = 'happy';
}

if (is_string($_REQUEST['colour'])) {
    $colour = $_REQUEST['colour'];
} else {
    $colour = 0.4;
}

// Search for something:
$options = array(
    'geocode' => '51.45,0.05,40000km',
    'count' => '100',
);

$searchResponse = $tweetGather->searchTweets($searchTerm, $options);

$parsedData = array();
foreach ($searchResponse->statuses as $tweet) {
    if ($tweet->coordinates) {
        $lat = $tweet->coordinates->coordinates[1];
        $lon = $tweet->coordinates->coordinates[0];
        $parsedData[] = $lat;
        $parsedData[] = $lon; 
        $parsedData[] = $colour;
    }
}
echo json_encode($parsedData);



