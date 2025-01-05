<?php

class SessionManager {
    public static function startSession() {
        session_start();
    }

    public static function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    public static function checkSessionTimeout($timeoutDuration = 1800) {
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeoutDuration)) {
            session_destroy();
            header("Location: admin_login.php");
            exit();
        }
        $_SESSION['last_activity'] = time();
    }

    public static function regenerateSession() {
        session_regenerate_id(true);
    }

    public static function logout() {
        session_destroy();
        header("Location: admin_login.php");
        exit();
    }
}
?>

