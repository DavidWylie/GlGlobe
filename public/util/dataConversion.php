<?php
set_time_limit(0);
require_once('../src/Db.php');

$data = file_get_contents('../json/all_month.geojson');
$jsonData = json_decode($data);

$db = new Db('earthquakes.db');
$db->createTables();

foreach($jsonData as $record) {
      $recordData = array(
        'text' => $record->properties->place,
        'time' => $record->properties->time,
        'lat'  => $record->geometry->coordinates[0],
        'lon' => $record->geometry->coordinates[1],
        'twit_id' => 'na'
    );

    $db->saveTweet($recordData);
}

$db->close();