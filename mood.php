<?php
session_start();
require_once "config.php"; // make sure $conn is your mysqli connection

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null; // get logged-in user
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// If POST request – log mood
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $input) {
    $mood = $conn->real_escape_string($input['mood']);
    $note = $conn->real_escape_string($input['note'] ?? '');
    $date = date('Y-m-d H:i:s');

    $sql = "INSERT INTO mood_logs (user_id, mood, note, date) VALUES ('$user_id', '$mood', '$note', '$date')";
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Mood logged successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: '.$conn->error]);
    }
    exit();
}

// If GET request – fetch past moods
$sql = "SELECT mood, note, date FROM mood_logs WHERE user_id='$user_id' ORDER BY date DESC";
$result = $conn->query($sql);

$logs = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $logs[] = [
            'mood' => $row['mood'],
            'note' => $row['note'],
            'date' => $row['date']
        ];
    }
}

echo json_encode(['success' => true, 'logs' => $logs]);
exit();
?>
