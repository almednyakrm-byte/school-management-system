<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (isset($_POST['action']) && ($_POST['action'] == 'edit' || $_POST['action'] == 'delete')) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);
if ($input === null) {
    $input = $_POST;
}

// Validate input data
if (!isset($input['action'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Handle different actions
switch ($input['action']) {
    case 'get_all':
        // Get all teachers
        $stmt = $pdo->prepare('SELECT * FROM teachers');
        $stmt->execute();
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($teachers);
        break;

    case 'get_by_id':
        // Get teacher by ID
        if (!isset($input['id']) || !is_numeric($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid ID'));
            exit;
        }
        $stmt = $pdo->prepare('SELECT * FROM teachers WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($teacher === false) {
            http_response_code(404);
            echo json_encode(array('error' => 'Teacher not found'));
            exit;
        }
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($teacher);
        break;

    case 'create':
        // Create new teacher
        if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required fields'));
            exit;
        }
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);
        $stmt = $pdo->prepare('INSERT INTO teachers (name, email, phone) VALUES (:name, :email, :phone)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array('message' => 'Teacher created successfully'));
        } else {
            http_response_code(500);
            echo json_encode(array('error' => 'Failed to create teacher'));
        }
        break;

    case 'update':
        // Update existing teacher
        if (!isset($input['id']) || !is_numeric($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid ID'));
            exit;
        }
        if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required fields'));
            exit;
        }
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);
        $stmt = $pdo->prepare('UPDATE teachers SET name = :name, email = :email, phone = :phone WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(array('message' => 'Teacher updated successfully'));
        } else {
            http_response_code(500);
            echo json_encode(array('error' => 'Failed to update teacher'));
        }
        break;

    case 'delete':
        // Delete teacher
        if (!isset($input['id']) || !is_numeric($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid ID'));
            exit;
        }
        $stmt = $pdo->prepare('DELETE FROM teachers WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(array('message' => 'Teacher deleted successfully'));
        } else {
            http_response_code(500);
            echo json_encode(array('error' => 'Failed to delete teacher'));
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid action'));
        break;
}