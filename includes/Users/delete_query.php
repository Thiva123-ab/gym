<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: UserQueries.php");
    exit;
}

if (isset($_POST['query_id'])) {
    $query_id = (int)$_POST['query_id'];
    $user_id = $_SESSION['user_id'];

    // Only delete if the query belongs to the logged-in user and status is 'pending'
    $stmt = $conn->prepare("DELETE FROM queries WHERE id = ? AND user_id = ? AND status = 'pending'");
    $stmt->bind_param("ii", $query_id, $user_id);
    $stmt->execute();
}

header("Location: UserQueries.php");
exit;
?>