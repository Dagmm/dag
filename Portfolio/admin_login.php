
<?php
session_start();
require_once 'db_connection.php';
$db = new Database();
$conn = $db->conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && hash('sha256', $password) === $user['password']) {
        $_SESSION['admin'] = $user['username'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<form method="POST">
    <h2>Admin Login</h2>
    <?php if (!empty($error)) echo "<p>$error</p>"; ?>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
