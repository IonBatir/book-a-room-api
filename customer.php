<?php
include_once 'config/headers.php';
include_once 'config/database.php';
include_once 'config/core.php';
include_once 'objects/customer.php';

$database = new Database();
$db = $database->getConnection();

$customer = new Customer($db);

$customer->id = isset($_GET['id']) ? $_GET['id'] : NULL;

$data = json_decode(file_get_contents("php://input"));

if ($data)
  Utils::sanitize_fields($data);

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    $result = $customer->id ? $customer->get() : $customer->get_all();
    if ($result->rowCount()) {
      if ($customer->id) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $response = array("customer" => $row);
      } else {
        $response["customers"] = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          array_push($response["customers"], $row);
        }
      }
      Response::send(200, $response);
    } else {
      Response::send(404, array("message" => "No customer found."));
    }
    break;
  case 'PUT':
    if (Utils::isset_all($data, $customer->fields)) {
      foreach ($customer->fields as $field)
        $customer->{$field} = $data->{$field};
      $customer->update() ? Response::send(200, array("message" => "Customer was updated.")) : Response::send(503, array("message" => "Unable to update customer."));
    } else
      Response::send(400, array("message" => "Unable to update customer. Data is incomplete."));
    break;
  case 'POST':
    $data->id = UUID::v4();
    if (Utils::isset_all($data, $customer->fields)) {
      foreach ($customer->fields as $field)
        $customer->{$field} = $data->{$field};
      $customer->add() ? Response::send(201, array("message" => "Customer was added.")) : Response::send(503, array("message" => "Unable to add customer."));
    } else
      Response::send(400, array("message" => "Unable to add customer. Data is incomplete."));
    break;
  case 'DELETE':
    if (isset($customer->id))
      $customer->delete() ? Response::send(200, array("message" => "Customer was deleted.")) : Response::send(503, array("message" => "Unable to delete customer."));
    else
      Response::send(400, array("message" => "Unable to delete customer. Data is incomplete."));
    break;
}
?>