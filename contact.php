<?php
header('Content-Type: application/json');
session_start();
require_once "config.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to add a contact.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$name  = trim($input['name'] ?? '');
$phone = trim($input['phone'] ?? '');
$user_id = $_SESSION['user_id'];

$response = ['success' => false, 'message' => 'Something went wrong!'];

// Validate input
if ($name === '' || $phone === '') {
    $response['message'] = 'All fields are required.';
    echo json_encode($response);
    exit();
}

// Insert contact
$stmt = $conn->prepare("INSERT INTO contacts (user_id, name, phone) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $name, $phone);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Contact added successfully!';
} else {
    $response['message'] = $stmt->error;
}

$stmt->close();
$conn->close();
echo json_encode($response);
?>
