<?php
session_start(); // start the session .session allow us to store user specific data
include 'dbconnect.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id'])) {
    echo "User ID is not set in session.";
    exit;
}


$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM queries WHERE user_id = $user_id ORDER BY created_at DESC");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Queries | Fitzone Fitness</title>
    <link rel="icon" type="image/svg+xml" href="/gym/includes/image/logo.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eef1f5;
            background: url('/gym/includes/Background/background2.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1100px;
            margin: 60px auto;
            padding: 30px;
            background-color:rgba(189, 189, 189, 0.82);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #2d2d2d;
            margin-bottom: 30px;
        }
        .success-message {
            background-color: #e6ffed;
            color: #2c662d;
            padding: 15px 20px;
            border-left: 6px solid #52c41a;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 500;
        }
        .query-card {
            background-color: #f9fafb;
            border-left: 5px solid #007bff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03);
        }
        .query-card:hover {
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }
        .query-card p {
            margin: 8px 0;
            color: #444;
        }
        .query-card strong {
            color: #222;
        }
        .badge-category {
            background: #e3f0ff;
            color: #0056b3;
            font-size: 0.95em;
            margin-right: 8px;
        }
        .badge-status {
            font-size: 0.95em;
            margin-right: 8px;
        }
        .badge-status.Pending {
            background: #fff3cd;
            color: #856404;
        }
        .badge-status.Responded {
            background: #d4edda;
            color: #155724;
        }
        .query-form label {
            margin-top: 10px;
        }
        .query-form textarea {
            width: 100%;
            height: 120px;
            padding: 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: vertical;
            margin-bottom: 15px;
            font-family: inherit;
        }
        .query-form input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .query-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        @media (max-width: 767.98px) {
            .container {
                padding: 10px;
            }
            .row {
                margin: 0;
            }
            .col-md-6 {
                padding: 0 !important;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Back to Home Button -->
        <a href="userpage.php" class="btn btn-secondary mb-3">&larr; Back to Home</a>
        <h1>My Queries</h1>
        <?php if (isset($_GET['submitted'])): ?>
            <div class="success-message">
                ✅ Your query was submitted successfully!
            </div>
        <?php endif; ?>

        <div class="row mt-4">
            <!-- Left: Submit a New Query -->
            <div class="col-md-6 mb-4">
                <div class="query-form">
                    <h4 class="mb-3">Submit a New Query</h4>
                    <form action="submit_query.php" method="POST">
                        <label for="category"><strong>Category:</strong></label>
                        <select name="category" id="category" required class="form-select mb-3">
                            <option value="" disabled selected>Select a category</option>
                            <option value="Membership">Membership</option>
                            <option value="Billing">Billing</option>
                            <option value="Facilities">Facilities</option>
                            <option value="Trainers">Trainers</option>
                            <option value="Classes">Classes</option>
                            <option value="Other">Other</option>
                        </select>
                        <label for="query_text"><strong>Your Query:</strong></label>
                        <textarea name="query_text" id="query_text" placeholder="Type your question..." required></textarea>
                        <input type="submit" value="Submit Query" class="btn btn-primary mt-2">
                    </form>
                </div>
            </div>
            <!-- Right: Past Queries List -->
            <div class="col-md-6">
                <h4 class="mb-3">Submitted Queries</h4>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="query-card">
                        <span><strong>Category:</strong></span>
                        <span class="badge badge-category"><?= htmlspecialchars($row['category']) ?></span>
                        <span><strong>Status:</strong></span>
                        <span class="badge badge-status <?= htmlspecialchars($row['status']) ?>">
                            <?= htmlspecialchars($row['status']) ?>
                        </span>
                        <p class="mt-2 mb-1"><strong>Query:</strong> <?= htmlspecialchars($row['query_text']) ?></p>
                        <?php if (!empty($row['response'])): ?>
                            <div style="border: 1px solid #28a745; background: #e9fbe7; padding: 10px; margin-top: 10px; border-radius: 5px;">
                                <strong>Staff Response:</strong>
                                <div style="margin-top: 5px; color: #155724;">
                                    <?= nl2br(htmlspecialchars($row['response'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="text-end">
                            <small class="text-muted"><?= date('M d, Y H:i', strtotime($row['created_at'])) ?></small>
                            <form action="delete_query.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this query?');">
                                <input type="hidden" name="query_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm ms-2">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../footer.php'; ?>

    <!-- Bootstrap JS (optional, for dropdowns etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>