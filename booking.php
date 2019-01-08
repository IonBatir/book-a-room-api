<?php
include_once 'config/headers.php';
include_once 'config/database.php';
include_once 'config/core.php';
include_once 'objects/booking.php';

$database = new Database();
$db = $database->getConnection();

$booking = new Booking($db);

$booking->id = isset($_GET['id']) ? $_GET['id'] : NULL;

$data = json_decode(file_get_contents("php://input"));

if ($data)
  Utils::sanitize_fields($data);

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    $result = $booking->id ? $booking->get() : $booking->get_all();
    if ($result->rowCount()) {
      if ($booking->id) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $response = array("booking" => $row);
      } else {
        $response["bookings"] = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          array_push($response["bookings"], $row);
        }
      }
      Response::send(200, $response);
    } else {
      Response::send(404, array("message" => "No booking found."));
    }
    break;
  case 'PUT':
    if (Utils::isset_all($data, $booking->fields)) {
      foreach ($booking->fields as $field)
        $booking->{$field} = $data->{$field};      
      $booking->update() ? Response::send(200, array("message" => "Booking was updated.")) : Response::send(503, array("message" => "Unable to update booking."));
    } else
      Response::send(400, array("message" => "Unable to update booking. Data is incomplete."));
    break;
  case 'POST':
    $data->id = UUID::v4();
    if (Utils::isset_all($data, $booking->fields)) {
      foreach ($booking->fields as $field)
        $booking->{$field} = $data->{$field};
      $booking->add() ? Response::send(201, array("message" => "Booking was added.")) : Response::send(503, array("message" => "Unable to add booking."));
    } else
      Response::send(400, array("message" => "Unable to add booking. Data is incomplete."));
    break;
  case 'DELETE':
    if (isset($booking->id))
      $booking->delete() ? Response::send(200, array("message" => "Booking was deleted.")) : Response::send(503, array("message" => "Unable to delete booking."));
    else
      Response::send(400, array("message" => "Unable to delete booking. Data is incomplete."));
    break;
}
?>