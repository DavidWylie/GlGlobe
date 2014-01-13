<?php
require_once('../init_autoloader.php');

$config = array(
    'access_token' => array(
        'token' => '36223830-Ka2R6vk4w3W3uWKN5EJefVhWLhaCIOf58m7EJ5saH',
        'secret' => 'm7mmmtglNISQXlA9UcyXYd54bz05Y5HQHWRrj0HuVaM',
    ),
    'oauth_options' => array(
        'consumerKey' => 'EYWBnpqHKDOKYp7qOgX7cA',
        'consumerSecret' => '3ibcwX89JYlwfSMywG3HbjGsC08UEhq7CjS8QzTw',
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
    $colour = $_REQUEST['search'];
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
        $parsedData[] = [$lat, $lon, $colour];
    }
}
echo json_encode($parsedData);



