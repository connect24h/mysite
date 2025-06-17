<?php
function get_db_connection() {
    $db_path = __DIR__ . '/../database/board.sqlite';
    try {
        $pdo = new PDO('sqlite:' . $db_path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // In a real application, you would log this error and show a user-friendly message.
        error_log("Database connection error: " . $e->getMessage());
        die("Database connection failed. Please try again later.");
    }
}
?>
