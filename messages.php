<?php
require_once('./data.php');
header('Content-type: application/json');
$person_id = getUser($_GET);
$place_id = getPlace($_GET);
$json = getData($person_id, $place_id);
print $json;
?>
