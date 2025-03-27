<?php
// config.php
session_start();

$servername = "localhost";
$username = "root";      // Update if needed
$password = "";          // Update if needed
$dbname = "interview_scheduler";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>