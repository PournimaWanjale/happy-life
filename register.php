<?php
// Debug mode (remove these 3 lines later in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once "config.php";

$response = ['success' => false, 'message' => 'Something went wrong'];

// Read JSON input
$input = json_decode(file_get_contents('php://input'), true);

$name     = trim($input['name'] ?? '');
$email    = trim($input['email'] ?? '');
$password = trim($input['password'] ?? '');
$role     = "user";

// ✅ 1. Validate
if ($name === '' || $email === '' || $password === '') {
    $response['message'] = "All fields are required!";
    echo json_encode($response);
    exit;
}

// ✅ 2. Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// ✅ 3. Insert into DB
$sql = "INSERT INTO users (name, email, password, role) VALUES (?,?,?,?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Registration successful!";
    } else {
        // Duplicate email check
        if (strpos($stmt->error, 'Duplicate') !== false) {
            $response['message'] = "Email already exists!";
        } else {
            $response['message'] = "Execute error: " . $stmt->error;
        }
    }

    $stmt->close();
} else {
    $response['message'] = "Prepare error: " . $conn->error;
}

$conn->close();
echo json_encode($response);
?>
