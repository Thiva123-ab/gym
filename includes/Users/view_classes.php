<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['join_error'] = "Login required.";
    header("Location: view_classes.php");
    exit();
}

// Handle join class POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['class_id'])) {
    $user_id = $_SESSION['user_id'];
    $class_id = (int)$_POST['class_id'];

    // Check if already joined
    $check = $conn->prepare("SELECT * FROM user_classes WHERE user_id = ? AND class_id = ?");
    $check->bind_param("ii", $user_id, $class_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['join_error'] = "You have already joined this class.";
        header("Location: view_classes.php");
        exit();
    }

    // Check if class is full
    $count = $conn->prepare("SELECT COUNT(*) AS enrolled FROM user_classes WHERE class_id = ?");
    $count->bind_param("i", $class_id);
    $count->execute();
    $enrolled = $count->get_result()->fetch_assoc()['enrolled'];

    $limit_check = $conn->prepare("SELECT `limit` FROM classes WHERE class_id = ?");
    $limit_check->bind_param("i", $class_id);
    $limit_check->execute();
    $limit = $limit_check->get_result()->fetch_assoc()['limit'];

    if ($enrolled >= $limit) {
        $_SESSION['join_error'] = "Class is full.";
        header("Location: view_classes.php");
        exit();
    }

    // Join class
    $insert = $conn->prepare("INSERT INTO user_classes (user_id, class_id) VALUES (?, ?)");
    $insert->bind_param("ii", $user_id, $class_id);

    if ($insert->execute()) {
        $_SESSION['join_success'] = "Successfully joined the class!";
    } else {
        $_SESSION['join_error'] = "Failed to join the class. Please try again.";
    }
    header("Location: view_classes.php");
    exit();
}
    // Fetch classes and trainers
    $result = $conn->query("
        SELECT c.*, t.name AS trainer_name
        FROM classes c
        JOIN trainers t ON c.trainer_id = t.trainer_id
    ");
    $user_id = $_SESSION['user_id'];
    $joined_classes = [];
    $joined_result = $conn->query("SELECT class_id FROM user_classes WHERE user_id = $user_id");
    while ($jc = $joined_result->fetch_assoc()) {
        $joined_classes[] = $jc['class_id'];
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Workout Classes</title>
    <link rel="icon" type="image/svg+xml" href="/gym/includes/image/logo.svg">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
    /* Page background */
    body {
        font-family: 'Segoe UI', sans-serif;
        background: url('../Background/background2.jpg') no-repeat center center fixed;
        background-size: cover;
        color: #333;
        margin: 0;
        padding: 40px 20px;
    }

    /* Section title */
    .page-title {
        text-align: center;
        font-size: 2rem;
        color: #fff;
        margin-bottom: 30px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.4);
    }

    /* Background container card */
    .class-background-card {
        background-color: rgba(255, 255, 255, 0.88);
        padding: 40px 30px;
        border-radius: 20px;
        max-width: 1100px;
        margin: 0 auto;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    /* Flex container for cards */
    .class-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 24px;
    }

    /* Card styles */
    .program-card {
        width: 260px;
        background: #ffffff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
        text-align: left;
    }
    .program-card:hover {
        transform: scale(1.05);
    }
    .class-name {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #2c3e50;
    }
    .program-card p {
        font-size: 0.95rem;
        color: #555;
        margin: 4px 0;
    }

    /* Join button */
    .join-btn {
        background: linear-gradient(to right, #4CAF50, #45a049);
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 0.95rem;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.2s ease;
        margin-top: 12px;
    }
    .join-btn:hover {
        background: linear-gradient(to right, #43a047, #388e3c);
        transform: scale(1.05);
    }
    .full-class {
        color: #c0392b;
        font-weight: 600;
        font-size: 1rem;
        margin-top: 20px;

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


    /* Alert styles */
    .alert {
        padding: 12px 18px;
        margin: 20px auto;
        max-width: 500px;
        border-radius: 6px;
        font-size: 0.95rem;
        text-align: center;
        font-weight: 500;
        animation: fadeIn 0.5s ease;
    }
    .alert.success {
        background: #e8f5e9;
        color: #2e7d32;
        border-left: 5px solid #4CAF50;
    }
    .alert.error {
        background: #fdecea;
        color: #c62828;
        border-left: 5px solid #e53935;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .page-title { font-size: 1.5rem; }
        .join-btn { width: 100%; font-size: 1rem; padding: 10px 0; }
        .program-card { width: 90%; padding: 16px; }
        .class-container { padding: 0 10px; }
    }

    
    </style>
</head>
<body>
    <div class="header-bar">
        <a href="/gym/includes/Users/userpage.php" class="button back-btn">&larr; Back to Home</a>
        <div class="header-title">
            Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
        </div>
    </div>
    <h2 class="page-title">Available Workout Classes</h2>
    <?php if (isset($_SESSION['join_success'])): ?>
        <div class="alert success">✅ <?= $_SESSION['join_success']; unset($_SESSION['join_success']); ?></div>
    <?php elseif (isset($_SESSION['join_error'])): ?>
        <div class="alert error"><?= $_SESSION['join_error']; unset($_SESSION['join_error']); ?></div>
    <?php endif; ?>

    <div class="class-background-card">
        <div class="class-container">
            <?php while ($row = $result->fetch_assoc()): 
                $class_id   = $row['class_id'];
                $enrolled   = $conn->query("SELECT COUNT(*) AS cnt FROM user_classes WHERE class_id = $class_id")
                                    ->fetch_assoc()['cnt'];
                $slots_left = $row['limit'] - $enrolled;
            ?>
                <div class="program-card">
                    <h3 class="class-name"><?= htmlspecialchars($row['class_name']) ?></h3>
                    <p><strong>Trainer:</strong> <?= htmlspecialchars($row['trainer_name']) ?></p>
                    <p><strong>Schedule:</strong> <?= date('F j, Y g:i A', strtotime($row['schedule'])) ?></p>
                    <p><strong>Slots Left:</strong> <?= $slots_left ?></p>

                    <?php if (in_array($class_id, $joined_classes)): ?>
                        <p class="full-class" style="color:#388e3c;">Already Joined</p>
                    <?php elseif ($slots_left > 0): ?>
                        <form method="POST" action="">
                            <input type="hidden" name="class_id" value="<?= $class_id ?>">
                            <button type="submit" class="join-btn">Join Class</button>
                        </form>
                    <?php else: ?>
                        <p class="full-class">Class is full</p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>

    </div>
<div style="margin-top: 80px;">
    <?php include __DIR__ . '/../footer.php'; ?>
</div>
</body>
</html>
