<?php
include 'config.php';

// Set the default timezone (adjust as needed)
date_default_timezone_set('America/New_York');

// Determine current date and time.
$today = date('Y-m-d');
$now = new DateTime();

// We'll always allow selecting today.
$minDate = $today;

// Get the selected date from GET or default to today.
$date = isset($_GET['date']) ? $_GET['date'] : $today;
if ($date < $minDate) {
    $date = $minDate;
}

// If the selected date is today, use current time for comparison.
$currentTime = ($date === $today) ? new DateTime() : null;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Interview Scheduler</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f6d365, #fda085);
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
        }

        .admin-login {
            padding: 10px 20px;
            background: #ff7e67;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1,
        h2 {
            text-align: center;
        }

        form {
            text-align: center;
            margin-bottom: 30px;
        }

        input[type="date"] {
            padding: 8px;
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
            margin-left: 10px;
        }

        .slots {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .slot {
            margin: 10px;
            padding: 15px;
            border-radius: 5px;
            min-width: 100px;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Disabled slots (booked or past) */
        .slot.disabled {
            background-color: #e0e0e0;
            color: #777;
            cursor: not-allowed;
        }

        /* Available slots styling */
        .slot.available {
            background-color: #a0d468;
            color: #fff;
            transition: transform 0.2s;
        }

        .slot.available:hover {
            transform: scale(1.05);
        }

        .slot.available a {
            color: inherit;
            text-decoration: none;
            display: block;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Interview Scheduler</h1>
        <?php
        // Display the appropriate admin button.
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            echo "<a class='admin-login' href='admin.php'>Dashboard</a>";
        } else {
            echo "<a class='admin-login' href='login.php'>Admin Login</a>";
        }
        ?>
    </div>
    <div class="container">
        <form method="GET" action="index.php">
            <label for="date">Select Date:</label>
            <input type="date" id="date" name="date" value="<?php echo $date; ?>" min="<?php echo $minDate; ?>">
            <input type="submit" value="Show Slots">
        </form>
        <h2>Available Slots for <?php echo $date; ?></h2>
        <div class="slots">
            <?php
            // Define work hours and 15-minute interval.
            $start = new DateTime('09:00');
            $end = new DateTime('19:00');
            $interval = new DateInterval('PT15M');

            // Retrieve booked slots for the selected date.
            $sql = "SELECT appointment_time FROM appointments WHERE appointment_date = '$date'";
            $result = $conn->query($sql);
            $booked_slots = [];
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $booked_slots[] = date('H:i', strtotime($row['appointment_time']));
                }
            }

            // Loop through each time slot.
            for ($time = clone $start; $time < $end; $time->add($interval)) {
                $slot = $time->format('H:i');
                // If selected date is today and slot time is in the past, mark as disabled.
                if ($date === $today && $time < $currentTime) {
                    echo "<div class='slot disabled'>$slot</div>";
                } else if (in_array($slot, $booked_slots)) {
                    echo "<div class='slot disabled'>$slot</div>";
                } else {
                    echo "<div class='slot available'><a href='book.php?date=$date&time=$slot'>$slot</a></div>";
                }
            }
            ?>
        </div>
    </div>
</body>

</html>