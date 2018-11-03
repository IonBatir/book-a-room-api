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
    if($hotel->id) {
      $result = $hotel->getHotelById();
      if ($result->rowCount()) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        Response::send(200, array("hotel" => $row));
      } else {
        Response::send(404, array("message" => "Hotel not found."));
      }
    } else {
      $result = $hotel->getHotels();
      if ($result->rowCount()) {
        $response["hotels"] = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          array_push($response["hotels"], $row);
        }
        Response::send(200, $response);
      } else {
        Response::send(404, array("message" => "No hotels found."));
      }
    }
    break;
  case 'PUT':
    break;
  case 'POST':
    break;
  case 'DELETE':
    break;
}

?>