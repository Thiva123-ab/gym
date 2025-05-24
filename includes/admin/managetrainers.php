<?php
session_start();
include '../dbconnect.php'; // Database connection

// Check if the admin is logged in
if (!isset($_SESSION['username'])) {
    echo '<script> 
            alert("Please log in to access the admin dashboard.");
            window.location.href = "../login.php";
          </script>';
    exit;
}

// Delete trainer
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $query = "DELETE FROM trainers WHERE trainer_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = "Trainer deleted successfully!";
    header("Location: managetrainers.php");
    exit();
}

// Fetch all trainers
$query = "SELECT * FROM trainers";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Trainers - FitZone Fitness</title>
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
            background: linear-gradient(45deg, rgba(255, 0, 150, 0.7), rgba(0, 204, 255, 0.7)), url('/gym/includes/image/pexels-victorfreitas-841130.jpg') no-repeat center center fixed;
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
            transition: transform 0.3s ease;
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

        .message {
            background: #28a745;
            color: var(--text-light);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto 40px;
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
        .form-container input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-container input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 8px rgba(255, 71, 87, 0.3);
        }

        .form-container input[type="file"] {
            padding: 8px;
        }

        .form-container #image-preview {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 10px;
            display: none;
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

        .trainers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .trainer-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .trainer-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .trainer-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .trainer-card h3 {
            font-size: 18px;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .trainer-card p {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }

        .trainer-card .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
        }

        .action-btn {
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            transition: background 0.3s, transform 0.2s;
        }

        .edit-btn {
            background: #007BFF;
            color: var(--text-light);
        }

        .edit-btn:hover {
            background: #0056b3;
            transform: scale(1.1);
        }

        .delete-btn {
            background: #ff6b6b;
            color: var(--text-light);
        }

        .delete-btn:hover {
            background: #ff4c4c;
            transform: scale(1.1);
        }

        .no-trainers {
            text-align: center;
            font-size: 16px;
            color: #666;
            margin: 20px 0;
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

            .trainer-card img {
                height: 150px;
            }

            .trainer-card {
                padding: 15px;
            }

            .action-btn {
                padding: 6px 12px;
                font-size: 12px;
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
            <a href="Add_new_class.php">Add Classes</a>
            <a href="manage_memberships.php">Manage Memberships</a>
            <a href="manage_user.php">View Users</a>
            <a href="../logout.php" class="logout">Logout</a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Manage Trainers</h1>
            </div>

            <!-- Success Message -->
            <?php if (isset($_SESSION['message'])) { ?>
                <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php } ?>

            <!-- Add Trainer Form -->
            <div class="form-container">
                <form method="POST" action="addtrainer.php" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="specialty">Specialty:</label>
                    <input type="text" id="specialty" name="specialty" required>

                    <label for="experience">Experience (Years):</label>
                    <input type="number" id="experience" name="experience" min="0" required>

                    <label for="image">Image:</label>
                    <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage(event)">
                    <img id="image-preview" src="#" alt="Image Preview">

                    <button type="submit">Add Trainer</button>
                </form>
            </div>

            <!-- Display Trainers -->
            <div class="trainers-grid">
                <?php if (mysqli_num_rows($result) > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <div class="trainer-card">
                            <img src="<?php echo htmlspecialchars($row['profile']); ?>" alt="Trainer Image">
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                            <p><strong>Specialty:</strong> <?php echo htmlspecialchars($row['specialty']); ?></p>
                            <p><strong>Experience:</strong> <?php echo htmlspecialchars($row['experience']); ?> years</p>
                            <div class="actions">
                                <a href="updatetrainer.php?id=<?php echo $row['trainer_id']; ?>" class="action-btn edit-btn">Edit</a>
                                <a href="managetrainers.php?delete=<?php echo $row['trainer_id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this trainer?')">Delete</a>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="no-trainers">No trainers found. Add a trainer to get started!</p>
                <?php } ?>
            </div>

            <div class="footer">
                <?php include __DIR__ . '/../footer.php'; ?>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            let name = document.getElementById('name').value;
            let specialty = document.getElementById('specialty').value;
            let experience = document.getElementById('experience').value;
            let image = document.getElementById('image').value;
            if (name.length < 2) {
                alert('Name must be at least 2 characters.');
                return false;
            }
            if (specialty.length < 2) {
                alert('Specialty must be at least 2 characters.');
                return false;
            }
            if (experience < 0) {
                alert('Experience cannot be negative.');
                return false;
            }
            if (!image) {
                alert('Please select an image.');
                return false;
            }
            return true;
        }

        function previewImage(event) {
            let preview = document.getElementById('image-preview');
            preview.style.display = 'block';
            preview.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
</body>
</html>