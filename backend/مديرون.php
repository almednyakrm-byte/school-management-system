<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get') {
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all directors
    $stmt = $pdo->prepare('SELECT * FROM directors');
    $stmt->execute();
    $directors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($directors);
    exit;
}

// Handle POST request
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    // Validate input data
    if (!isset($inputData['name']) || !isset($inputData['email'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = htmlspecialchars($inputData['name']);
    $email = htmlspecialchars($inputData['email']);

    // Insert new director
    $stmt = $pdo->prepare('INSERT INTO directors (name, email) VALUES (:name, :email)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Return response
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Director created successfully'));
    exit;
}

// Handle PUT request
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['email'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($inputData['id']);
    $name = htmlspecialchars($inputData['name']);
    $email = htmlspecialchars($inputData['email']);

    // Update director
    $stmt = $pdo->prepare('UPDATE directors SET name = :name, email = :email WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Return response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Director updated successfully'));
    exit;
}

// Handle DELETE request
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($inputData['id']);

    // Delete director
    $stmt = $pdo->prepare('DELETE FROM directors WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Director deleted successfully'));
    exit;
}

// Return error response
http_response_code(400);
echo json_encode(array('error' => 'Invalid request'));
exit;