<?php

function getConfig() {
  $json = file_get_contents('./config.json');
  return $json;
}

function getFilename($person_id, $place_id) {
  return './data/' . $person_id . '-' . $place_id . '.json';
}

function getUser($obj) {
  if (isset($obj['person_id'])) {
    return $obj['person_id'];
  }
  return 'demo';
}

function getPlace($obj) {
  if (isset($obj['place_id'])) {
    return $obj['place_id'];
  }
  return '49ce832ef964a5204f5a1fe3';
}

function getFile($person_id, $place_id, $open='a+') {
  $data_file = getFilename($person_id, $place_id);
  $file = fopen($data_file, $open);
  fclose($file);
  return $data_file;
}

function readData($person_id, $place_id) {
  $filename = getFile($person_id, $place_id);
  $data = file_get_contents($filename);
  if ($data == '') { $data = '[]'; }
  return $data;
}

function getData($person_id, $place_id) {
  $data = readData($person_id, $place_id);
  return $data;
}

function addMessage($person_id, $place_id, $message) {
  $filename = getFilename($person_id, $place_id);
  $data = json_decode(readData($person_id, $place_id));
  array_push($data, $message);
  $file = fopen($filename, 'w+');
  $json = json_encode($data);
  fwrite($file, $json);
  fclose($file);
  return $json;
}

?>
