<?php
// bulk_delete.php
include 'config.php';
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_ids'])) {
    $ids = $_POST['delete_ids'];
    // Ensure the ids are integers to prevent SQL injection.
    $id_list = implode(',', array_map('intval', $ids));
    $sql = "DELETE FROM appointments WHERE id IN ($id_list)";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
        exit;
    } else {
        echo "Error deleting records: " . $conn->error;
    }
} else {
    header("Location: admin.php");
    exit;
}
?>