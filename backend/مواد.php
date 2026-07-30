<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate input data
if (!isset($input['id']) && !isset($input['name']) && !isset($input['description'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

// Sanitize input data
$input['name'] = trim($input['name'] ?? '');
$input['description'] = trim($input['description'] ?? '');

// Connect to database
$db = new PDO('sqlite:' . DB_NAME);

// Handle GET request
if (isset($_GET['id'])) {
    // Get single record by ID
    $stmt = $db->prepare('SELECT * FROM مواد WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($record) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($record);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Record not found']);
    }
} elseif (isset($_GET['all'])) {
    // Get all records
    $stmt = $db->query('SELECT * FROM مواد');
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request method']);
}

// Handle POST request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert new record
    if (isset($input['name']) && isset($input['description'])) {
        $stmt = $db->prepare('INSERT INTO مواد (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record created successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
    }
}

// Handle PUT request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update existing record
    if (isset($input['id']) && isset($input['name']) && isset($input['description'])) {
        $stmt = $db->prepare('UPDATE مواد SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record updated successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
    }
}

// Handle DELETE request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Delete existing record
    if (isset($input['id'])) {
        $stmt = $db->prepare('DELETE FROM مواد WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record deleted successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
    }
}

// Close database connection
$db = null;