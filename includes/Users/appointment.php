<?php
session_start();
include('dbconnect.php');
error_reporting(0);

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['username']);
$isLoggedId = isset($_SESSION['user_id']);

if (!$isLoggedIn) {
    echo '<script> 
            alert("Please log in to your account to book a training session.");
            window.location.href = "login.php";
          </script>';
    exit;
}
?>
<?php
    if (isset($_POST['appointment'])) {
        // Validate and sanitize input data
        $full_name = htmlspecialchars($_POST['full_name']);
        $email = htmlspecialchars($_POST['email']);
        $phone = htmlspecialchars($_POST['phone']);
        $trainer_id = (int) $_POST['trainer_id'];
        $session_type = htmlspecialchars($_POST['session_type']);
        $session_date = htmlspecialchars($_POST['session_date']);
        $session_time = htmlspecialchars($_POST['session_time']);
        $status = 'pending';

        // Insert appointment details into appointments table
        $sql = "INSERT INTO appointments 
                    (user_id, trainer_id, session_type, appointment_date, appointment_time, status)
                VALUES 
                    ('".$_SESSION['user_id']."', '$trainer_id', '$session_type', '$session_date', '$session_time', '$status')";

        if ($conn->query($sql) === TRUE) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?booked=1");
            exit;
        } else {
            echo "<p class='error'>Error: " . $conn->error . "</p>";
        }
        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Training Session</title>
    <link rel="icon" type="image/svg+xml" href="/gym/includes/image/logo.svg">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            background: url('/gym/includes/Background/background2.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 28px;
            color: #333;
        }

        h2 {
            font-size: 24px;
            color: #444;
            margin-bottom: 20px;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-container input, 
        .form-container select, 
        .form-container button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 16px;
        }

        .form-container input:focus, 
        .form-container select:focus, 
        .form-container button:focus {
            outline: none;
            border-color: #007BFF;
        }

        .form-container label {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .form-container button {
            background-color: #28a745;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #218838;
        }

        .form-container .error {
            color: red;
            margin-top: 10px;
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .login-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: rgba(10, 86, 103, 0.9);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .login-link a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link:hover {
            background-color: rgba(15, 71, 84, 0.9);
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .form-container {
                padding: 20px;
            }
        }
        .header-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            background:rgba(88, 88, 88, 0.51);
            padding: 18px 30px;
            border-radius: 0 0 20px 20px;
            margin-bottom: 30px;
            position: relative;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .header-bar .back-btn {
            position: absolute;
            left: 30px;
            top: 50%;
            transform: translateY(-50%);
            background-color:rgba(10, 86, 103, 0.9);
            color: #fff;
            font-weight: bold;
            border-radius: 6px;
            padding: 10px 20px;
            text-decoration: none;
            transition: background 0.2s;
        }
        .header-bar .back-btn:hover {
            background-color:rgba(9, 57, 68, 0.9);
        }
        .header-title {
            flex: 1;
            text-align: center;
            color: #fff;
            font-size: 1.7rem;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-bar">
        <a href="/gym/includes/Users/userpage.php" class="button back-btn">&larr; Back to Home</a>
        <div class="header-title">
            Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
        </div>
        <div class="login-link">
            <a href="viewappointment.php">View My Sessions</a>
        </div>
    </div>
    <?php if (isset($_GET['booked'])): ?>
        <div class="success-message" style="background-color:#e6ffed;color:#2c662d;padding:15px 20px;border-left:6px solid #52c41a;border-radius:8px;margin-bottom:25px;font-weight:500;">
            ✅ Session booked successfully!
        </div>
    <?php endif; ?>

    <!-- Appointment Booking Form -->
    <div class="form-container">
        <h2>Book a Training Session</h2>
        <form action="" method="POST">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="trainer">Select Trainer:</label>
            <select id="trainer" name="trainer_id" required>
                <option value="">-- Choose Trainer --</option>
                <?php
                    $query = "SELECT trainer_id, name FROM trainers";
                    $result = $conn->query($query);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['trainer_id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No trainers available</option>";
                    }
                ?>
            </select>

            <label for="session_type">Training Session Type:</label>
            <select id="session_type" name="session_type" required>
                <option value="">-- Choose Session Type --</option>
                <option value="Weight Training">Weight Training</option>
                <option value="Cardio">Cardio</option>
                <option value="Yoga">Yoga</option>
                <option value="Zumba">Zumba</option>
            </select>

            <label for="date">Session Date:</label>
            <input type="date" id="date" name="session_date" required>

            <label for="time">Session Time:</label>
            <input type="time" id="time" name="session_time" required>

            <button type="submit" name="appointment">Submit Session</button>
        </form>
    </div>

    
<div style="margin-top: 80px;" width="100%">
    <?php include __DIR__ . '/../footer.php'; ?>
</div>
</body>
</html>