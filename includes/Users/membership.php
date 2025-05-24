<?php
session_start();
include('dbconnect.php');
error_reporting(0);

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['username']);
$isLoggedId = isset($_SESSION['user_id']);

if (!$isLoggedIn) {
    echo '<script> 
            alert("Please log in to your account to select a membership plan.");
            window.location.href = "../login.php";
          </script>';
    exit;
}

if (isset($_POST['cancel_membership'])) {
    $user_id = $_SESSION['user_id'];
    $cancelSql = "DELETE FROM user_memberships WHERE user_id = '$user_id' AND type = 'active'";
    $conn->query($cancelSql);
    // Redirect to refresh the page and show the form
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['subscribe'])) {
    // Validate and sanitize input data
    $membership_id = (int) $_POST['membership_id'];

    // Insert membership details into user_memberships table
    $sql = "INSERT INTO user_memberships 
                (user_id, membership_id, start_date, end_date, type)
            VALUES 
                ('".$_SESSION['user_id']."', '$membership_id', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 'active')";

    if ($conn->query($sql) === TRUE) {
    // Redirect with a success flag
    header("Location: " . $_SERVER['PHP_SELF'] . "?subscribed=1");
    exit;
    } else {
        echo "<p class='error'>Error: " . $conn->error . "</p>";
    }
    $conn->close();
    }

$user_id = $_SESSION['user_id'];
$activeMembershipQuery = "SELECT um.*, m.name, m.price 
    FROM user_memberships um 
    JOIN memberships m ON um.membership_id = m.membership_id 
    WHERE um.user_id = '$user_id' AND um.type = 'active' 
    ORDER BY um.start_date DESC LIMIT 1";
$activeMembershipResult = $conn->query($activeMembershipQuery);
$hasActiveMembership = ($activeMembershipResult && $activeMembershipResult->num_rows > 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Plan</title>
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

        .form-container .success {
            color: green;
            margin-top: 10px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF; /* Blue background, change as needed */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .form-container {
                padding: 20px;
            }
        }

        .memberships {
            padding: 20px 10px;
            background-color: rgba(9, 65, 77, 0.9);
            text-align: center;
            border-radius: 30px;
            margin: 20px auto;
            width: 90%;
            max-width: 1200px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .memberships h2 {
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 32px;
            margin-bottom: 20px;
        }
        .membership-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .membership-card {
            width: 240px;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .membership-card:hover {
            transform: scale(1.05);
        }

        .membership-card .price {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
            background-color:#8f8c8c;
            padding: 5px ;
            border-radius: 5px;
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
    </div>
    <?php if (isset($_GET['subscribed'])): ?>
        <br>
        <div class="success-message" style="background-color:#e6ffed;color:#2c662d;padding:15px 20px;border-left:6px solid #52c41a;border-radius:8px;margin-bottom:25px;font-weight:500;">
            ✅ Membership subscribed successfully!
        </div>
    <?php endif; ?>
    <!-- Membership Details -->
    <?php if ($hasActiveMembership): 
        $membership = $activeMembershipResult->fetch_assoc();
    ?>
        <div class="form-container">
            <h2>Your Active Membership</h2>
            <p><strong>Plan:</strong> <?php echo htmlspecialchars($membership['name']); ?></p>
            <p><strong>Price:</strong> $<?php echo htmlspecialchars($membership['price']); ?></p>
            <p><strong>Start Date:</strong> <?php echo htmlspecialchars($membership['start_date']); ?></p>
            <p><strong>End Date:</strong> <?php echo htmlspecialchars($membership['end_date']); ?></p>
            <form method="POST" style="margin-top:20px;">
                <button type="submit" name="cancel_membership" style="background:#e53935;color:#fff;padding:10px 20px;border:none;border-radius:6px;cursor:pointer;font-weight:bold;">Cancel Membership</button>
            </form>
        </div>
    <?php else: ?>
        <!-- Membership Selection Form -->
        <div class="form-container">
            <h2>Choose a Membership Plan</h2>
            <form action="" method="POST">
                <label for="membership">Select Membership Plan:</label>
                <select id="membership" name="membership_id" required>
                    <option value="">-- Choose Plan --</option>
                    <?php
                        $membershipQuery = "SELECT membership_id, name, price FROM memberships";
                        $membershipResult = $conn->query($membershipQuery);
                        if ($membershipResult->num_rows > 0) {
                            while ($row = $membershipResult->fetch_assoc()) {
                                echo "<option value='" . $row['membership_id'] . "'>" . htmlspecialchars($row['name']) . " - $" . $row['price'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No membership plans available</option>";
                        }
                    ?>
                </select>
                <button type="submit" name="subscribe">Subscribe to Plan</button>
            </form>
        </div>
    <?php endif; ?>

    <section id="memberships" class="memberships container">
        <h2>Our Membership Plans</h2>
        <div class="membership-grid">
            <?php
            // Connect to database
            $conn = new mysqli("localhost", "root", "", "gym");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT name, price, duration, description FROM memberships";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="membership-card">';
                    echo '<h3>' . htmlspecialchars($row["name"]) . '</h3>';
                    echo '<p class="price">$' . htmlspecialchars($row["price"]) . '</p>';
                    echo '<p><strong>Duration:</strong> ' . htmlspecialchars($row["duration"]) . ' days</p>';
                    echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
                    echo '</div>';
                }
            } else {
                echo "<p>No membership plans available.</p>";
            }
            $conn->close();
            ?>
        </div>
    </section>
<?php include __DIR__ . '/../footer.php'; ?>

</body>
</html>