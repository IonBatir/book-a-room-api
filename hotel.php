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
    $data = json_decode(file_get_contents("php://input"));
    if (
      !empty($data->id) &&
      !empty($data->name) &&
      !empty($data->nr_stars) &&
      !empty($data->nr_floors) &&
      !empty($data->address) &&
      !empty($data->city_id) &&
      !empty($data->description) &&
      !empty($data->swimming_pool) &&
      !empty($data->gym) &&
      !empty($data->restaurant) &&
      !empty($data->bar) &&
      !empty($data->wifi) &&
      !empty($data->car_hire) &&
      !empty($data->parking) &&
      !empty($data->laundry)
    ) {
      $hotel->id = $data->id;
      $hotel->name = $data->name;
      $hotel->nr_stars = $data->nr_stars;
      $hotel->nr_floors = $data->nr_floors;
      $hotel->address = $data->address;
      $hotel->city_id = $data->city_id;
      $hotel->description = $data->description;
      $hotel->swimming_pool = $data->swimming_pool;
      $hotel->gym = $data->gym;
      $hotel->restaurant = $data->restaurant;
      $hotel->bar = $data->bar;
      $hotel->wifi = $data->wifi;
      $hotel->car_hire = $data->car_hire;
      $hotel->parking = $data->parking;
      $hotel->laundry = $data->laundry;
   
      $hotel->add_hotel() ? Response::send(201, array("message" => "Hotel was added.")) : Response::send(503, array("message" => "Unable to add hotel."));
    } else {
      Response::send(400, array("message" => "Unable to add hotel. Data is incomplete."));
  }
    break;
  case 'DELETE':
    break;
}

?>