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

$booking_option = new BookingOption($db);

$booking_option->id = isset($_GET['id']) ? $_GET['id'] : NULL;

$data = json_decode(file_get_contents("php://input"));

if ($data) {
  Utils::sanitize_fields($data);
  foreach ($booking_option->fields as $field)
    $booking_option->{$field} = $data->{$field};
}

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    $result = $booking_option->id ? $booking_option->get() : $booking_option->get_all();
    if ($result->rowCount()) {
      if ($booking_option->id) {
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
    if (Utils::isset_all($data, $booking_option->fields))
      $booking_option->update() ? Response::send(200, array("message" => "Hotel was updated.")) : Response::send(503, array("message" => "Unable to update hotel."));
    else
      Response::send(400, array("message" => "Unable to update hotel. Data is incomplete."));
    break;
  case 'POST':
    if (Utils::isset_all($data, $booking_option->fields))
      $booking_option->add() ? Response::send(201, array("message" => "Hotel was added.")) : Response::send(503, array("message" => "Unable to add hotel."));
    else
      Response::send(400, array("message" => "Unable to add hotel. Data is incomplete."));
    break;
  case 'DELETE':
    if (isset($data->id))
      $booking_option->delete() ? Response::send(200, array("message" => "Hotel was deleted.")) : Response::send(503, array("message" => "Unable to delete hotel."));
    else
      Response::send(400, array("message" => "Unable to delete hotel. Data is incomplete."));
    break;
}
?>