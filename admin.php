<?php
// admin.php
include 'config.php';
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT * FROM appointments ORDER BY appointment_date, appointment_time";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #5ee7df, #b490ca);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 40px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            clear: both;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        a.button {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            margin: 0 5px;
        }

        a.edit {
            background-color: #4caf50;
        }

        a.delete {
            background-color: #f44336;
        }

        .header-links {
            overflow: auto;
            margin-bottom: 20px;
        }

        .header-links a {
            float: right;
            background-color: #333;
            padding: 10px 15px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }

        .bulk-action {
            margin-bottom: 20px;
            text-align: right;
        }

        .bulk-action input[type="submit"] {
            padding: 8px 15px;
            background: #f44336;
            border: none;
            color: white;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    <script>
        function toggleSelectAll(source) {
            var checkboxes = document.getElementsByName('delete_ids[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
</head>

<body>
    <div class="container">
        <div class="header-links">
            <a href="index.php">Main Page</a>
            <a href="logout.php">Logout</a>
        </div>
        <h1>Admin Dashboard</h1>

        <!-- Bulk Delete Form -->
        <form method="POST" action="bulk_delete.php"
            onsubmit="return confirm('Are you sure you want to delete the selected appointments?');">
            <div class="bulk-action">
                <input type="submit" value="Delete Selected">
            </div>

            <table>
                <tr>
                    <th><input type="checkbox" onclick="toggleSelectAll(this)"></th>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Candidate Name</th>
                    <th>Candidate Email</th>
                    <th>Candidate Phone</th>
                    <th>Actions</th>
                </tr>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='delete_ids[]' value='" . $row['id'] . "'></td>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['appointment_date'] . "</td>";
                        echo "<td>" . date('H:i', strtotime($row['appointment_time'])) . "</td>";
                        echo "<td>" . $row['candidate_name'] . "</td>";
                        echo "<td>" . $row['candidate_email'] . "</td>";
                        echo "<td>" . $row['candidate_phone'] . "</td>";
                        echo "<td>
                      <a class='button edit' href='edit.php?id=" . $row['id'] . "'>Edit</a>
                      <a class='button delete' href='delete.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure?');\">Delete</a>
                    </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No appointments scheduled.</td></tr>";
                }
                ?>
            </table>
        </form>
    </div>
</body>

</html>