<?php
session_start();
include '../dbconnect.php';

// Check if the admin is logged in
if (!isset($_SESSION['username'])) {
    echo '<script> 
            alert("Please log in to access the admin dashboard.");
            window.location.href = "../login.php";
          </script>';
    exit;
}

// Fetch trainers for dropdown
$query = "SELECT trainer_id, name FROM trainers";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Workout Class - FitZone Fitness</title>
    <link rel="icon" type="image/svg+xml" href="/gym/includes/image/logo.svg">
    <style>
        :root {
            --primary-color: #ff4757;
            --secondary-color: #40E0D0;
            --bg-dark: #222;
            --text-light: #fff;
            --text-dark: #333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: url('/gym/includes/image/pexels-victorfreitas-841130.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            color: var(--text-dark);
        }

        .container {
            display: flex;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .sidebar {
            width: 250px;
            background: var(--bg-dark);
            color: var(--text-light);
            padding: 20px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
        }

        .sidebar h2 {
            font-size: 24px;
            margin-bottom: 30px;
            text-align: center;
            color: var(--secondary-color);
        }

        .sidebar a {
            display: block;
            color: var(--text-light);
            text-decoration: none;
            padding: 15px;
            margin: 5px 0;
            border-radius: 8px;
            font-size: 16px;
            transition: background 0.3s, transform 0.2s;
        }

        .sidebar a:hover {
            background: var(--primary-color);
            transform: translateX(5px);
        }

        .sidebar .logout {
            margin-top: auto;
            background: #444;
        }

        .sidebar .logout:hover {
            background: #ff6b6b;
        }

        .main-content {
            margin-left: 270px;
            padding: 40px;
            flex-grow: 1;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 32px;
            color: var(--text-dark);
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
            transition: transform 0.3s;
        }

        .form-container:hover {
            transform: translateY(-5px);
        }

        .form-container label {
            display: block;
            font-size: 16px;
            color: var(--text-dark);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="datetime-local"],
        .form-container select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-container input:focus,
        .form-container select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 8px rgba(255, 71, 87, 0.3);
        }

        .form-container select {
            appearance: none;
            background: url('/gym/includes/image/pexels-victorfreitas-841130.jpg') no-repeat right 12px center;
            background-size: 12px;
            padding-right: 30px;
        }

        .form-container button {
            width: 100%;
            padding: 14px;
            background: var(--primary-color);
            color: var(--text-light);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        .form-container button:hover {
            background: #e84148;
            transform: scale(1.02);
        }

        .form-container .cancel-btn {
            display: block;
            text-align: center;
            padding: 12px;
            background: #666;
            color: var(--text-light);
            text-decoration: none;
            border-radius: 8px;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .form-container .cancel-btn:hover {
            background: #555;
        }

        .error {
            background: #ff6b6b;
            color: var(--text-light);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        .footer {
            color: #fff;
            text-align: center;
            padding: 20px 0;
            width: 100%;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: static;
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
                padding: 10px;
            }

            .sidebar h2 {
                display: none;
            }

            .sidebar a {
                margin: 5px;
                padding: 10px;
                font-size: 14px;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .form-container {
                max-width: 100%;
            }
        }
        .sidebar {
        width: 250px;
        background: var(--bg-dark);
        color: var(--text-light);
        padding: 20px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
    }

    .sidebar h2 {
        font-size: 24px;
        margin-bottom: 30px;
        text-align: center;
    }

    .sidebar a {
        display: block;
        color: var(--text-light);
        text-decoration: none;
        padding: 15px;
        margin: 5px 0;
        border-radius: 8px;
        font-size: 16px;
        transition: background 0.3s, transform 0.2s;
    }

    .sidebar a:hover {
        background: var(--primary-color);
        transform: translateX(5px);
    }

    .sidebar .logout {
        margin-top: auto;
        background: #444;
    }

    .sidebar .logout:hover {
        background: #ff6b6b;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: static;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            padding: 10px;
        }

        .sidebar h2 {
            display: none;
        }

        .sidebar a {
            margin: 5px;
            padding: 10px;
            font-size: 14px;
        }
    }
    </style>
</head>
<body>

    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <h2>Admin Dashboard</h2>
            <a href="dashboard.php">Dashboard</a>
            <a href="managetrainers.php">Manage Trainers</a>
            <a href="manage_memberships.php">Manage Memberships</a>
            <a href="manage_user.php">View Users</a>
            <a href="../logout.php" class="logout">Logout</a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Add Workout Class</h1>
            </div>

            <!-- Add Class Form -->
            <div class="form-container">
                <form action="process_add_class.php" method="POST" onsubmit="return validateForm()">
                    <label for="class_name">Class Name:</label>
                    <input type="text" id="class_name" name="class_name" required>

                    <label for="trainer_id">Trainer:</label>
                    <select id="trainer_id" name="trainer_id" required>
                        <option value="">Select Trainer</option>
                        <?php while ($trainer = $result->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($trainer['trainer_id']); ?>">
                                <?php echo htmlspecialchars($trainer['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <label for="schedule">Schedule:</label>
                    <input type="datetime-local" id="schedule" name="schedule" required>

                    <label for="limit">Limit:</label>
                    <input type="number" id="limit" name="limit" min="1" required>

                    <button type="submit">Add Class</button>
                    <a href="dashboard.php" class="cancel-btn">Cancel</a>
                </form>
            </div>

            <div class="footer">
                <?php include __DIR__ . '/../footer.php'; ?>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            let className = document.getElementById('class_name').value.trim();
            let trainerId = document.getElementById('trainer_id').value;
            let schedule = document.getElementById('schedule').value;
            let limit = document.getElementById('limit').value;

            if (className.length < 2) {
                alert('Class name must be at least 2 characters.');
                return false;
            }
            if (!trainerId) {
                alert('Please select a trainer.');
                return false;
            }
            if (!schedule) {
                alert('Please select a schedule.');
                return false;
            }
            if (limit < 1) {
                alert('Limit must be at least 1.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>