<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="/gym/includes/image/logo.svg">
</head>
<body>
    
</body>
</html>
<?php
session_start();
session_destroy();
echo '<script>
    window.location.href = "../index.php";
</script>'; // Redirect to home page
exit;
?>
