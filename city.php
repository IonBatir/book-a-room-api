<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once 'config/database.php';
include_once 'config/core.php';
include_once 'objects/city.php';

$database = new Database();
$db = $database->getConnection();

$city = new City($db);

$city->id = isset($_GET['id']) ? $_GET['id'] : NULL;

$data = json_decode(file_get_contents("php://input"));

if ($data) {
  Utils::sanitize_fields($data);
  foreach ($city->fields as $field)
    $city->{$field} = $data->{$field};
}

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    $result = $city->id ? $city->get() : $city->get_all();
    if ($result->rowCount()) {
      if ($city->id) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $response = array("city" => $row);
      } else {
        $response["cities"] = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          array_push($response["cities"], $row);
        }
      }
      Response::send(200, $response);
    } else {
      Response::send(404, array("message" => "No city found."));
    }
    break;
  case 'PUT':
    if (Utils::isset_all($data, $city->fields))
      $city->update() ? Response::send(200, array("message" => "City was updated.")) : Response::send(503, array("message" => "Unable to update city."));
    else
      Response::send(400, array("message" => "Unable to update city. Data is incomplete."));
    break;
  case 'POST':
    if (Utils::isset_all($data, $city->fields))
      $city->add() ? Response::send(201, array("message" => "City was added.")) : Response::send(503, array("message" => "Unable to add city."));
    else
      Response::send(400, array("message" => "Unable to add city. Data is incomplete."));
    break;
  case 'DELETE':
    if (isset($data->id))
      $city->delete() ? Response::send(200, array("message" => "City was deleted.")) : Response::send(503, array("message" => "Unable to delete city."));
    else
      Response::send(400, array("message" => "Unable to delete city. Data is incomplete."));
    break;
}
?>