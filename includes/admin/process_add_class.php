<?php
include '../dbconnect.php';

$class_name = $_POST['class_name'];
$trainer_id = $_POST['trainer_id'];
$schedule = $_POST['schedule'];
$limit = $_POST['limit'];

$stmt = $conn->prepare("INSERT INTO classes (class_name, trainer_id, schedule, `limit`) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sisi", $class_name, $trainer_id, $schedule, $limit);
$stmt->execute();

header("Location: Add_new_class.php?success=1");
?>