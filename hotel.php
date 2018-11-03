<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once 'config/database.php';
include_once 'config/core.php';
include_once 'objects/hotel.php';

$database = new Database();
$db = $database->getConnection();

$hotel = new Hotel($db);

$hotel->id = isset($_GET['id']) ? $_GET['id'] : NULL;

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    $result = $hotel->id ? $hotel->get_hotel() : $hotel->get_hotels();
    if ($result->rowCount()) {
      if ($hotel->id) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $response = array("hotel" => $row);
      } else {
        $response["hotels"] = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          array_push($response["hotels"], $row);
        }
      }
      Response::send(200, $response);
    } else {
      Response::send(404, array("message" => "No hotel found."));
    }
    break;
  case 'PUT':
    // Update
    
    break;
  case 'POST':
    // Insert
    break;
  case 'DELETE':
    break;
}

?>