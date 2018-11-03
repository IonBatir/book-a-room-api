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
?>