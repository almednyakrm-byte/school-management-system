<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/mqrtrs' => array('GET', 'GET ALL'),
    '/mqrtrs' => array('POST', 'CREATE'),
    '/mqrtrs/:id' => array('GET', 'READ'),
    '/mqrtrs/:id' => array('PUT', 'UPDATE'),
    '/mqrtrs/:id' => array('DELETE', 'DELETE')
);

// Parse route
$route = explode('/', $_SERVER['REQUEST_URI']);
array_shift($route); // Remove empty string
array_shift($route); // Remove 'mqrtrs'

// Check if route is valid
if (!isset($routes['/' . implode('/', $route)])) {
    http_response_code(404);
    echo json_encode(array('error' => 'Not Found'));
    exit;
}

// Check if user is admin for edits/deletions
if (in_array($_SESSION['user_role'], array('admin')) && in_array($_SERVER['REQUEST_METHOD'], array('PUT', 'DELETE'))) {
    // Admin-only
} else {
    // Non-admin
}

// Handle request
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // GET ALL
        $stmt = $pdo->prepare('SELECT * FROM mqrtrs');
        $stmt->execute();
        $mqrtrs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($mqrtrs);
        break;
    case 'POST':
        // CREATE
        if (!isset($input['name']) || !isset($input['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Validation failed'));
            exit;
        }
        $name = $pdo->quote($input['name']);
        $description = $pdo->quote($input['description']);
        $stmt = $pdo->prepare('INSERT INTO mqrtrs (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Created successfully'));
        break;
    case 'GET':
        // READ
        $id = (int) $route[0];
        $stmt = $pdo->prepare('SELECT * FROM mqrtrs WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $mqrtr = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$mqrtr) {
            http_response_code(404);
            echo json_encode(array('error' => 'Not Found'));
            exit;
        }
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($mqrtr);
        break;
    case 'PUT':
        // UPDATE
        if (!isset($input['name']) || !isset($input['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Validation failed'));
            exit;
        }
        $id = (int) $route[0];
        $name = $pdo->quote($input['name']);
        $description = $pdo->quote($input['description']);
        $stmt = $pdo->prepare('UPDATE mqrtrs SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Updated successfully'));
        break;
    case 'DELETE':
        // DELETE
        $id = (int) $route[0];
        $stmt = $pdo->prepare('DELETE FROM mqrtrs WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Deleted successfully'));
        break;
}

?>