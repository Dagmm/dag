<?php
session_start();
require_once 'db_connection.php';
$db = new Database();
$conn = $db->conn;

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Manage Messages</h2>
<?php foreach ($messages as $message): ?>
    <div>
        <h3><?= htmlspecialchars($message['full_name']) ?> (<?= htmlspecialchars($message['email']) ?>)</h3>
        <p><strong>Subject:</strong> <?= htmlspecialchars($message['subject']) ?></p>
        <p><?= htmlspecialchars($message['message']) ?></p>
        <small>Sent on: <?= $message['created_at'] ?></small>
        <hr>
    </div>
<?php endforeach; ?>
