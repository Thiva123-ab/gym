<?php
// Start session to check for admin access
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$host = '127.0.0.1';
$dbname = 'gym';
$username = 'root'; // Adjust as needed
$password = ''; // Adjust as needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submissions
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_membership'])) {
        // Add new membership
        $name = trim($_POST['name']);
        $price = floatval($_POST['price']);
        $duration = intval($_POST['duration']);
        $description = trim($_POST['description']);

        // Validation
        if (empty($name)) {
            $errors[] = "Membership name is required.";
        }
        if ($price <= 0) {
            $errors[] = "Price must be greater than 0.";
        }
        if ($duration <= 0) {
            $errors[] = "Duration must be greater than 0.";
        }

        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO memberships (name, price, duration, description) VALUES (:name, :price, :duration, :description)");
                $stmt->execute([
                    ':name' => $name,
                    ':price' => $price,
                    ':duration' => $duration,
                    ':description' => $description
                ]);
                $success = "Membership added successfully.";
            } catch (PDOException $e) {
                $errors[] = "Failed to add membership: " . $e->getMessage();
            }
        }
    } elseif (isset($_POST['edit_membership'])) {
        // Edit existing membership
        $membership_id = intval($_POST['membership_id']);
        $name = trim($_POST['name']);
        $price = floatval($_POST['price']);
        $duration = intval($_POST['duration']);
        $description = trim($_POST['description']);

        // Validation
        if (empty($name)) {
            $errors[] = "Membership name is required.";
        }
        if ($price <= 0) {
            $errors[] = "Price must be greater than 0.";
        }
        if ($duration <= 0) {
            $errors[] = "Duration must be greater than 0.";
        }

        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare("UPDATE memberships SET name = :name, price = :price, duration = :duration, description = :description WHERE membership_id = :id");
                $stmt->execute([
                    ':name' => $name,
                    ':price' => $price,
                    ':duration' => $duration,
                    ':description' => $description,
                    ':id' => $membership_id
                ]);
                $success = "Membership updated successfully.";
            } catch (PDOException $e) {
                $errors[] = "Failed to update membership: " . $e->getMessage();
            }
        }
    } elseif (isset($_POST['delete_membership'])) {
        // Delete membership
        $membership_id = intval($_POST['membership_id']);
        try {
            // Check if membership is in use
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_memberships WHERE membership_id = :id");
            $stmt->execute([':id' => $membership_id]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $errors[] = "Cannot delete membership; it is currently in use.";
            } else {
                $stmt = $pdo->prepare("DELETE FROM memberships WHERE membership_id = :id");
                $stmt->execute([':id' => $membership_id]);
                $success = "Membership deleted successfully.";
            }
        } catch (PDOException $e) {
            $errors[] = "Failed to delete membership: " . $e->getMessage();
        }
    }
}

// Fetch all memberships
try {
    $stmt = $pdo->query("SELECT * FROM memberships");
    $memberships = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors[] = "Failed to fetch memberships: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Memberships</title>
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
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)), url('https://images.unsplash.com/photo-1517836357463-d25dfeac3438?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            width: 100%;
        }

        h2, h3 {
            color: #fff;
            text-align: center;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
            margin-bottom: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header {
            background: #007bff;
            color: #fff;
            padding: 15px;
            font-size: 1.2rem;
            font-weight: 500;
        }

        .card-body {
            padding: 20px;
        }

        .error, .success {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .error {
            background: #ffe6e6;
            color: #d32f2f;
        }

        .success {
            background: #e6ffed;
            color: #2e7d32;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
            outline: none;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        button {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        button.primary {
            background: #007bff;
            color: #fff;
        }

        button.primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        button.secondary {
            background: #6c757d;
            color: #fff;
        }

        button.secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        button.danger {
            background: #dc3545;
            color: #fff;
        }

        button.danger:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        button.success {
            background: #28a745;
            color: #fff;
        }

        button.success:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            font-weight: 500;
        }

        tr:hover {
            background: #f1f3f5;
        }

        .actions {
            display: flex;
            gap: 10px;
        }
        .footer {
            color: #fff;
            text-align: center;
            padding: 20px 0;
            width: 100%;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            h2 {
                font-size: 1.5rem;
            }

            h3 {
                font-size: 1.2rem;
            }

            .card-body {
                padding: 15px;
            }

            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 10px;
            }

            .actions {
                flex-direction: column;
                gap: 5px;
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
    </style>
</head>
<body>

    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <h2>Admin Dashboard</h2>
            <a href="dashboard.php">Dashboard</a>

            <a href="managetrainers.php">Manage Trainers</a>
            <a href="Add_new_class.php">Add Classes</a>
            <a href="manage_user.php">View Users</a>
            <a href="../../includes/logout.php" class="logout">Logout</a>
        </div>
    <div class="container">
        <h2>Manage Memberships</h2>

        <!-- Display errors or success messages -->
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="success">
                <p><?php echo htmlspecialchars($success); ?></p>
            </div>
        <?php endif; ?>

        <!-- Add Membership Form -->
        <div class="card">
            <div class="card-header">Add New Membership</div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="name">Membership Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price ($)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="duration">Duration (days)</label>
                        <input type="number" id="duration" name="duration" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"></textarea>
                    </div>
                    <button type="submit" name="add_membership" class="primary">Add Membership</button>
                </form>
            </div>
        </div>

        <!-- Membership List -->
        <div class="card">
            <div class="card-header">Existing Memberships</div>
            <div class="card-body">
                <?php if (!empty($memberships)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Duration (days)</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($memberships as $membership): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($membership['membership_id']); ?></td>
                                    <td><?php echo htmlspecialchars($membership['name']); ?></td>
                                    <td>$<?php echo htmlspecialchars(number_format($membership['price'], 2)); ?></td>
                                    <td><?php echo htmlspecialchars($membership['duration']); ?></td>
                                    <td><?php echo htmlspecialchars($membership['description'] ?? ''); ?></td>
                                    <td class="actions">
                                        <button class="success" onclick="populateEditForm(<?php echo $membership['membership_id']; ?>, '<?php echo addslashes($membership['name']); ?>', <?php echo $membership['price']; ?>, <?php echo $membership['duration']; ?>, '<?php echo addslashes($membership['description'] ?? ''); ?>')">Edit</button>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this membership?');">
                                            <input type="hidden" name="membership_id" value="<?php echo $membership['membership_id']; ?>">
                                            <button type="submit" name="delete_membership" class="danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No memberships found.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Edit Membership Form (Hidden by default) -->
        <div class="card" id="edit-form-card" style="display:none;">
            <div class="card-header">Edit Membership</div>
            <div class="card-body">
                <form method="POST" id="edit-form">
                    <input type="hidden" id="edit_membership_id" name="membership_id">
                    <div class="form-group">
                        <label for="edit_name">Membership Name</label>
                        <input type="text" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_price">Price ($)</label>
                        <input type="number" id="edit_price" name="price" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_duration">Duration (days)</label>
                        <input type="number" id="edit_duration" name="duration" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea id="edit_description" name="description"></textarea>
                    </div>
                    <button type="submit" name="edit_membership" class="primary">Update Membership</button>
                    <button type="button" class="secondary" onclick="cancelEdit()">Cancel</button>
                </form>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../footer.php'; ?>


    <script>
        function populateEditForm(id, name, price, duration, description) {
            document.getElementById('edit_membership_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_duration').value = duration;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit-form-card').style.display = 'block';
            window.scrollTo({
                top: document.getElementById('edit-form-card').offsetTop - 20,
                behavior: 'smooth'
            });
        }

        function cancelEdit() {
            document.getElementById('edit-form-card').style.display = 'none';
            document.getElementById('edit-form').reset();
        }
    </script>
</body>
</html>