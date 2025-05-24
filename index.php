<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitZone Fitness</title>
    <link rel="icon" type="image/svg+xml" href="/gym/includes/image/logo.svg">
    
</head>
<body>
<style>
body {
    margin: 0;
    padding: 0;
    font-family: 'Montserrat', sans-serif;
    background: url('includes/image/pexels-victorfreitas-841130.jpg') no-repeat center center fixed;
    background-size: cover;
}

.logo {
    font-size: 2.5rem;
    color: var(--text-color);
    font-weight: 800;
    cursor: pointer;
    transition: 0.3s ease-in-out;
}

.logo:hover {
    transform: scale(1.1);
}

span {
    color: #40E0D0;
}

.header {
    background: #000;
    color: white;
    padding: 20px 0;
}

.header .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
}

.header h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: 28px;
}

nav a {
    color: hsl(0, 0.00%, 87.50%);
    margin-left: 15px;
    text-decoration: none;
    transition: color 0.2s;
    font-size: 18px;
}

nav a:hover {
    color:hsl(0, 0.00%, 39.60%);
}

.hero {
    color: white;
    text-align: center;
    padding: 100px 20px;
}

.hero h2 {
    font-size: 36px;
    font-family: 'Montserrat', sans-serif;
    margin-bottom: 10px;
}
.hero p {
    font-size: 20px;
    margin-bottom: 20px;
    font-family: 'Montserrat', sans-serif;
}

.button {
    background: #000000;
    color: #ffffff;
    padding: 12px 24px;
    text-decoration: none;
    display: inline-block;
    margin-top: 20px;
    border-radius: 8px;
    font-size: 18px;
    transition: background 0.3s;
}
.button:hover {
    background: #40E0D0;
    transform: scale(1.08);
    transition: background 0.3s, transform 0.3s;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
}

.features {
    display: flex;
    justify-content: space-between;
    margin: 60px auto;
    gap: 20px;
    flex-wrap: wrap;
}

.feature-box {
    flex: 1;
    padding: 28px 22px;
    background: rgba(255, 255, 255, 0.13);
    border-radius: 18px;
    box-shadow: 0 4px 18px rgba(64, 224, 208, 0.13), 0 1.5px 8px rgba(0,0,0,0.07);
    text-align: center;
    transition: transform 0.25s, box-shadow 0.25s;
    border: 1.5px solid rgba(64, 224, 208, 0.18);
    backdrop-filter: blur(2px);
}

.feature-box h3 {
    color: #40E0D0;
    font-size: 1.3rem;
    margin-bottom: 12px;
    font-weight: 700;
    letter-spacing: 0.5px;
}
.feature-box p {
    color:rgb(0, 0, 0);
    font-style: bold;
    font-size: 1rem;
    margin: 0;
    opacity: 0.85;
}

.feature-box:hover {
    transform: translateY(-8px) scale(1.04);
    box-shadow: 0 8px 32px rgba(64, 224, 208, 0.18), 0 3px 16px rgba(0,0,0,0.10);
    background: rgba(255,255,255,0.22);
}

.footer {
    background: #000;
    color: #fff;
    text-align: center;
    padding: 20px 0;
    margin-top: 40px;
}

html {
    scroll-behavior: smooth;
}

.programs {
    padding: 60px 20px;
    background-color: rgba(9, 65, 77, 0.9);
    text-align: center;
    border-radius: 30px;
    margin: 40px auto;
    width: 90%;
    max-width: 1200px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.programs h2 {
    color: #fff;
    font-size: 32px;
    margin-bottom: 40px;
    font-family: 'Montserrat', sans-serif;
}

.program-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.program-card {
    width: 240px;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.program-card:hover {
    transform: scale(1.05);
}

.program-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 10px;
}

.program-card h3 {
    margin-top: 15px;
    font-size: 18px;
    font-family: 'Montserrat', sans-serif;
}

.trainers {
    padding: 60px 20px;
    background-color: rgba(9, 65, 77, 0.9);
    text-align: center;
    border-radius: 30px;
    margin: 40px auto;
    width: 90%;
    max-width: 1200px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.trainers h2 {
    color: #fff;
    font-family: 'Montserrat', sans-serif;
    font-size: 32px;
    margin-bottom: 40px;
}

.trainer-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 50px;
}

.trainer-card {
    width: 240px;
    padding: 20px;
    background-color: #f4f4f4;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.trainer-card:hover {
    transform: translateY(-5px);
}

.trainer-card img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: 12px;
}

.trainer-card h3 {
    font-family: 'Montserrat', sans-serif;
    margin: 10px 0 5px;
}

.trainer-card p {
    font-size: 14px;
    color: #444;
}

.train-btn {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
    box-sizing: border-box;
    font-size: 16px;
}

.memberships {
    padding: 60px 20px;
    background-color: rgba(9, 65, 77, 0.9);
    text-align: center;
    border-radius: 30px;
    margin: 40px auto;
    width: 90%;
    max-width: 1200px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.memberships h2 {
    color: #fff;
    font-family: 'Montserrat', sans-serif;
    font-size: 32px;
    margin-bottom: 40px;
}

.membership-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.membership-card {
    width: 240px;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.membership-card:hover {
    transform: scale(1.05);
}

.membership-card h3 {
    margin-top: 15px;
    font-size: 18px;
    font-family: 'Montserrat', sans-serif;
    color: #333;
}

.membership-card p {
    font-size: 14px;
    color: #444;
    margin: 8px 0;
}

.membership-card .price {
    font-size: 16px;
    font-weight: bold;
    color: #f20202;
    margin: 10px 0;
}

.membership-card .choose-btn {
    display: block;
    background: #000;
    color: #fff;
    padding: 10px;
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
    margin-top: 15px;
    transition: background 0.3s;
}

.membership-card .choose-btn:hover {
    background: #f20202;
}
</style>

    <header class="header">
        <div class="container">
            <img src="includes/image/logo.svg" alt="Logo" style="width: 50px; height: 50px;">
            <a class="logo">FitZone<span> Fitness Center</span></a>
            <nav>
                <a href="index.php">Home</a>
                <a href="#programs">Programs</a>
                <a href="#trainers">Trainers</a>
                <a href="#memberships">Memberships</a>
                <a href="includes/about_us.php">About Us</a>
                <a href="includes/login.php">Login</a>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h2 style="color: #ffffff; 
                       text-shadow: -2px -2px #000000, 2px -2px #000000, -2px 2px #000000, 2px 2px #000000;">
                Welcome to Your Transformation Journey
            </h2>
            <p style="color: #ffffff; 
                      text-shadow: -1px -1px #000000, 1px -1px #000000, -1px 1px #000000, 1px 1px #000000;">
                Join us today and start your fitness journey!
            </p>
            <a href="includes/register.php" class="button">Get Started</a>
        </div>
    </section>

    <section class="features container">
        <div class="feature-box">
            <h3>State-of-the-Art Equipment</h3>
            <p>Modern machines to help you reach your goals faster.</p>
        </div>
        <div class="feature-box">
            <h3>Expert Trainers</h3>
            <p>Work with certified professionals to level up your fitness game.</p>
        </div>
        <div class="feature-box">
            <h3>Flexible Memberships</h3>
            <p>Plans that suit your time, goals, and budget.</p>
        </div>
    </section>

    <section id="programs" class="programs container">
        <h2>Our Programs</h2>
        <div class="program-grid">
            <div class="program-card">
                <img src="program_img/strength.jpg" alt="Strength Training">
                <h3>Strength Training</h3>
                <p>Focus on building muscle, improving balance, and toning up.</p>
            </div>
            <div class="program-card">
                <img src="program_img/cardio.jpg" alt="Cardio">
                <h3>Cardio</h3>
                <p>High-energy workouts to boost your endurance and burn fat.</p>
            </div>
            <div class="program-card">
                <img src="program_img/yoga.jpg" alt="Yoga">
                <h3>Yoga</h3>
                <p>Improve flexibility and reduce stress with guided sessions.</p>
            </div>
            <div class="program-card">
                <img src="program_img/personal.jpg" alt="Personal Training">
                <h3>Personal Training</h3>
                <p>One-on-one coaching tailored to your goals and pace.</p>
            </div>
        </div>
    </section>

    <section id="trainers" class="trainers container">
        <h2>Meet Our Trainers</h2>
        <div class="trainer-grid">
            <?php
            // Connect to database
            $conn = new mysqli("localhost", "root", "", "gym");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT name, specialty, experience, profile FROM trainers";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="trainer-card">';
                    echo '<img src="includes/admin/' . $row["profile"] . '" alt="' . $row["name"] . '" class="trainer-img">';
                    echo '<h3>' . ucfirst($row["name"]) . '</h3>';
                    echo '<p><strong>Specialty:</strong> ' . $row["specialty"] . '</p>';
                    echo '<p><strong>Experience:</strong> ' . $row["experience"] . ' year(s)</p>';
                    echo '</div>';
                }
            } else {
                echo "<p>No trainers found.</p>";
            }
            $conn->close();
            ?>
        </div>
    </section>

    <section id="memberships" class="memberships container">
        <h2>Our Membership Plans</h2>
        <div class="membership-grid">
            <?php
            // Connect to database
            $conn = new mysqli("localhost", "root", "", "gym");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT name, price, duration, description FROM memberships";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="membership-card">';
                    echo '<h3>' . htmlspecialchars($row["name"]) . '</h3>';
                    echo '<p class="price">$' . htmlspecialchars($row["price"]) . '</p>';
                    echo '<p><strong>Duration:</strong> ' . htmlspecialchars($row["duration"]) . ' days</p>';
                    echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
                    echo '<a href="includes/Users/membership.php" class="choose-btn">Choose Plan</a>';
                    echo '</div>';
                }
            } else {
                echo "<p>No membership plans available.</p>";
            }
            $conn->close();
            ?>
        </div>
    </section>

<?php include __DIR__ . '../includes/footer.php'; ?>
 
</body>
</html>