<?php
require_once('./data.php');

$post = var_export($_POST, true);
$get = var_export($_GET, true);
$file = fopen('./data/received_data.txt', 'w+');
fwrite($file, "POST:\n" . $post . "\n\nGET:\n" . $get);
fclose($file);

$person_id = $_POST['person_id'];
$place_id = $_POST['place_id'];
$data = getData($person_id, $place_id);
$json = json_decode($data);
$update = false;
foreach($_POST as $key => $value) {
  $message[$key] = $value;
  $update = true;
}
if ($update) {
  $json = addMessage($person_id, $place_id, $message);
  print $json;
} else {
  print '[]';
}
?>
