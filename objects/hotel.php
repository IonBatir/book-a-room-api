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

  function get_hotels(){
    $query = "SELECT * FROM " . $this->table_name;
    $stmt = $this->conn->prepare($query);
  
    $stmt->execute();
    return $stmt;
  }

  function get_hotel() {
    $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(1, $this->id);

    $stmt->execute();
    return $stmt;
  }

  function add_hotel() {

  }
}