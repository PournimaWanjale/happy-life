<?php
session_start();
require_once "config.php";

// Only logged-in users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard - Happy Life</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h2>Welcome, <?php echo $_SESSION['name']; ?> 🌸</h2>
    <nav>
      <a href="logout.php">Logout</a>
    </nav>
  </header>

  <section>
    <h3>💖 Your Emergency Contacts</h3>
    <?php
    $uid = $_SESSION['user_id'];
    $contacts = $conn->query("SELECT id, name, phone FROM contacts WHERE user_id = $uid");
    if ($contacts->num_rows > 0) {
        echo "<ul>";
        while ($row = $contacts->fetch_assoc()) {
            echo "<li>{$row['name']} - {$row['phone']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No contacts added yet.</p>";
    }
    ?>
    <p><a href="add_contact.html">➕ Add New Contact</a></p>
  </section>

  <section>
    <h3>🌈 Daily Motivation</h3>
    <p>“Every day may not be good, but there is something good in every day.”</p>
  </section>
</body>
</html>
