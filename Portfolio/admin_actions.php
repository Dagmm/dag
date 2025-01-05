<?php
require_once 'db.php';
require_once 'SessionManager.php';
require_once 'CRUDOperations.php';


SessionManager::startSession();
SessionManager::checkSessionTimeout();


if (!SessionManager::isAdmin()) {
    echo json_encode(["status" => "error", "message" => "You are not authorized to perform this action"]);
    exit();
}

$db = new DatabaseConnection();
$conn = $db->connect();
$crud = new CRUDOperations($conn);


if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        SessionManager::regenerateSession();
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['username'] = $username;
        echo "<script>alert('Login successful'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }
}


$action = $_REQUEST['action'] ?? null;
try {
    switch ($action) {
        case 'create':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $table = $_POST['table'];
                $data = json_decode($_POST['data'], true);

                if (!$crud->validateTable($table)) {
                    throw new Exception("Invalid table name");
                }

                $response = $crud->createRecord($table, $data);
                echo json_encode($response);
            }
            break;

        case 'update':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $table = $_POST['table'];
                $id = $_POST['id'];
                $data = json_decode($_POST['data'], true);

                if (!$crud->validateTable($table)) {
                    throw new Exception("Invalid table name");
                }

                $response = $crud->updateRecord($table, $id, $data);
                echo json_encode($response);
            }
            break;

        case 'delete':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $table = $_POST['table'];
                $id = $_POST['id'];

                if (!$crud->validateTable($table)) {
                    throw new Exception("Invalid table name");
                }

                $response = $crud->deleteRecord($table, $id);
                echo json_encode($response);
            }
            break;

        case 'read':
            $table = $_GET['table'];
            $id = $_GET['id'] ?? null;

            if (!$crud->validateTable($table)) {
                throw new Exception("Invalid table name");
            }

            $records = $crud->readRecord($table, $id);
            echo json_encode(["status" => "success", "data" => $records]);
            break;

        default:
            throw new Exception("Invalid action");
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>

