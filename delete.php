<?php
// delete.php
include 'config.php';
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Invalid appointment ID.");
}
$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: admin.php");
exit;
?>