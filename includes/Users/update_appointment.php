<?php
session_start();
include('dbconnect.php');
error_reporting(0);

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['username']);
$isLoggedId = isset($_SESSION['user_id']);

if (!$isLoggedIn || !$isLoggedId) {
    echo "<script>alert('Please log in to update an appointment.'); window.location.href = 'login.php';</script>";
    exit;
}

// Check if the user is trying to update an appointment
$appointment = null;
if (isset($_GET['update_id'])) {
    $update_id = (int)$_GET['update_id'];

    // Fetch the appointment details using a prepared statement
    $stmt = $conn->prepare("SELECT appointments.id, trainers.trainer_id, trainers.name AS trainer_name, appointments.appointment_date, appointments.appointment_time, appointments.session_type 
                            FROM appointments 
                            JOIN trainers ON appointments.trainer_id = trainers.trainer_id  
                            WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $update_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointment = $result->fetch_assoc();
    $stmt->close();

    if ($appointment) {
        // Handle form submission
        if (isset($_POST['update'])) {
            $trainer_id = (int)$_POST['trainer_id'];
            $appointment_date = htmlspecialchars($_POST['appointment_date']);
            $appointment_time = htmlspecialchars($_POST['appointment_time']);

            // Update the appointment using a prepared statement
            $update_stmt = $conn->prepare("UPDATE appointments 
                                          SET appointment_date = ?, 
                                              appointment_time = ?, 
                                              trainer_id = ? 
                                          WHERE id = ? AND user_id = ?");
            $update_stmt->bind_param("ssiii", $appointment_date, $appointment_time, $trainer_id, $update_id, $_SESSION['user_id']);
        }
    } else {
        echo "<script>alert('Appointment not found or you do not have permission to edit it.'); window.location.href = 'viewappointment.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('No appointment selected for update.'); window.location.href = 'viewappointment.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Appointment</title>
    <link rel="icon" type="image/svg+xml" href="/gym/includes/image/logo.svg">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: url('/gym/includes/Background/background2.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 32px;
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-container label {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
            display: block;
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

        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
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


        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
        <div class="header-bar">
            <a href="viewappointment.php" class="button back-btn">&larr; Go Back </a>
            <div class="header-title">
                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
            </div>
        </div>

        <div class="form-container">
            <form action="" method="POST">
                <label for="trainer">Trainer</label>
                <select id="trainer" name="trainer_id" required>
                    <option value="">-- Choose Trainer --</option>
                    <?php
                    // Fetch the list of trainers
                    $query = "SELECT trainer_id, name FROM trainers";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $selected = ($row['trainer_id'] == $appointment['trainer_id']) ? 'selected' : '';
                            echo "<option value='" . $row['trainer_id'] . "' $selected>" . htmlspecialchars($row['name']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No trainers available</option>";
                    }
                    ?>
                </select>

                <label for="appointment_date">Date</label>
                <input type="date" id="appointment_date" name="appointment_date" value="<?php echo htmlspecialchars($appointment['appointment_date']); ?>" required>

                <label for="appointment_time">Time</label>
                <input type="time" id="appointment_time" name="appointment_time" value="<?php echo htmlspecialchars($appointment['appointment_time']); ?>" required>

                <button type="submit" name="update">Update Appointment</button>
            </form>
        </div>
    <?php $conn->close(); ?>
 
<div style="margin-top: 105px;">
    <?php include __DIR__ . '/../footer.php'; ?>
</div>
</body>
</html>