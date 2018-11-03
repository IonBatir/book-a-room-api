<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once 'config/database.php';
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
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $response["hotel"] = array();
        array_push($response["hotel"], $row);
        http_response_code(200);
        echo json_encode($response);
      } else {
        http_response_code(404);
        echo json_encode(
          array("message" => "Hotel not found.")
        );
      }
    } else {
      $result = $hotel->getHotels();
      if ($result->rowCount()) {
        $response["hotels"] = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          array_push($response["hotels"], $row);
        }
        http_response_code(200);
        echo json_encode($response);
      } else {
        http_response_code(404);
        echo json_encode(
          array("message" => "No hotels found.")
        );
      }
    }
    break;
  case 'PUT':
    $sql = "update `$table` set $set where id=$key"; 
    break;
  case 'POST':
    $sql = "insert into `$table` set $set"; 
    break;
  case 'DELETE':
    $sql = "delete `$table` where id=$key"; 
    break;
}

?>