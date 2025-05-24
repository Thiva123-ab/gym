<?php 
include '../dbconnect.php'; // Database connection

// Fetch the trainer ID from the URL
if (isset($_GET['id'])) {
    $trainer_id = $_GET['id'];
    
    // Fetch trainer data based on ID
    $query = "SELECT * FROM trainers WHERE trainer_id = $trainer_id";
    $result = mysqli_query($conn, $query);
    $trainer = mysqli_fetch_assoc($result);
    
    // Check if trainer exists
    if (!$trainer) {
        echo "Trainer not found.";
        exit();
    }
} else {
    echo "No trainer ID provided.";
    exit();
}

// Handle form submission (update trainer)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $specialty = $_POST['specialty'];
    $experience = $_POST['experience'];
    
    // Check if a new image is uploaded
    $profile = $trainer['profile']; // Default to existing profile image
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // New image uploaded, process it
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $new_image_name = "trainer_" . $trainer_id . "." . $image_ext;
        $image_path = "uploads/" . $new_image_name;
        
        // Move uploaded file to the server's folder
        move_uploaded_file($image_tmp, $image_path);
        
        // Update profile to new image
        $profile = "uploads/" . $new_image_name;
    }

    // Update trainer details in the database
    $update_query = "UPDATE trainers SET 
                    name = '$name', 
                    specialty = '$specialty', 
                    experience = '$experience', 
                    profile = '$profile' 
                    WHERE trainer_id = $trainer_id";
    
    if (mysqli_query($conn, $update_query)) {
        // Redirect to the manage trainers page
        header("Location: managetrainers.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Trainer</title>
    <link rel="icon" type="image/svg+xml" href="/gym/includes/image/logo.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background:rgb(187, 187, 187);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .edit-container {
            background:rgb(239, 239, 239);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }

        .edit-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }

        label {
            display: block;
            margin: 1rem 0 0.25rem;
            font-weight: 600;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 0.75rem;
        }

        img {
            display: block;
            margin: 0.5rem 0 1.5rem;
            max-width: 100px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #007BFF;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-link {
            margin-top: 1rem;
            text-align: center;
        }

        .back-link a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="edit-container">
    <h2>Edit Trainer</h2>

    <form method="POST" action="updatetrainer.php?id=<?= $trainer['trainer_id'] ?>" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($trainer['name']) ?>" required>

        <label for="specialty">Specialty:</label>
        <input type="text" name="specialty" id="specialty" value="<?= htmlspecialchars($trainer['specialty']) ?>" required>

        <label for="experience">Experience (Years):</label>
        <input type="number" name="experience" id="experience" value="<?= htmlspecialchars($trainer['experience']) ?>" required>

        <label for="image">Profile Image (Leave empty if no change):</label>
        <input type="file" name="image" id="image" accept="image/*">

        <img src="<?= htmlspecialchars($trainer['profile']) ?>" alt="Current Trainer Image">

        <button type="submit">Update Trainer</button>
    </form>

    <div class="back-link">
        ← <a href="managetrainers.php">Back to Trainers</a>
    </div>
</div>

</body>
</html>
