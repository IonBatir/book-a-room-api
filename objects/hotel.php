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

  public function __construct($db){
    $this->conn = $db;
  }

  function sanitize_fields() {
    $this->id = htmlspecialchars(strip_tags($this->id));
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->nr_stars = htmlspecialchars(strip_tags($this->nr_stars));
    $this->nr_floors = htmlspecialchars(strip_tags($this->nr_floors));
    $this->address = htmlspecialchars(strip_tags($this->address));
    $this->city_id = htmlspecialchars(strip_tags($this->city_id));
    $this->description = htmlspecialchars(strip_tags($this->description));
    $this->swimming_pool = htmlspecialchars(strip_tags($this->swimming_pool));
    $this->gym = htmlspecialchars(strip_tags($this->gym));
    $this->restaurant = htmlspecialchars(strip_tags($this->restaurant));
    $this->bar = htmlspecialchars(strip_tags($this->bar));
    $this->wifi = htmlspecialchars(strip_tags($this->wifi));
    $this->car_hire = htmlspecialchars(strip_tags($this->car_hire));
    $this->parking = htmlspecialchars(strip_tags($this->parking));
    $this->laundry = htmlspecialchars(strip_tags($this->laundry));
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

    $stmt->bindParam(":id", $this->id);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":nr_stars", $this->nr_stars);
    $stmt->bindParam(":nr_floors", $this->nr_floors);
    $stmt->bindParam(":address", $this->address);
    $stmt->bindParam(":city_id", $this->city_id);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":swimming_pool", $this->swimming_pool);
    $stmt->bindParam(":gym", $this->gym);
    $stmt->bindParam(":restaurant", $this->restaurant);
    $stmt->bindParam(":bar", $this->bar);
    $stmt->bindParam(":wifi", $this->wifi);
    $stmt->bindParam(":car_hire", $this->car_hire);
    $stmt->bindParam(":parking", $this->parking);
    $stmt->bindParam(":laundry", $this->laundry);

    return $stmt->execute();
  }
}