<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all teachers
    $stmt = $pdo->prepare('SELECT * FROM معلمين');
    $stmt->execute();
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return teachers
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($teachers);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    if (!isset($inputData['اسم المعلم']) || !isset($inputData['عنوان المعلم']) || !isset($inputData['البريد الالكتروني'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = htmlspecialchars($inputData['اسم المعلم']);
    $title = htmlspecialchars($inputData['عنوان المعلم']);
    $email = htmlspecialchars($inputData['البريد الالكتروني']);

    // Insert teacher
    $stmt = $pdo->prepare('INSERT INTO معلمين (اسم المعلم, عنوان المعلم, البريد الالكتروني) VALUES (:name, :title, :email)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Return success message
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Teacher added successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['اسم المعلم']) || !isset($inputData['عنوان المعلم']) || !isset($inputData['البريد الالكتروني'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($inputData['id']);
    $name = htmlspecialchars($inputData['اسم المعلم']);
    $title = htmlspecialchars($inputData['عنوان المعلم']);
    $email = htmlspecialchars($inputData['البريد الالكتروني']);

    // Update teacher
    $stmt = $pdo->prepare('UPDATE معلمين SET اسم المعلم = :name, عنوان المعلم = :title, البريد الالكتروني = :email WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Teacher updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
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

    // Delete teacher
    $stmt = $pdo->prepare('DELETE FROM معلمين WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Teacher deleted successfully'));
    exit;
}