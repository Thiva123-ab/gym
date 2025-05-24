<?php
session_start();
include 'dbconnect.php'; // DB connection

$user_id = $_SESSION['user_id']; // assuming user is logged in
$query_text = $_POST['query_text'];
$category = $_POST['category']; // Get the category from the form

$stmt = $conn->prepare("INSERT INTO queries (user_id, query_text, category) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $query_text, $category);
$stmt->execute();

header("Location: http://localhost/gym/includes/Users/UserQueries.php?submitted=1");
exit();