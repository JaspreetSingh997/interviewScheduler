<?php
// edit.php
include 'config.php';
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Invalid appointment ID.");
}
$id = $_GET['id'];

// Fetch appointment details.
$sql = "SELECT * FROM appointments WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("Appointment not found.");
}
$appointment = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $candidate_name = $_POST['candidate_name'];
    $candidate_email = $_POST['candidate_email'];
    $candidate_number = $_POST['candidate_phone]'];

    $stmt = $conn->prepare("UPDATE appointments SET candidate_name = ?, candidate_email = ?, candidate_phone = ? WHERE id = ?");
    $stmt->bind_param("sssi", $candidate_name, $candidate_email, $candidate_number, $id);

    if ($stmt->execute()) {
        header("Location: admin.php");
        exit;
    } else {
        $error = "Error updating appointment: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Edit Appointment</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #a1c4fd, #c2e9fb);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 40%;
            margin: 60px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="text"],
        input[type="email"] {
            padding: 10px;
            margin: 10px;
            width: 80%;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background: #4caf50;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Edit Appointment</h1>
        <?php if (isset($error)) {
            echo "<p class='error'>$error</p>";
        } ?>
        <form method="POST" action="">
            <label for="candidate_name">Candidate Name:</label>
            <input type="text" id="candidate_name" name="candidate_name"
                value="<?php echo $appointment['candidate_name']; ?>" required>
            <label for="candidate_email">Candidate Email:</label>
            <input type="email" id="candidate_email" name="candidate_email"
                value="<?php echo $appointment['candidate_email']; ?>" required>
            <label for="candidate_phone">Candidate Number:</label>
            <input type="text" id="candidate_number" name="candidate_phone"
                value="<?php echo $appointment['candidate_phone']; ?>" required>
            <input type="submit" value="Update Appointment">
        </form>
    </div>
</body>

</html>