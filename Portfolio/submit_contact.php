<?php
session_start();
require_once 'db.php'; 

class Database {
    private $conn;

    public function __construct($dsn, $user, $pass) {
        try {
            $this->conn = new PDO($dsn, $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }

    public function execute($stmt) {
        return $stmt->execute();
    }
}


class ContactForm {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }


    private function sanitizeInput($data) {
        return htmlspecialchars(trim($data));
    }


    private function sendEmailNotification($name, $email, $phone, $subject, $message) {
        $to = "your-email@example.com"; 
        $subject = "New Contact Form Submission: $subject";
        $body = "
            You have received a new message from your portfolio contact form:
            
            Name: $name
            Email: $email
            Phone: $phone
            Subject: $subject
            Message: $message
        ";

        $headers = "From: $email" . "\r\n" .
                   "Reply-To: $email" . "\r\n" .
                   "Content-Type: text/plain; charset=UTF-8";
        
        return mail($to, $subject, $body, $headers);
    }


    public function insertContactData($fullName, $email, $phoneNumber, $subject, $message) {
        try {
            $sql = "INSERT INTO contacts (full_name, email, phone_number, subject, message, created_at) 
                    VALUES (:full_name, :email, :phone_number, :subject, :message, NOW())";
            $stmt = $this->db->prepare($sql);


            $stmt->bindParam(':full_name', $fullName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone_number', $phoneNumber);
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':message', $message);


            return $this->db->execute($stmt);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
            return false;
        }
    }


    public function validateForm($email, $message) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email format.";
            return false;
        }
        if (empty($message)) {
            $_SESSION['error'] = "Message cannot be empty.";
            return false;
        }
        return true;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dsn = "mysql:host=localhost;dbname=portfolio;charset=utf8";
    $user = "root";
    $pass = "";


    $db = new Database($dsn, $user, $pass);
    $contactForm = new ContactForm($db);


    $fullName = $contactForm->sanitizeInput($_POST['full_name']);
    $email = filter_var($contactForm->sanitizeInput($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phoneNumber = $contactForm->sanitizeInput($_POST['phone_number']);
    $subject = $contactForm->sanitizeInput($_POST['subject']);
    $message = $contactForm->sanitizeInput($_POST['message']);


    if ($contactForm->validateForm($email, $message)) {

        if ($contactForm->insertContactData($fullName, $email, $phoneNumber, $subject, $message)) {

            if ($contactForm->sendEmailNotification($fullName, $email, $phoneNumber, $subject, $message)) {
                $_SESSION['success'] = "Your message has been sent successfully!";
            } else {
                $_SESSION['error'] = "Failed to send email notification.";
            }
        } else {
            $_SESSION['error'] = "Failed to save your message. Please try again later.";
        }
    }


    header("Location: #contact");
    exit();
}
?>

