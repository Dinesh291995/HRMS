<?php

class Employee {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    private function generateEmployeeId() {
        $prefix = 'TAD';
        $sql = "SELECT MAX(CAST(SUBSTRING(employee_id, 4) AS UNSIGNED)) AS max_id FROM Employee";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nextId = $result['max_id'] ? $result['max_id'] + 1 : 1;
        return $prefix . str_pad($nextId, 6, '0', STR_PAD_LEFT); // Example: TAD000001
    }

    public function create($data) {
        $data['employee_id'] = $this->generateEmployeeId();
        $sql = "INSERT INTO Employee (employee_id, first_name, last_name, email, phone_number, designation, department_id, manager_id, role_id, password_hash) 
                VALUES (:employee_id, :first_name, :last_name, :email, :phone_number, :designation, :department_id, :manager_id, :role_id, :password_hash)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $data['employee_id'];
    }

    public function getById($employee_id) {
        $sql = "SELECT * FROM Employee WHERE employee_id = :employee_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['employee_id' => $employee_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($employee_id, $data) {
        $data['employee_id'] = $employee_id;
        $sql = "UPDATE Employee 
                SET first_name = :first_name, last_name = :last_name, email = :email, phone_number = :phone_number, 
                    designation = :designation, department_id = :department_id, manager_id = :manager_id, 
                    role_id = :role_id, password_hash = :password_hash 
                WHERE employee_id = :employee_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($employee_id) {
        $sql = "DELETE FROM Employee WHERE employee_id = :employee_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['employee_id' => $employee_id]);
    }

    public function getAll() {
        $sql = "SELECT * FROM Employee";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}