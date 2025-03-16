<?php
// index.php

require_once __DIR__ . '/../controllers/EmployeeController.php';

header("Content-Type: application/json");

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$employeeController = new EmployeeController();

// Parse the request URI
$uriSegments = explode('/', trim($requestUri, '/'));

// Route requests
if ($uriSegments[0] === 'employees') {
    $employee_id = $uriSegments[1] ?? null;

    switch ($requestMethod) {
        case 'GET':
            if ($employee_id) {
                $response = $employeeController->getById($employee_id);
            } else {
                $response = $employeeController->getAll();
            }
            break;

        case 'POST':
            $response = $employeeController->create();
            break;

        case 'PUT':
            if ($employee_id) {
                $response = $employeeController->update($employee_id);
            } else {
                http_response_code(400);
                $response = ['message' => 'Employee ID is required'];
            }
            break;

        case 'DELETE':
            if ($employee_id) {
                $response = $employeeController->delete($employee_id);
            } else {
                http_response_code(400);
                $response = ['message' => 'Employee ID is required'];
            }
            break;

        default:
            http_response_code(405);
            $response = ['message' => 'Method not allowed'];
            break;
    }

    // Send the response
    echo json_encode($response);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Resource not found']);
}