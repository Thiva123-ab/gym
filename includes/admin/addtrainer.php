<?php
include '../dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $specialty = $_POST['specialty'];
    $experience = $_POST['experience'];


    // Image upload
    $target_dir = "uploads/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . time() . "_" . $image_name;
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    $query = "INSERT INTO trainers (name, specialty, experience, profile) 
              VALUES ('$name', '$specialty', '$experience', '$target_file')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: managetrainers.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
