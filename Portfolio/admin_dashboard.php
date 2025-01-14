<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['admin']) ?>!</h1>
    <nav>
        <a href="manage_sections.php">Manage Sections</a>
        <a href="manage_projects.php">Manage Projects</a>
        <a href="manage_testimonials.php">Manage Testimonials</a>
        <a href="manage_messages.php">View Messages</a>
        <a href="logout.php">Logout</a>
    </nav>
</body>
</html>
