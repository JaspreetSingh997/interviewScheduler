<?php
include 'config.php';
date_default_timezone_set('America/New_York');

$today = date('Y-m-d');
$now = new DateTime();
$endTimeToday = new DateTime($today . ' 17:00');
$minDate = ($now > $endTimeToday) ? date('Y-m-d', strtotime('+1 day')) : $today;

if (!isset($_GET['date']) || !isset($_GET['time'])) {
    die("Invalid access.");
}

$date = $_GET['date'];
$time = $_GET['time'];

// Prevent booking if the date in the URL is earlier than allowed.
if ($date < $minDate) {
    die("Booking is not allowed for this date.");
}

// Additional check: if the selected date is today, ensure the slot time is in the future.
if ($date === $today) {
    // Create a DateTime from the provided date and time.
    $slotTime = DateTime::createFromFormat('Y-m-d H:i', "$date $time");
    if (!$slotTime) {
        die("Invalid time format.");
    }
    // If the slot time is less than or equal to the current time, block booking.
    if ($slotTime <= $now) {
        die("Booking is not allowed for a past time slot.");
    }
}

// Process the booking only on POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $candidate_name = $_POST['candidate_name'];
    $candidate_email = $_POST['candidate_email'];
    $candidate_number = $_POST['candidate_phone'];

    // Check if this email has already booked an appointment.
    $stmt_check = $conn->prepare("SELECT id FROM appointments WHERE candidate_email = ?");
    $stmt_check->bind_param("s", $candidate_email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $error = "This email has already booked an appointment.";
    } else {
        $stmt = $conn->prepare("INSERT INTO appointments (appointment_date, appointment_time, candidate_name, candidate_email, candidate_phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $date, $time, $candidate_name, $candidate_email, $candidate_number);

        if ($stmt->execute()) {
            echo "<p>Appointment booked successfully!</p>";
            echo "<p><a href='index.php'>Back to Scheduler</a></p>";
            exit;
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f6d365, #fda085);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 40px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
        }

        .details {
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
            background: #ff7e67;
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
        <h1>Book Appointment</h1>
        <div class="details">
            <p>Date: <?php echo $date; ?></p>
            <p>Time: <?php echo $time; ?></p>
        </div>
        <?php if (isset($error)) {
            echo "<p class='error'>$error</p>";
        } ?>
        <form method="POST" action="">
            <label for="candidate_name">Your Name:</label>
            <input type="text" id="candidate_name" name="candidate_name" required>

            <label for="candidate_email">Your Email:</label>
            <input type="email" id="candidate_email" name="candidate_email" required>

            <label for="candidate_phone">Phone Number</label>
            <input type="text" id="candidate_number" name="candidate_phone" required>

            <input type="submit" value="Book Appointment">
        </form>
    </div>
</body>

</html>