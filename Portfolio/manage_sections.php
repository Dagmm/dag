
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
    $sectionName = trim($_POST['section_name']);
    $content = trim($_POST['content']);

    if ($_POST['action'] === 'create') {
        $stmt = $conn->prepare("INSERT INTO sections (section_name, content) VALUES (:section_name, :content)");
    } elseif ($_POST['action'] === 'update') {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE sections SET section_name = :section_name, content = :content WHERE id = :id");
        $stmt->bindParam(':id', $id);
    } elseif ($_POST['action'] === 'delete') {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM sections WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header("Location: manage_sections.php");
        exit();
    }

    $stmt->bindParam(':section_name', $sectionName);
    $stmt->bindParam(':content', $content);
    $stmt->execute();
    header("Location: manage_sections.php");
    exit();
}

$sections = $conn->query("SELECT * FROM sections")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Manage Sections</h2>
<form method="POST">
    <input type="text" name="section_name" placeholder="Section Name" required>
    <textarea name="content" placeholder="Content" required></textarea>
    <input type="hidden" name="action" value="create">
    <button type="submit">Add Section</button>
</form>

<h3>Existing Sections</h3>
<?php foreach ($sections as $section): ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $section['id'] ?>">
        <input type="text" name="section_name" value="<?= htmlspecialchars($section['section_name']) ?>">
        <textarea name="content"><?= htmlspecialchars($section['content']) ?></textarea>
        <input type="hidden" name="action" value="update">
        <button type="submit">Update</button>
        <button type="submit" name="action" value="delete">Delete</button>
    </form>
<?php endforeach; ?>
