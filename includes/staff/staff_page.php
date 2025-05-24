<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION['staff_id']) || $_SESSION['role'] != 'staff') {
    die("Access denied. Please log in as staff.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query_id'], $_POST['response'])) {
    $query_id = $_POST['query_id'];
    $response = $_POST['response'];

    $stmt = $conn->prepare("UPDATE queries SET response = ?, status = 'Responded', responded_at = NOW() WHERE id = ?");
    $stmt->bind_param("si", $response, $query_id);
    $stmt->execute();
}

// Get selected category from GET or POST
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';

// Fetch all queries
$result = $conn->query("SELECT queries.*, users.username FROM queries JOIN users ON queries.user_id = users.user_id");
$pending = [];
$responded = [];
while ($row = $result->fetch_assoc()) {
    if ($selectedCategory && $row['category'] !== $selectedCategory) {
        continue; // Skip if not matching selected category
    }
    if (strtolower($row['status']) === 'responded') {
        $responded[] = $row;
    } else {
        $pending[] = $row;
    }
}
// Get all unique categories from queries table
$categoryResult = $conn->query("SELECT DISTINCT category FROM queries");
$categories = [];
while ($catRow = $categoryResult->fetch_assoc()) {
    $categories[] = $catRow['category'];
}

// Get selected category from GET or POST
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Query Responses</title>
    <link rel="icon" type="image/svg+xml" href="/gym/includes/image/logo.svg">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color:rgb(201, 201, 201);
        }

        .header-bar {
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            padding: 18px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 10;
            gap: 20px;
        }

        .header-title {
            flex: 1;
            text-align: center;
            font-size: 2rem;
            font-weight: 600;
            color: #2a2a2a;

        }

        .category-filter-form {
            display: flex;
            align-items: center;
            background: #f5f8ff;
            border-radius: 8px;
            padding: 6px 14px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .category-select {
            border: 1px solid #b3c6e0;
            border-radius: 6px;
            padding: 5px 14px;
            font-size: 1rem;
            background: #fff;
            color: #0056b3;
            margin-left: 6px;
            outline: none;
            transition: border-color 0.2s;
        }

        .logout-btn {
            background: linear-gradient(45deg, #ff4757, #ff6b81);
            color: #fff;
            padding: 10px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            border: none;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(255, 71, 87, 0.15);
        }

        .logout-btn:hover {
            background: linear-gradient(45deg, #e84148, #ff4c68);
            box-shadow: 0 6px 20px rgba(255, 71, 87, 0.3);
        }

        .container {
            width: 95%;
            max-width: 1200px;
            margin: 20px auto;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .column {
            flex: 1 1 100%;
        }

        @media (min-width: 768px) {
            .column {
                flex: 1 1 48%;
            }
        }

        .column h2 {
            text-align: center;
            color: #444;
        }

        .query {
            background-color: #e9f7ff;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .query p {
            margin: 10px 0;
            font-size: 16px;
        }

        .status {
            font-weight: bold;
            color: #2a9d8f;
        }

        .response {
            margin-top: 10px;
            background-color: #f0f8ff;
            padding: 10px;
            border-radius: 8px;
            margin-left: 10px;
            
        }
        
        .responded{
            margin-top: 10px;
            background-color: #f0f8ff;
            padding: 5px;
            border-left: 5px solid #2a9d8f;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .pending{
            margin-top: 10px;
            background-color: #f0f8ff;
            padding: 5px;
            border-left: 5px solid #ffb300;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        form textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            resize: vertical;
            min-height: 80px;
        }

        form button {
            margin-top: 10px;
            margin-left: 10px;
            font-size: 1rem;
            padding: 10px 15px;
            background-color:rgb(180, 126, 0);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);

        }

        form button:hover {
            background-color:rgb(135, 94, 0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s;

        }

        .query-responded {
            border-left: 8px solid #2a9d8f;
            background-color: rgba(195, 225, 216, 0.67);
            padding: 10px;
        }

        .query-pending {
            border-left: 8px solid #ffb300;
            background-color: rgba(238, 224, 156, 0.62);
            padding: 10px;
        }

        .badge-category {
            background: #e3f0ff;
            color: #0056b3;
            font-size: 0.95em;
            margin-right: 8px;
            padding: 3px 10px;
            border-radius: 12px;
        }
        
    </style>
</head>
<body>
    <div class="header-bar">
        <form method="get" class="category-filter-form" style="margin:0; display:flex; align-items:center;">
            <label for="category" style="margin:0 8px 0 0; font-weight:600; color:#444;">Category:</label>
            <select name="category" id="category" onchange="this.form.submit()" class="category-select">
                <option value="">All</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= $selectedCategory === $cat ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <noscript><button type="submit">Filter</button></noscript>
        </form>

        <div class="header-title">Query Responses</div>

        <a href="/gym/includes/logout.php" class="logout-btn">Log Out</a>
    </div>


    <div class="container">

        <div class="column">
            <h2>Pending Queries</h2>
            <?php if (empty($pending)): ?>
                <div class="query query-pending">
                    <span class="badge-category"><strong>Info:</strong> </span>
                    <div class="pending">
                        <p style="margin-bottom:0;"><strong>No pending queries at the moment.</strong></p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($pending as $row): ?>
                    <div class="query query-pending">
                        <span><strong>User:</strong> <?php echo htmlspecialchars($row['username']); ?></span>
                        <span class="badge-category"><strong>Category:</strong><?php echo htmlspecialchars($row['category']); ?></span>
                        <div class="pending">
                            <p><strong>Query:</strong> <?php echo htmlspecialchars($row['query_text']); ?></p>
                        </div>
                        <form method="POST" action="">
                            <input type="hidden" name="query_id" value="<?php echo $row['id']; ?>">
                            <textarea name="response" placeholder="Type your response..." required style="box-sizing:border-box;"></textarea>
                            <button type="submit">Submit Response</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="column">
            <h2>Responded Queries</h2>
            <?php foreach ($responded as $row): ?>
                <div class="query query-responded">
                    <span><strong>User:</strong> <?php echo htmlspecialchars($row['username']); ?></span>
                    <span class="badge-category"><strong>Category:</strong><?php echo htmlspecialchars($row['category']); ?></span>
                    <div class="responded">
                        <p><strong>Query:</strong> <?php echo htmlspecialchars($row['query_text']); ?></p>
                    </div>
                    <div class="response">
                        <p><strong>Response:</strong> <?php echo htmlspecialchars($row['response']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
