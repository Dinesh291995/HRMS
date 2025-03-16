<?php
// api/index.php

// Allow requests from any origin (replace * with your frontend URL in production)
header("Access-Control-Allow-Origin: *");

// Allow specific HTTP methods
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow specific headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../controllers/EmployeeController.php';

header("Content-Type: application/json");

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$employeeController = new EmployeeController();

// Parse the request URI
$uriSegments = explode('/', trim($requestUri, '/'));

// Route requests
if ($uriSegments[0] === 'api' && $uriSegments[1] === 'employees') {
    $employee_id = $uriSegments[2] ?? null;

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

    echo json_encode($response);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Resource not found']);
}