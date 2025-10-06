<?php
header('Content-Type: application/json');
session_start();
require_once "config.php";

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$email = trim($input['email'] ?? '');
$password = trim($input['password'] ?? '');

$response = ['success' => false, 'message' => 'Invalid credentials'];

// Check for empty fields
if ($email === '' || $password === '') {
    $response['message'] = "Please fill all fields!";
    echo json_encode($response);
    exit();
}

// Check if user exists
$stmt = $conn->prepare("SELECT user_id, name, email, password, role FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email']; // added email
        $_SESSION['role'] = $user['role'];

        $response['success'] = true;
        $response['message'] = "Login successful!";
        $response['role'] = $user['role']; // used in JS to redirect
    } else {
        $response['message'] = "Incorrect password!";
    }
} else {
    $response['message'] = "User not found!";
}

$stmt->close();
$conn->close();
echo json_encode($response);
?>
