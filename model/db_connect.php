<?php

class DATABASE {

    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $dbname = 'blog';
    private $conn;
    
    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->password);
            
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  

        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
    public function closeConnection() {
        $this->conn = null;
    }
}

$conn = new DATABASE();

?>
