<?php
session_start();
include('dbconnect.php');
error_reporting(0);

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['username']);
$isLoggedId = isset($_SESSION['user_id']);

// Handle the delete action
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    // Prepare and execute the DELETE query (using prepared statement for security)
    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();

    // Redirect to the page
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Appointments</title>
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

        h2 {
            font-size: 24px;
            color: #fff;
            margin-bottom: 20px;
        }

        .table-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow-x: auto; /* Responsive table */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .action-buttons a {
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            margin-right: 5px;
            display: inline-block;
        }

        .action-buttons .delete {
            background-color: #dc3545;
            color: white;
        }

        .action-buttons .delete:hover {
            background-color: #c82333;
        }

        .action-buttons .update {
            background-color: #ffc107;
            color: #333;
        }

        .action-buttons .update:hover {
            background-color: #e0a800;
        }

        .no-appointments {
            text-align: center;
            font-size: 18px;
            color: #666;
            padding: 20px;
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

            th, td {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <?php if ($isLoggedIn): ?>
                <div class="header-bar">
                    <a href="/gym/includes/Users/userpage.php" class="button back-btn">&larr; Back to Home</a>
                    <div class="header-title">
                        Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                    </div>
                </div>
                <h2 style="background:hsla(211, 100.00%, 50.00%, 0.66); color: #000000; padding: 12px 0; border-radius: 8px; text-align:center;">
                    Your Appointments For Sessions
                </h2>

                <div class="table-container">
                    <?php
                    // Fetch appointments for the logged-in user (using prepared statement)
                    $stmt = $conn->prepare("SELECT appointments.id, trainers.name AS trainer_name, appointments.appointment_date, appointments.appointment_time, appointments.session_type, appointments.status 
                                            FROM appointments 
                                            JOIN trainers ON appointments.trainer_id = trainers.trainer_id 
                                            WHERE appointments.user_id = ?");
                    $stmt->bind_param("i", $_SESSION['user_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Appointment ID</th>
                                    <th>Trainer</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Session</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['trainer_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                                        <td><?php echo htmlspecialchars($row['appointment_time']); ?></td>
                                        <td><?php echo htmlspecialchars($row['session_type']); ?></td>
                                        <td><?php echo ucfirst(htmlspecialchars($row['status'])); ?></td>
                                        <td class="action-buttons">
                                            <a href="?delete_id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this appointment?');">Delete</a>
                                            <a href="update_appointment.php?update_id=<?php echo $row['id']; ?>" class="update">Update</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="no-appointments">You have no appointments scheduled.</p>
                    <?php endif; ?>
                    <?php $stmt->close(); ?>
                </div>
            <?php else: ?>
                <h2>Please log in to view your appointments.</h2>
            <?php endif; ?>
        </div>

    </div>

    <?php $conn->close(); ?>
<div style="margin-top: 95px;">
    <?php include __DIR__ . '/../footer.php'; ?>
</div>
</body>
</html>