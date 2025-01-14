

<?php
session_start();
require_once 'db_connection.php';


if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$db = new Database();
$conn = $db->conn;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'create') {
        $name = trim($_POST['name']);
        $feedback = trim($_POST['feedback']);

        $stmt = $conn->prepare("INSERT INTO testimonials (name, feedback) VALUES (:name, :feedback)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':feedback', $feedback);
        $stmt->execute();

    } elseif ($action === 'update') {
        $id = $_POST['id'];
        $name = trim($_POST['name']);
        $feedback = trim($_POST['feedback']);

        $stmt = $conn->prepare("UPDATE testimonials SET name = :name, feedback = :feedback WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':feedback', $feedback);
        $stmt->execute();

    } elseif ($action === 'delete') {
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    header("Location: manage_testimonials.php");
    exit();
}


$testimonials = $conn->query("SELECT * FROM testimonials ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Testimonials</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Manage Testimonials</h1>
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="manage_sections.php">Manage Sections</a>
        <a href="manage_projects.php">Manage Projects</a>
        <a href="logout.php">Logout</a>
    </nav>

    <h2>Add a New Testimonial</h2>
    <form method="POST">
        <input type="hidden" name="action" value="create">
        <input type="text" name="name" placeholder="Name" required>
        <textarea name="feedback" placeholder="Feedback" required></textarea>
        <button type="submit">Add Testimonial</button>
    </form>

    <h2>Existing Testimonials</h2>
    <?php foreach ($testimonials as $testimonial): ?>
        <form method="POST" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
            <input type="hidden" name="id" value="<?= $testimonial['id'] ?>">
            <input type="hidden" name="action" value="update">
            <input type="text" name="name" value="<?= htmlspecialchars($testimonial['name']) ?>" required>
            <textarea name="feedback" required><?= htmlspecialchars($testimonial['feedback']) ?></textarea>
            <button type="submit">Update</button>
            <button type="submit" name="action" value="delete">Delete</button>
        </form>
    <?php endforeach; ?>
</body>
</html>

