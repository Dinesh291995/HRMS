// api/employees.php
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Employee.php';
require_once __DIR__ . '/../controllers/EmployeeController.php';

header("Content-Type: application/json");

// Initialize the EmployeeController
$employeeController = new EmployeeController();

// Parse the request URI
$requestUri = $_SERVER['REQUEST_URI'];
$uriSegments = explode('/', trim($requestUri, '/'));

// Extract the employee ID if present
$employee_id = $uriSegments[2] ?? null;

// Route requests based on HTTP method
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if ($employee_id) {
            // GET /api/employees/{employee_id}
            $response = $employeeController->getById($employee_id);
        } else {
            // GET /api/employees
            $response = $employeeController->getAll();
        }
        break;

    case 'POST':
        // POST /api/employees
        $response = $employeeController->create();
        break;

    case 'PUT':
        if ($employee_id) {
            // PUT /api/employees/{employee_id}
            $response = $employeeController->update($employee_id);
        } else {
            http_response_code(400);
            $response = ['message' => 'Employee ID is required'];
        }
        break;

    case 'DELETE':
        if ($employee_id) {
            // DELETE /api/employees/{employee_id}
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