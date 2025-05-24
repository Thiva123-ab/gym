<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['join_error'] = "Login required.";
    header("Location: /gym/includes/Users/view_classes.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$class_id = $_POST['class_id'];

// Use prepared statement to prevent SQL injection
$check = $conn->prepare("SELECT * FROM user_classes WHERE user_id = ? AND class_id = ?");
$check->bind_param("ii", $user_id, $class_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    $_SESSION['join_error'] = "You have already joined this class.";
    header("Location: /gym/includes/Users/view_classes.php");
    exit();
}

// Check if class has available slots
$count = $conn->prepare("SELECT COUNT(*) AS enrolled FROM user_classes WHERE class_id = ?");
$count->bind_param("i", $class_id);
$count->execute();
$enrolled = $count->get_result()->fetch_assoc()['enrolled'];

// Get class limit
$limit_check = $conn->prepare("SELECT `limit` FROM classes WHERE class_id = ?");
$limit_check->bind_param("i", $class_id);
$limit_check->execute();
$limit = $limit_check->get_result()->fetch_assoc()['limit'];

if ($enrolled >= $limit) {
    $_SESSION['join_error'] = "Class is full.";
    header("Location: /gym/includes/Users/view_classes.php");
    exit();
}

$insert = $conn->prepare("INSERT INTO user_classes (user_id, class_id) VALUES (?, ?)");
$insert->bind_param("ii", $user_id, $class_id);

if ($insert->execute()) {
    $_SESSION['join_success'] = "Successfully joined the class!";
} else {
    $_SESSION['join_error'] = "Failed to join the class. Please try again.";
}

header("Location: /gym/includes/Users/view_classes.php");
exit();
?>