<?php
session_start();
require_once "config.php";

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Happy Life</title>
  <link rel="stylesheet" href="style.css">
  <style>
      table {
          border-collapse: collapse;
          width: 100%;
          margin-bottom: 30px;
      }
      th, td {
          border: 1px solid #ccc;
          padding: 8px;
          text-align: left;
      }
      th {
          background-color: #ffeb3b;
      }
      h2, h3 {
          color: #ff6b6b;
      }
      a.logout {
          float: right;
          color: white;
          background-color: #ff6b6b;
          padding: 6px 12px;
          border-radius: 8px;
          text-decoration: none;
      }
      a.logout:hover {
          background-color: #ffb347;
      }
  </style>
</head>
<body>
  <header>
    <h2>Welcome, Admin <?php echo $_SESSION['name']; ?> 👑</h2>
    <a href="logout.php" class="logout">Logout</a>
  </header>

  <!-- Users Table -->
  <section>
    <h3>📊 Users List</h3>
<?php
$users = $conn->query("SELECT user_id, name, email, role FROM users");
if ($users->num_rows > 0) {
    echo "<table border='1'><tr><th>#</th><th>Name</th><th>Email</th><th>Role</th></tr>";
    $counter = 1;
    while ($row = $users->fetch_assoc()) {
        echo "<tr>
                <td>{$counter}</td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['role']}</td>
              </tr>";
        $counter++;
    }
    echo "</table>";
} else {
    echo "<p>No users found.</p>";
}
?>

  </section>

  <!-- Contacts Table -->
  <section>
    <h3>📌 Emergency Contacts</h3>
    <?php
    $contacts = $conn->query("
        SELECT c.contact_id, u.name AS user_name, c.name AS contact_name, c.phone
        FROM contacts c
        JOIN users u ON c.user_id = u.user_id
    ");
    if ($contacts && $contacts->num_rows > 0) {
        echo "<table>
                <tr>
                  <th>ID</th>
                  <th>User Name</th>
                  <th>Contact Name</th>
                  <th>Phone</th>
                </tr>";
        while ($row = $contacts->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['contact_id']}</td>
                    <td>{$row['user_name']}</td>
                    <td>{$row['contact_name']}</td>
                    <td>{$row['phone']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No contacts found.</p>";
    }
    ?>
  </section>

    <!-- Mood Logs Table -->
  <section>
    <h3>📊 Mood Logs</h3>
    <?php
    $moods = $conn->query("
        SELECT m.mood_id, u.name AS user_name, u.email, m.mood, m.note, m.date
        FROM mood_logs m
        JOIN users u ON m.user_id = u.user_id
        ORDER BY m.date DESC
    ");
    if ($moods && $moods->num_rows > 0) {
        echo "<table>
                <tr>
                  <th>ID</th>
                  <th>User Name</th>
                  <th>Email</th>
                  <th>Mood</th>
                  <th>Note</th>
                  <th>Date</th>
                </tr>";
        while ($row = $moods->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['mood_id']}</td>
                    <td>{$row['user_name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['mood']}</td>
                    <td>".($row['note'] ?: '—')."</td>
                    <td>{$row['date']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No mood logs found.</p>";
    }
    ?>
  </section>

  </section>
</body>
</html>
