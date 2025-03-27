<?php
// login.php
session_start(); // Ensure session is started.
include 'config.php';

// If already logged in, redirect to the admin dashboard.
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare a statement to fetch the admin record.
    $stmt = $conn->prepare("SELECT id, password FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($admin_id, $stored_password);
        $stmt->fetch();

        // Compare the MD5 hash of the entered password with the stored MD5 hash.
        if (md5($password) === $stored_password) {
            $_SESSION['admin'] = true;
            $_SESSION['admin_id'] = $admin_id;
            header("Location: admin.php");
            exit;
        } else {
            $error = "Invalid credentials!";
        }
    } else {
        $error = "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f093fb, #f5576c);
            margin: 0;
            padding: 0;
        }

        .login-container {
            width: 30%;
            margin: 100px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
            position: relative;
        }

        .header-links {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .header-links a {
            display: inline-block;
            padding: 5px 10px;
            background: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            margin-left: 5px;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            margin: 10px;
            width: 80%;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background: #f5576c;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="header-links">
            <a href="index.php">Main Page</a>
        </div>
        <h1>Admin Login</h1>
        <?php if (isset($error)) {
            echo "<p class='error'>$error</p>";
        } ?>
        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>

</html>