<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['username'])) {
    echo '<script> 
            alert("Please log in to access the admin dashboard.");
            window.location.href = "/gym/includes/login.php";
          </script>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FitZone Fitness</title>
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
            background: url('/gym/includes/background/background2.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: var(--text-dark);
        }

        .container {
            display: flex;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            flex-grow: 1;
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
        }

        .header h1 {
            font-size: 32px;
            color: var(--text-dark);
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: var(--text-dark);
        }

        .card p {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }

        .card a {
            display: inline-block;
            background: var(--primary-color);
            color: var(--text-light);
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }

        .card a:hover {
            background: #e84148;
        }

        .footer {
            color: #fff;
            text-align: center;
            padding: 20px 0;
            width: 100%;
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

            .dashboard-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <h2>Admin Dashboard</h2>
            <a href="managetrainers.php">Manage Trainers</a>
            <a href="Add_new_class.php">Add Classes</a>
            <a href="manage_memberships.php">Manage Memberships</a>
            <a href="manage_user.php">View Users</a>
            <a href="../../includes/logout.php" class="logout">Logout</a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>!</h1>
            </div>

            <div class="dashboard-cards">
                <div class="card">
                    <h3>Manage Trainers</h3>
                    <p>Add, edit, or remove trainers from the system.</p>
                    <a href="managetrainers.php">Go to Trainers</a>
                </div>
                <div class="card">
                    <h3>Add Classes</h3>
                    <p>Create new fitness classes for users to join.</p>
                    <a href="Add_new_class.php">Add a Class</a>
                </div>
                <div class="card">
                    <h3>Manage Memberships</h3>
                    <p>Update membership plans and pricing.</p>
                    <a href="manage_memberships.php">Manage Plans</a>
                </div>
                <div class="card">
                    <h3>View Users</h3>
                    <p>Review and manage users</p>
                    <a href="manage_user.php">View Users</a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <?php include __DIR__ . '/../footer.php'; ?>
    </div>
</body>
</html>