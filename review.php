<?php
include_once 'config/headers.php';
include_once 'config/database.php';
include_once 'config/core.php';
include_once 'objects/review.php';

$database = new Database();
$db = $database->getConnection();

$review = new Review($db);

$review->id = isset($_GET['id']) ? $_GET['id'] : NULL;

$data = json_decode(file_get_contents("php://input"));

if ($data) {
  Utils::sanitize_fields($data);
  foreach ($review->fields as $field)
    $review->{$field} = $data->{$field};
}

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    $result = $review->id ? $review->get() : $review->get_all();
    if ($result->rowCount()) {
      if ($review->id) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $response = array("review" => $row);
      } else {
        $response["reviews"] = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          array_push($response["reviews"], $row);
        }
      }
      Response::send(200, $response);
    } else {
      Response::send(404, array("message" => "No review found."));
    }
    break;
  case 'PUT':
    if (Utils::isset_all($review, $review->fields))
      $review->update() ? Response::send(200, array("message" => "Review was updated.")) : Response::send(503, array("message" => "Unable to update review."));
    else
      Response::send(400, array("message" => "Unable to update review. Data is incomplete."));
    break;
  case 'POST':
    $review->id = UUID::v4();
    if (Utils::isset_all($review, $review->fields))
      $review->add() ? Response::send(201, array("message" => "Review was added.")) : Response::send(503, array("message" => "Unable to add review."));
    else
      Response::send(400, array("message" => "Unable to add review. Data is incomplete."));
    break;
  case 'DELETE':
    if (isset($review->id))
      $review->delete() ? Response::send(200, array("message" => "Review was deleted.")) : Response::send(503, array("message" => "Unable to delete review."));
    else
      Response::send(400, array("message" => "Unable to delete review. Data is incomplete."));
    break;
}
?>