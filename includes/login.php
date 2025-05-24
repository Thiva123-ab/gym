<?php 
session_start();
include('dbconnect.php');
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Fitzone Fitness</title>
    <link rel="icon" type="image/svg+xml" href="/gym/includes/image/logo.svg">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('Background/background.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #111;
        }
        .login-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #111;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .login-container button:hover {
            background-color: #333;
        }
        .login-container a {
            color: #007bff;
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
        .login-container p {
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Member Login</h2>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="log">Login</button>
        </form>
        <p><a href="../index.php">← Back to Home</a></p>
        <p>Admin or Staff? <a href="admin_staff_login.php">Login here</a></p>
        <p>Don't have an account? <a href="register.php">Register</a></p>

        <?php
        if (isset($_POST["log"])) {
            $usern = $_POST["username"];
            $passw = $_POST["password"];
            
            $sql = "SELECT * FROM users WHERE username=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $usern);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row && password_verify($passw, $row['pwd'])) {
                $_SESSION['user_id'] = $row['user_id']; 
                $_SESSION['username'] = $row['username']; 
                
                echo '<script>
                    window.location.href = "/gym/includes/Users/userpage.php";
                </script>';
            } else {
                echo "<p style='color: red;'>Wrong username or password.</p>";
            }
            $stmt->close();
        }
        $conn->close();
        ?>
    </div>
</body>
</html>