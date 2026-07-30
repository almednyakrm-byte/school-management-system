<?php
// Import database connection
require_once 'db.php';

// Initialize database connection
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);

// Function to check if user is logged in
function isLoggedIn() {
    // Implement your own logic to check if user is logged in
    // For demonstration purposes, assume a logged-in user
    return true;
}

// Function to check if user is admin
function isAdmin() {
    // Implement your own logic to check if user is admin
    // For demonstration purposes, assume an admin user
    return true;
}

// Handle HTTP requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    
    // Check if user is logged in
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    // SQL query structure: Select all or by id
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM مدارس WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
    } else {
        $stmt = $pdo->prepare('SELECT * FROM مدارس');
        $stmt->execute();
        $result = $stmt->fetchAll();
    }
    
    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
}

elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    // Read input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $address = filter_var($input['address'] ?? null, FILTER_SANITIZE_STRING);
    
    // SQL query structure: Insert
    $stmt = $pdo->prepare('INSERT INTO مدارس (name, address) VALUES (:name, :address)');
    $stmt->execute([':name' => $name, ':address' => $address]);
    
    // Output processing
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Created successfully']);
}

elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    // Read input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $address = filter_var($input['address'] ?? null, FILTER_SANITIZE_STRING);
    
    // SQL query structure: Update
    $stmt = $pdo->prepare('UPDATE مدارس SET name = :name, address = :address WHERE id = :id');
    $stmt->execute([':id' => $id, ':name' => $name, ':address' => $address]);
    
    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Updated successfully']);
}

elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    // Read input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    
    // SQL query structure: Delete
    $stmt = $pdo->prepare('DELETE FROM مدارس WHERE id = :id');
    $stmt->execute([':id' => $id]);
    
    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Deleted successfully']);
}

else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}