<?php
include_once 'config/headers.php';
include_once 'config/database.php';
include_once 'config/core.php';
include_once 'objects/hotel.php';

$database = new Database();
$db = $database->getConnection();

$hotel = new Hotel($db);

$hotel->id = isset($_GET['id']) ? $_GET['id'] : NULL;

$data = json_decode(file_get_contents("php://input"));

if ($data) {
  Utils::sanitize_fields($data);
  if (Utils::isset_all($data, $hotel->fields))
    foreach ($hotel->fields as $field)
      $hotel->{$field} = $data->{$field};
  else
    exit("Data is incomplete.");
}

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    $result = $hotel->id ? $hotel->get() : $hotel->get_all();
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
    if (Utils::isset_all($hotel, $hotel->fields))
      $hotel->update() ? Response::send(200, array("message" => "Hotel was updated.")) : Response::send(503, array("message" => "Unable to update hotel."));
    else
      Response::send(400, array("message" => "Unable to update hotel. Data is incomplete."));
    break;
  case 'POST':
    $hotel->id = UUID::v4();
    if (Utils::isset_all($hotel, $hotel->fields))
      $hotel->add() ? Response::send(201, array("message" => "Hotel was added.")) : Response::send(503, array("message" => "Unable to add hotel."));
    else
      Response::send(400, array("message" => "Unable to add hotel. Data is incomplete."));
    break;
  case 'DELETE':
    if (isset($hotel->id))
      $hotel->delete() ? Response::send(200, array("message" => "Hotel was deleted.")) : Response::send(503, array("message" => "Unable to delete hotel."));
    else
      Response::send(400, array("message" => "Unable to delete hotel. Data is incomplete."));
    break;
}
?>