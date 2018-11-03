<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$home_url = "https://book-a-room.ionbatir.com/api/";

class Response {
  public static function send($code, $response) {
    http_response_code($code);
    echo json_encode($response);
  }
}

class Utils {
  public static function isset_all($object, $fields) {
    $isset_all = true;
    foreach ($fields as $field)
      if (!isset($object->{$field})) {
        $isset_all = false;
        break;
      }
    return $isset_all;
  }
}
?>