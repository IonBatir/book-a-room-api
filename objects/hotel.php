<?php
class Hotel {
  private $conn;
  private $table_name = "hotels";

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

  public function __construct($db){
    $this->conn = $db;
  }

  function sanitize_fields() {
    foreach ($this->fields as $field)
      $this->{$field} = htmlspecialchars(strip_tags($this->{$field}));
  }

  function get_hotels(){
    $query = "SELECT * FROM ".$this->table_name;
    $stmt = $this->conn->prepare($query);
  
    $stmt->execute();
    return $stmt;
  }

  function get_hotel() {
    $query = "SELECT * FROM ".$this->table_name." WHERE id = :id LIMIT :id";
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":id", $this->id);

    $stmt->execute();
    return $stmt;
  }

  function add_hotel() {
    $query = "INSERT INTO ".$this->table_name." VALUES 
    (:id, :name, :nr_stars, :nr_floors, :address, :city_id, :description, :swimming_pool, :gym, :restaurant, :bar, :wifi, :car_hire, :parking, :laundry)";
    $stmt = $this->conn->prepare($query);

    $this->sanitize_fields();

    foreach ($this->fields as $field)
      $stmt->bindParam(":".$field, $this->{$field});

    return $stmt->execute();
  }
}