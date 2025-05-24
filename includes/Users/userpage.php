<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "gym");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email, role FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "No user found.";
    exit();
}

$stmt->close();

// Fetch membership details
$membership_sql = "
    SELECT um.id, m.name, m.price, m.duration, m.description, um.start_date, um.end_date
    FROM user_memberships um
    JOIN memberships m ON um.membership_id = m.membership_id
    WHERE um.user_id = ?
";
$membership_stmt = $conn->prepare($membership_sql);
$membership_stmt->bind_param("i", $user_id);
$membership_stmt->execute();
$membership_result = $membership_stmt->get_result();

$memberships = [];
if ($membership_result->num_rows > 0) {
    while ($row = $membership_result->fetch_assoc()) {
        $memberships[] = $row;
    }
}

$membership_stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Fitzone</title>
    <link rel="icon" type="image/svg+xml" href="/gym/includes/image/logo.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: url('/gym/includes/Background/background2.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        @keyframes backgroundAnimation {
            0% { background-position: 0 0; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0 0; }
        }

        .header {
            background: rgba(34, 34, 34, 0.8);
            padding: 15px 0;
            color: #fff;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            margin-top:8px;
            margin-right: 40px;

        }
        .header h1 span {
            color: #40E0D0;
        }

        nav {
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }

        nav a {
            color: #ddd;
            margin: 5px 10px;
            text-decoration: none;
            font-weight: 500;
            font-size: 18px;
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #ff4757;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            flex-grow: 1;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            gap: 15px;
        }

        .user-profile {
            background: rgba(255, 255, 255, 0.9);
            max-width: 450px;
            margin: 20px 0;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 280px;
        }

        .user-profile img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .user-profile h2 {
            font-size: 20px;
            margin-bottom: 6px;
            color: #333;
        }

        .user-profile p {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }

        .logout-btn {
            background: #ff4757;
            color: #ffffff;
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 0px;
            margin-left: 55px;
            font-size: 16px;
            transition: all 0.3s ease;
            transform: scale(1);
        }

        .logout-btn:hover {
            transform: scale(1.05);
            color:#ffffff;
        }

        .action-button {
            text-align: center;
            margin: 20px 0;
            width: 100%;
        }

        .button {
            background: linear-gradient(45deg, #ff4757, #ff6b81);
            color: #fff;
            padding: 12px 40px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            font-family: 'Montserrat', sans-serif;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(255, 71, 87, 0.4);
            transition: all 0.3s ease;
            transform: scale(1);
        }

        .button:hover {
            background: linear-gradient(45deg, #e84148, #ff4c68);
            transform: scale(1.05);
        }

        .footer {
            text-align: center;
            padding: 15px 0;
            background: #222;
            color: #ccc;
            margin-top: auto;
            font-size: 12px;
        }

        .membership-section {
            background: rgba(255, 255, 255, 0.95);
            max-width: 450px;
            margin: 20px 0;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 280px;
        }

        .membership-section h3 {
            text-align: center;
            margin-bottom: 15px;
            color: #222;
            font-size: 18px;
        }

        .membership {
            background: #f7f7f7;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #ff4757;
            border-radius: 8px;
        }

        .membership p {
            margin: 6px 0;
            font-size: 13px;
            color: #444;
        }

        .status-active {
            color: green;
            font-weight: bold;
        }

        .status-inactive {
            color: red;
            font-weight: bold;
        }

        .membership-action {
            margin-top: 8px;
        }

        .membership-action button {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 12px;
            transition: background 0.3s ease;
        }

        .query-section {
            background: rgba(255, 255, 255, 0.95);
            max-width: 450px;
            margin: 20px 0;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 280px;
        }

        .query-section h3 {
            text-align: center;
            margin-bottom: 15px;
            color: #222;
            font-size: 18px;
        }

        .query-section textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            font-size: 13px;
            border: 1px solid #ddd;
            border-radius: 6px;
            resize: vertical;
            margin-bottom: 10px;
            font-family: inherit;
            background: #f7f7f7;
            transition: border-color 0.3s ease;
        }

        .query-section textarea:focus {
            outline: none;
            border-color: #ff4757;
            box-shadow: 0 0 5px rgba(255, 71, 87, 0.3);
        }

        .query-section input[type="submit"] {
            background: #ff4757;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            width: 100%;
            transition: background 0.3s ease;
        }

        .query-section input[type="submit"]:hover {
            background: #e84148;
        }

        .trainers {
            padding: 30px 10px;
            background-color: rgba(226, 188, 149, 0.9);
            text-align: center;
            border-radius: 20px;
            margin: 20px auto;
            width: 80%;
            max-width: 1000px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .trainers h2 {
            color: #000000;
            font-family: 'Montserrat', sans-serif;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        .trainer-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
        }

        .trainer-card {
            width: 200px;
            padding: 15px;
            background-color: #f4f4f4;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .trainer-card:hover {
            transform: translateY(-5px);
        }

        .trainer-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }

        .trainer-card h3 {
            font-family: 'Montserrat', sans-serif;
            margin: 8px 0 4px;
            font-size: 16px;
        }

        .trainer-card p {
            font-size: 12px;
            color: #444;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                align-items: center;
                gap: 0;
            }

            .user-profile,
            .membership-section,
            .query-section {
                margin: 15px 10px;
                width: 100%;
                max-width: none;
                padding: 15px;
            }

            .membership-section {
                order: 0;
            }

            .trainers {
                padding: 20px 10px;
                margin: 15px auto;
            }

            .trainer-grid {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .trainer-card {
                width: 90%;
                max-width: 300px;
            }

            .header h1 {
                font-size: 20px;
            }

            nav a {
                font-size: 12px;
                margin: 5px 8px;
            }

            .button {
                padding: 10px 30px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .user-profile,
            .membership-section,
            .query-section {
                margin: 10px 5px;
                padding: 10px;
            }

            .user-profile img {
                width: 80px;
                height: 80px;
            }

            .user-profile h2 {
                font-size: 18px;
            }

            .user-profile p,
            .membership p,
            .query-section textarea,
            .query-section input[type="submit"] {
                font-size: 12px;
            }

            .membership-section h3,
            .query-section h3,
            .trainers h2 {
                font-size: 16px;
            }

            .trainer-card {
                width: 100%;
                padding: 10px;
            }

            .trainer-card img {
                height: 150px;
            }

            .trainer-card h3 {
                font-size: 14px;
            }

            .trainer-card p {
                font-size: 11px;
            }

            .header h1 {
                font-size: 18px;
            }

            nav a {
                font-size: 11px;
                margin: 4px 6px;
            }

            .button {
                padding: 8px 20px;
                font-size: 12px;
            }

            .footer {
                font-size: 10px;
                padding: 10px 0;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <img src="/gym/includes/image/logo.svg" alt="Fitzone Logo" style="width: 50px; height: 50px; margin-bottom: 10px;">
            <h1>Fitzone <span>Fitness Center</span></h1>
            <nav>
                <a href="view_classes.php">Classes</a>
                <a href="appointment.php">Appointments</a>
                <a href="UserQueries.php">My Queries</a>
                <a href="membership.php">Memberships</a>
                <a href="../logout.php" class="logout-btn">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="membership-section">
            <h3>Memberships</h3>
            <?php if (count($memberships) > 0): ?>
                <?php foreach ($memberships as $membership): ?>
                    <div class="membership">
                        <p><strong>Membership Name:</strong> <?php echo htmlspecialchars($membership['name']); ?></p>
                        <p><strong>Start Date:</strong> <?php echo htmlspecialchars($membership['start_date']); ?></p>
                        <p><strong>End Date:</strong> <?php echo htmlspecialchars($membership['end_date']); ?></p>
                        <div class="membership-action">
                            <a href="membership.php" class="button" style="padding:6px 18px;font-size:13px;">View Membership Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center;">You don't have any memberships yet.</p>
            <?php endif; ?>
        </div>

        <div class="user-profile">
            <h2><?php echo htmlspecialchars($user['username']); ?></h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Role:</strong> <?php echo htmlspecialchars(ucfirst($user['role'])); ?></p>
            <a href="viewappointment.php" class="button">My Sessions</a>
        </div>

        <div class="query-section">
            <h3>Queries</h3>
            <div style="text-align:center; margin-bottom:10px;">
                <i class="fa-solid fa-comments" style="font-size: 2.5em; color:rgb(82, 82, 82);"></i>
            </div>
            <p style="text-align:center; color:#888; font-style:italic; margin-bottom:15px;">
            Have a question or need help? We're here to listen!
            </p>
            <a href="UserQueries.php" class="button" style="display:block;text-align:center;margin-top:20px;">Go to My Queries</a>
        </div>
    </div>

    <section id="trainers" class="trainers">
        <h2>Meet Our Trainers</h2>
        <div class="trainer-grid">
            <?php
            $conn = new mysqli("localhost", "root", "", "gym");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT name, specialty, experience, profile FROM trainers";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="trainer-card">';
                    echo '<img src="/gym/includes/admin/' . $row["profile"] . '" alt="' . $row["name"] . '" class="trainer-img">';
                    echo '<h3>' . ucfirst($row["name"]) . '</h3>';
                    echo '<p><strong>Specialty:</strong> ' . $row["specialty"] . '</p>';
                    echo '<p><strong>Experience:</strong> ' . $row["experience"] . ' year(s)</p>';
                    echo '</div>';
                }
            } else {
                echo "<p>No trainers found.</p>";
            }
            $conn->close();
            ?>
        </div>
    </section>

    <div class="action-button">
        <a href="/gym/includes/about_us.php" class="button">About Us</a>
    </div>

    <?php include __DIR__ . '/../footer.php'; ?>

</body>
</html>