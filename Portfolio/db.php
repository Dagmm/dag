<?php

class db {
    private $host;
    private $username;
    private $password;
    private $database;
    private $conn;

    public function __construct() {
        $this->host = getenv('DB_HOST') ?: "localhost";
        $this->username = getenv('DB_USERNAME') ?: "root";
        $this->password = getenv('DB_PASSWORD') ?: "";
        $this->database = getenv('DB_NAME') ?: "portfolio";
    }


    public function connect() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {

                error_log("Connection failed: " . $e->getMessage());
                die("Connection failed. Please try again later.");
            }
        }

        return $this->conn;
    }
}

