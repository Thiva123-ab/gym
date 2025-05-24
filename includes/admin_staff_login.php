<?php 
session_start();
include('dbconnect.php');
error_reporting(0);

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $user_type = $_POST["user_type"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND role=?");
    $stmt->bind_param("ss", $username, $user_type);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($password, $row['pwd'])) {
        $_SESSION['user_id'] = $row['user_id']; 
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        if ($row['role'] == 'staff') {
            $_SESSION['staff_id'] = $row['user_id'];
        }

        if ($row['role'] == 'admin') {
            echo '<script> window.location.href = "admin/dashboard.php";</script>';
        } elseif ($row['role'] == 'staff') {
            echo '<script> window.location.href = "staff/staff_page.php";</script>';
        }
    } else {
        $error_message = "Invalid username, password, or user role.";
    }

    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Admin & Staff</title>
    <link rel="icon" type="image/svg+xml" href="/gym/includes/image/logo.svg">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            background: url('Background/background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 2rem 3rem;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            margin-bottom: 1.5rem;
            color: #333;
            text-align: center;
        }
        label {
            display: block;
            margin: 0.5rem 0 0.25rem;
            font-weight: 600;
        }
        input, select {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 1rem;
            transition: border-color 0.3s;
        }
        input:focus, select:focus {
            border-color: #007BFF;
            outline: none;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #007BFF;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-link {
            margin-top: 1rem;
            text-align: center;
        }
        .back-link a {
            color: #007BFF;
            text-decoration: none;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin & Staff Login</h2>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="user_type">Login As</label>
            <select name="user_type" id="user_type" required>
                <option value="staff">Staff</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit" name="login">Login</button>
        </form>

        <div class="back-link">
            ← <a href="../index.php">Back to Home</a>
        </div>
    </div>
</body>
</html>
