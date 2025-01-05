<?php

class CRUDOperations {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }


    public function validateTable($table) {
        $allowedTables = ['users', 'products', 'orders']; 
        return in_array($table, $allowedTables);
    }


    public function createRecord($table, $data) {
        $columns = implode(", ", array_keys($data)) . ", created_at";
        $values = ":" . implode(", :", array_keys($data)) . ", NOW()";

        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        $stmt = $this->conn->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        if ($stmt->execute()) {
            return ["status" => "success", "message" => "Record created successfully"];
        } else {
            throw new Exception("Failed to create record");
        }
    }


    public function updateRecord($table, $id, $data) {
        $updates = [];
        foreach ($data as $key => $value) {
            $updates[] = "$key = :$key";
        }
        $updates = implode(", ", $updates) . ", updated_at = NOW()";

        $sql = "UPDATE $table SET $updates WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return ["status" => "success", "message" => "Record updated successfully"];
        } else {
            throw new Exception("Failed to update record");
        }
    }


    public function deleteRecord($table, $id) {
        $sql = "DELETE FROM $table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return ["status" => "success", "message" => "Record deleted successfully"];
        } else {
            throw new Exception("Failed to delete record");
        }
    }


    public function readRecord($table, $id = null) {
        if ($id) {
            $sql = "SELECT * FROM $table WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM $table";
            $stmt = $this->conn->prepare($sql);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

