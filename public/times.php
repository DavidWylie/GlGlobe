<?php
require_once('../src/Db.php');

if (is_string($_REQUEST['search'])) {
    $searchDb = $_REQUEST['search'];
    $searchDb = str_replace(' ','_',$searchDb);
    $searchDb = strtolower($searchDb);
} else {
    $searchDb = 'tweets';
}

$db = new Db($searchDb);
$timeRange = $db->getTimeRange();

echo json_encode($timeRange);



