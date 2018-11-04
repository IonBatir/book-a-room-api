<?php
class Option {
  private $conn;
  private $table_name = "options";

  public $id;
  public $name;
  public $price;

  public $fields = array("id", "name", "price");

  public $nr_fields = 3;

  public function __construct($db){
    $this->conn = $db;
  }

  function get_all(){
    $query = "SELECT * FROM ".$this->table_name;

    $stmt = $this->conn->prepare($query);
  
    $stmt->execute();

    return $stmt;
  }

  function get() {
    $query = "SELECT * FROM ".$this->table_name." WHERE id = :id LIMIT 1";

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