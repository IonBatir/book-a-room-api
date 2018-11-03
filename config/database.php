<?php
class Database{
    private $host = "localhost";
    private $db_name = "ionbatir_book-a-room";
    private $username = "ionbatir_ib";
    private $password = "YRb-YXw-Pdn-G9z";
    
    public $conn;

    public function getConnection(){
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>