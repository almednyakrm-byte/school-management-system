<?php

// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response with the user's data
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array(
        'status' => 'logged_in',
        'user_id' => $user_id,
        'username' => $username
    );
    echo json_encode($response);
    exit;
}

// Handle the login request
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize the input fields to prevent SQL injection
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Prepare the SQL query to select the user
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch();

        // Check if the user exists and the password is correct
        if ($user && password_verify($password, $user['password'])) {
            // If the password is correct, log the user in and return a JSON response
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $response = array(
                'status' => 'logged_in',
                'user_id' => $user['id'],
                'username' => $user['username']
            );
            echo json_encode($response);
        } else {
            // If the password is incorrect, return a JSON response with an error message
            $response = array(
                'status' => 'error',
                'message' => 'Invalid username or password'
            );
            echo json_encode($response);
        }
    } else {
        // If the username or password is missing, return a JSON response with an error message
        $response = array(
            'status' => 'error',
            'message' => 'Missing username or password'
        );
        echo json_encode($response);
    }
}

// Handle the register request
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the username, email, and password are set
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input fields to prevent SQL injection
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Check if the username and email are unique
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch();

        // Check if the username or email is already taken
        if ($user) {
            // If the username or email is already taken, return a JSON response with an error message
            $response = array(
                'status' => 'error',
                'message' => 'Username or email already taken'
            );
            echo json_encode($response);
        } else {
            // If the username and email are unique, hash the password and insert the user data into the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->execute();

            // If the user is registered successfully, return a JSON response with a success message
            $response = array(
                'status' => 'registered',
                'message' => 'User registered successfully'
            );
            echo json_encode($response);
        }
    } else {
        // If the username, email, or password is missing, return a JSON response with an error message
        $response = array(
            'status' => 'error',
            'message' => 'Missing username, email, or password'
        );
        echo json_encode($response);
    }
}

// Handle the logout request
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy the session to log the user out
    session_destroy();
    $response = array(
        'status' => 'logged_out'
    );
    echo json_encode($response);
}

?>