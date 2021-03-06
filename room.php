<?php
include_once 'config/headers.php';
include_once 'config/database.php';
include_once 'config/core.php';
include_once 'objects/room.php';

$database = new Database();
$db = $database->getConnection();

$room = new Room($db);

$room->id = isset($_GET['id']) ? $_GET['id'] : NULL;

$data = json_decode(file_get_contents("php://input"));

if ($data)
  Utils::sanitize_fields($data);

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    $result = $room->id ? $room->get() : $room->get_all();
    if ($result->rowCount()) {
      if ($room->id) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $response = array("room" => $row);
      } else {
        $response["rooms"] = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          array_push($response["rooms"], $row);
        }
      }
      Response::send(200, $response);
    } else {
      Response::send(404, array("message" => "No room found."));
    }
    break;
  case 'PUT':
    if (Utils::isset_all($data, $room->fields)) {
      foreach ($room->fields as $field)
        $room->{$field} = $data->{$field};
      $room->update() ? Response::send(200, array("message" => "Room was updated.")) : Response::send(503, array("message" => "Unable to update room."));
    } else
      Response::send(400, array("message" => "Unable to update room. Data is incomplete."));
    break;
  case 'POST':
    $data->id = UUID::v4();
    if (Utils::isset_all($data, $room->fields)) {
      foreach ($room->fields as $field)
        $room->{$field} = $data->{$field};
      $room->add() ? Response::send(201, array("message" => "Room was added.")) : Response::send(503, array("message" => "Unable to add room."));
    } else
      Response::send(400, array("message" => "Unable to add room. Data is incomplete."));
    break;
  case 'DELETE':
    if (isset($room->id))
      $room->delete() ? Response::send(200, array("message" => "Room was deleted.")) : Response::send(503, array("message" => "Unable to delete room."));
    else
      Response::send(400, array("message" => "Unable to delete room. Data is incomplete."));
    break;
}
?>