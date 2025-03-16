<?php
// controllers/EmployeeController.php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Employee.php';

class EmployeeController {
    private $employeeModel;

    public function __construct() {
        $pdo = include __DIR__ . '/../config/db.php';
        $this->employeeModel = new Employee($pdo);
    }

    // Create a new employee
    public function create() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data)) {
            $employeeId = $this->employeeModel->create($data);
            return ['message' => 'Employee created successfully', 'employee_id' => $employeeId];
        } else {
            http_response_code(400);
            return ['message' => 'Invalid input'];
        }
    }

    // Get an employee by ID
    public function getById($employee_id) {
        $employee = $this->employeeModel->getById($employee_id);

        if ($employee) {
            return $employee;
        } else {
            http_response_code(404);
            return ['message' => 'Employee not found'];
        }
    }

    // Update an employee
    public function update($employee_id) {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data)) {
            $result = $this->employeeModel->update($employee_id, $data);
            if ($result) {
                return ['message' => 'Employee updated successfully'];
            } else {
                http_response_code(500);
                return ['message' => 'Failed to update employee'];
            }
        } else {
            http_response_code(400);
            return ['message' => 'Invalid input'];
        }
    }

    // Delete an employee
    public function delete($employee_id) {
        $result = $this->employeeModel->delete($employee_id);

        if ($result) {
            return ['message' => 'Employee deleted successfully'];
        } else {
            http_response_code(500);
            return ['message' => 'Failed to delete employee'];
        }
    }

    // Get all employees
    public function getAll() {
        $employees = $this->employeeModel->getAll();
        return $employees;
    }
}