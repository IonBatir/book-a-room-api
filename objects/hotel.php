<?php
class Hotel {
  private $conn;
  private $table_name = "hotels";
  private $view_name = "hotels_view"

  public $id;
  public $name;
  public $nr_stars;
  public $nr_floors;
  public $address;
  public $city_id;
  public $description;
  public $swimming_pool;
  public $gym;
  public $restaurant;
  public $bar;
  public $wifi;
  public $car_hire;
  public $parking;
  public $laundry;

  public $fields = array("id", "name", "nr_stars", "nr_floors", "address", "city_id",
  "description", "swimming_pool", "gym", "restaurant", "bar", "wifi", "car_hire", "parking", "laundry");

  public $nr_fields = 15;

  public function __construct($db){
    $this->conn = $db;
  }

  function get_all(){
    $query = "SELECT * FROM ".$this->view_name;

    $stmt = $this->conn->prepare($query);
  
    $stmt->execute();

    return $stmt;
  }

  function get() {
    $query = "SELECT * FROM ".$this->view_name." WHERE id = :id LIMIT 1";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":id", $this->id);

    $stmt->execute();

    return $stmt;
  }

  function add() {
    $query = "INSERT INTO ".$this->table_name." VALUES (";
    for ($i = 0; $i < $this->nr_fields; $i++)
      if ($i == $this->nr_fields - 1)
        $query .= ":".$this->fields[$i].")";
      else
        $query .= ":".$this->fields[$i].", ";
      
    $stmt = $this->conn->prepare($query);

    foreach ($this->fields as $field)
      $stmt->bindParam(":".$field, $this->{$field});

    return $stmt->execute();
  }

  function update() {
    $query = "UPDATE ".$this->table_name." SET ";
    for ($i = 1; $i < $this->nr_fields; $i++)
      if ($i == $this->nr_fields - 1)
        $query .= $this->fields[$i]." = :".$this->fields[$i]." WHERE id = :id";
      else
        $query .= $this->fields[$i]." = :".$this->fields[$i].", ";
        
    $stmt = $this->conn->prepare($query);

    foreach ($this->fields as $field)
      $stmt->bindParam(":".$field, $this->{$field});

    return $stmt->execute();
  }

  function delete() {
    $query = "DELETE FROM ".$this->table_name." WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":id", $this->id);

    return $stmt->execute();
  }
}