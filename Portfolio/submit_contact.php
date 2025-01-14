

<?php

require_once 'db_connection.php';
$db = new Database();
$conn = $db->conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phoneNumber = trim($_POST['phone_number']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    $stmt = $conn->prepare("INSERT INTO contact_messages (full_name, email, phone_number, subject, message) VALUES (:full_name, :email, :phone_number, :subject, :message)");
    $stmt->bindParam(':full_name', $fullName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone_number', $phoneNumber);
    $stmt->bindParam(':subject', $subject);
    $stmt->bindParam(':message', $message);

    if ($stmt->execute()) {
        echo "<p>Thank you! Your message has been sent successfully.</p>";
    } else {
        echo "<p>Something went wrong. Please try again.</p>";
    }
}

header("Location: index.php#contact");
exit();

?>
