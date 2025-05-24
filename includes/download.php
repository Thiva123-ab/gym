<?php
include('dbconnect.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Download Files</title>
</head>
<body>

<h2>Download Files</h2>

<?php
$sql = "SELECT * FROM files";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<a href='" . $row['file_path'] . "' download>" . $row['file_name'] . "</a><br>";
    }
} else {
    echo "No files found.";
}
?>

<br><br>
<a href="upload.php">Go to Upload Page</a>

</body>
</html>
