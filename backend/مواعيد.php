<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON or POST request
$inputData = json_decode(file_get_contents('php://input'), true);
if (!$inputData) {
    $inputData = $_POST;
}

// Define validation rules
$validationRules = array(
    'id' => array('required' => true, 'type' => 'integer'),
    'title' => array('required' => true, 'type' => 'string'),
    'date' => array('required' => true, 'type' => 'date'),
    'time' => array('required' => true, 'type' => 'string')
);

// Validate input data
foreach ($validationRules as $field => $rules) {
    if (!isset($inputData[$field])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing required field: ' . $field));
        exit;
    }
    if ($rules['type'] == 'integer' && !is_int($inputData[$field])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid type for field: ' . $field));
        exit;
    }
    if ($rules['type'] == 'string' && !is_string($inputData[$field])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid type for field: ' . $field));
        exit;
    }
    if ($rules['type'] == 'date' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $inputData[$field])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid date format for field: ' . $field));
        exit;
    }
}

// Sanitize input data
$inputData = array_map('trim', $inputData);

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    if (isset($inputData['id'])) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Handle GET request
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM مواعيد WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($row);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif (isset($_GET['all'])) {
    $stmt = $pdo->prepare('SELECT * FROM مواعيد');
    $stmt->execute();
    $rows = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($rows);
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}

// Handle POST request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare('INSERT INTO مواعيد (title, date, time) VALUES (:title, :date, :time)');
    $stmt->bindParam(':title', $inputData['title']);
    $stmt->bindParam(':date', $inputData['date']);
    $stmt->bindParam(':time', $inputData['time']);
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal server error'));
    }
}

// Handle PUT request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $stmt = $pdo->prepare('UPDATE مواعيد SET title = :title, date = :date, time = :time WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->bindParam(':title', $inputData['title']);
    $stmt->bindParam(':date', $inputData['date']);
    $stmt->bindParam(':time', $inputData['time']);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Updated successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal server error'));
    }
}

// Handle DELETE request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $stmt = $pdo->prepare('DELETE FROM مواعيد WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Deleted successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal server error'));
    }
}