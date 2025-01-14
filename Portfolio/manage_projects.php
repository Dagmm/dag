

<?php

session_start();
require_once 'db_connection.php';
$db = new Database();
$conn = $db->conn;

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $imageUrl = trim($_POST['image_url']);
    $link = trim($_POST['link']);

    if ($_POST['action'] === 'create') {
        $stmt = $conn->prepare("INSERT INTO projects (title, description, image_url, link) VALUES (:title, :description, :image_url, :link)");
    } elseif ($_POST['action'] === 'update') {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE projects SET title = :title, description = :description, image_url = :image_url, link = :link WHERE id = :id");
        $stmt->bindParam(':id', $id);
    } elseif ($_POST['action'] === 'delete') {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM projects WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header("Location: manage_projects.php");
        exit();
    }

    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image_url', $imageUrl);
    $stmt->bindParam(':link', $link);
    $stmt->execute();
    header("Location: manage_projects.php");
    exit();
}

$projects = $conn->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Manage Projects</h2>
<form method="POST">
    <input type="text" name="title" placeholder="Project Title" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="text" name="image_url" placeholder="Image URL">
    <input type="text" name="link" placeholder="Project Link">
    <input type="hidden" name="action" value="create">
    <button type="submit">Add Project</button>
</form>

<h3>Existing Projects</h3>
<?php foreach ($projects as $project): ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $project['id'] ?>">
        <input type="text" name="title" value="<?= htmlspecialchars($project['title']) ?>">
        <textarea name="description"><?= htmlspecialchars($project['description']) ?></textarea>
        <input type="text" name="image_url" value="<?= htmlspecialchars($project['image_url']) ?>">
        <input type="text" name="link" value="<?= htmlspecialchars($project['link']) ?>">
        <input type="hidden" name="action" value="update">
        <button type="submit">Update</button>
        <button type="submit" name="action" value="delete">Delete</button>
    </form>
<?php endforeach; ?>

