<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
        .signup-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .signup-container h2 {
            margin-bottom: 20px;
            color: #111;
        }
        .signup-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .signup-container button {
            width: 100%;
            padding: 12px;
            background-color: #111;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .signup-container button:hover {
            background-color: #333;
        }
        .signup-container a {
            color: #007bff;
            text-decoration: none;
        }
        .signup-container a:hover {
            text-decoration: underline;
        }
        .signup-container p {
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="signup-container">
        <h2>Sign Up</h2>
        <form name="myform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required><br>

            <label for="emailid">Email:</label>
            <input type="email" name="emailid" id="emailid" required><br>

            <label for="pwsd">Password:</label>
            <input type="password" name="pwsd" id="pwsd" required><br>

            <button type="submit" name="signup" id="submit">Sign Up</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
        <p>Admin or Staff? <a href="admin_staff_login.php">Login here</a></p>
        <p><---<a href="../index.php">Back to Home</a></p>
    </div>

    <?php
    session_start();
    session_regenerate_id(true);
    include('dbconnect.php');
    error_reporting(E_ALL);

    if (isset($_POST['signup'])) {
        $usern = trim($_POST['username']);
        $passw = trim($_POST['pwsd']);
        $email = trim($_POST['emailid']);
        $user_type = 'customer'; // Default user type

        // Hash the password before storing it in the database
        $hashed_password = password_hash($passw, PASSWORD_DEFAULT);

        // Prepare SQL query
        $check = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $check->bind_param("ss", $usern, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo '<script type="text/javascript">alert("Username or Email already exists. Please use a different one.");</script>';
            $check->close();
        } else {
            // Prepare SQL query
            $stmt = $conn->prepare("INSERT INTO users (username, pwd, email, role) VALUES (?, ?, ?, ?)");

            if (!$stmt) {
                die("Prepare failed: " . $conn->error); // Debugging output
            }

            $stmt->bind_param("ssss", $usern, $hashed_password, $email, $user_type);

            if ($stmt->execute()) {
                echo '<script type="text/javascript">alert("Registration successful. Now you can login");
                window.location="login.php";
                </script>';
            } else {
                echo "Error executing query: " . $stmt->error; // Debugging output
            }

            $stmt->close();
        }
    }

    $conn->close();
    ?>

</body>
</html>
