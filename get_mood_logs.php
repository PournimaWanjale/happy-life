<?php
header('Content-Type: application/json');
include 'config.php'; // your DB connection

session_start();

// Example: get user ID from session
$userId = $_SESSION['user_id'] ?? 1; // replace 1 with proper session handling

$sql = "SELECT mood, note, created_at FROM mood_logs WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);

if($stmt->execute()){
    $result = $stmt->get_result();
    $logs = [];
    while($row = $result->fetch_assoc()){
        $logs[] = [
            'mood' => $row['mood'],
            'note' => $row['note'],
            'date' => $row['created_at']
        ];
    }
    echo json_encode(['logs' => $logs]);
} else {
    echo json_encode(['logs' => []]);
}

$stmt->close();
$conn->close();
?>
