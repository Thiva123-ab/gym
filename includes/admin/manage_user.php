<?php
// db connection
$host = "localhost";
$user = "root";
$password = "";
$database = "gym";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// DELETE user
if (isset($_GET['delete'])) {
    $user_id = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    if ($user_id) {
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
    }
    header("Location: manage_user.php");
    exit();
}

// UPDATE user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
    
    // Validate role
    $valid_roles = ['admin', 'staff', 'customer'];
    if ($user_id && $username && $email && in_array($role, $valid_roles)) {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE user_id = ?");
        $stmt->execute([$username, $email, $role, $user_id]);
    }
    header("Location: manage_user.php");
    exit();
}

// GET user for editing
$edit_user = null;
if (isset($_GET['edit'])) {
    $user_id = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
    if ($user_id) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $edit_user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            min-height: 100vh;
            background: url('/gym/includes/Background/background2.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            margin-left: 240px;
        }

        h2 {
            color: #1a202c;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: #f4f4f4;
        }

        .table-container {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            color: #4a5568;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1rem;
            text-align: left;
        }

        td {
            padding: 1rem;
            color: #2d3748;
            font-size: 0.875rem;
        }

        tr {
            border-bottom: 1px solid #edf2f7;
            transition: background-color 0.2s;
        }

        tr:hover {
            background-color: #f7fafc;
        }

        .action-link {
            text-decoration: none;
            margin-right: 1rem;
            font-size: 0.875rem;
        }

        .edit-link {
            color: #3182ce;
        }

        .edit-link:hover {
            color: #2b6cb0;
        }

        .delete-link {
            color: #e53e3e;
        }

        .delete-link:hover {
            color: #c53030;
        }
        

        .form-container {
            margin-top: 2rem;
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }

        .form-container h3 {
            color: #1a202c;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            color: #4a5568;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        input, select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #3182ce;
            box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
        }

        button {
            background: #3182ce;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button:hover {
            background: #2b6cb0;
        }

        button i {
            margin-right: 0.5rem;
        }
        .footer {
            color: #fff;
            text-align: center;
            padding: 20px 0;
            width: 100%;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            th, td {
                padding: 0.75rem;
                font-size: 0.8rem;
            }

            .form-container {
                padding: 1rem;
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
<div class="sidebar">
    <h2>Admin Dashboard</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="managetrainers.php">Manage Trainers</a>
    <a href="Add_new_class.php">Add Classes</a>
    <a href="manage_memberships.php">Manage Memberships</a>
    <a href="../../includes/logout.php" class="logout">Logout</a>
</div>
    <div class="container">
        <h2>User Management</h2>

        <!-- User Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->query("SELECT * FROM users");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['user_id']) . "</td>
                                <td>" . htmlspecialchars($row['username']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['role']) . "</td>
                                <td>
                                    <a href='manage_user.php?edit={$row['user_id']}' class='action-link edit-link'>
                                        <i class='fas fa-edit'></i> Edit
                                    </a>
                                    <a href='manage_user.php?delete={$row['user_id']}' 
                                       onclick='return confirm(\"Are you sure you want to delete this user?\")'
                                       class='action-link delete-link'>
                                        <i class='fas fa-trash'></i> Delete
                                    </a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Edit Form -->
        <?php if ($edit_user): ?>
        <div class="form-container">
            <h3>Edit User (ID: <?php echo htmlspecialchars($edit_user['user_id']); ?>)</h3>
            <form method="POST">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($edit_user['user_id']); ?>">
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="<?php echo htmlspecialchars($edit_user['username']); ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="<?php echo htmlspecialchars($edit_user['email']); ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="admin" <?php if ($edit_user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                        <option value="staff" <?php if ($edit_user['role'] == 'staff') echo 'selected'; ?>>Staff</option>
                        <option value="customer" <?php if ($edit_user['role'] == 'customer') echo 'selected'; ?>>Customer</option>
                    </select>
                </div>

                <button type="submit" name="update_user">
                    <i class="fas fa-save"></i> Update User
                </button>
            </form>
        </div>
        <?php endif; ?>
    </div>
    <div class="footer">
        <?php include __DIR__ . '/../footer.php'; ?>
    </div>
    <script>
        // Smooth scrolling for edit form
        document.addEventListener('DOMContentLoaded', () => {
            if (window.location.search.includes('edit=')) {
                document.querySelector('.form-container')?.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    </script>
</body>
</html>