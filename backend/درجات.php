<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON body
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/degrees' => array('GET' => 'getDegrees', 'POST' => 'addDegree'),
    '/degrees/:id' => array('GET' => 'getDegree', 'PUT' => 'updateDegree', 'DELETE' => 'deleteDegree')
);

// Get route and method
$route = explode('/', $_SERVER['REQUEST_URI']);
$method = $_SERVER['REQUEST_METHOD'];

// Check if route exists
if (!isset($routes['/' . implode('/', array_slice($route, 1))])) {
    http_response_code(404);
    echo json_encode(array('error' => 'Not Found'));
    exit;
}

// Get route and method
list($route, $method) = explode(':', $routes['/' . implode('/', array_slice($route, 1))]);

// Call corresponding function
$func = $method . $route;
$func();

// Functions
function getDegrees() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM degrees');
    $stmt->execute();
    $degrees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($degrees);
}

function getDegree() {
    global $pdo;
    $id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM degrees WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $degree = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$degree) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not Found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($degree);
}

function addDegree() {
    global $pdo;
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    // Sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
    // Insert data
    $stmt = $pdo->prepare('INSERT INTO degrees (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Degree added successfully'));
}

function updateDegree() {
    global $pdo;
    $id = $_GET['id'];
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    // Sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
    // Check if user is admin
    if (!$_SESSION['user']['role'] == 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Update data
    $stmt = $pdo->prepare('UPDATE degrees SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Degree updated successfully'));
}

function deleteDegree() {
    global $pdo;
    $id = $_GET['id'];
    // Check if user is admin
    if (!$_SESSION['user']['role'] == 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Delete data
    $stmt = $pdo->prepare('DELETE FROM degrees WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Degree deleted successfully'));
}

?>