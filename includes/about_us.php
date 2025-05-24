<?php
// Start PHP session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - FitZone Fitness Center</title>
    <link rel="icon" type="image/svg+xml" href="/gym/includes/image/logo.svg">

    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('Background/background2.jpg'); 
            background-size: cover; 
            background-position: center; 
            color: #333;
        }
        
        header {
            background-color: #1d1d1d;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        
        header h1 {
            margin: 0;
            font-size: 2.5em;
        }

        nav {
            background-color: #333;
            overflow: hidden;
        }
        
        nav a {
            color: white;
            padding: 14px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        
        nav a:hover {
            background-color: #575757;
        }
        
        .content {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .about-section {
            background-color: rgba(200, 200, 200, 0.9);
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .about-section h2 {
            color: #1d1d1d;
            font-size: 2em;
        }

        .about-section p {
            font-size: 1.1em;
            line-height: 1.6;
        }

        .go-back-button, .blog-button {
            background-color: #333;
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
            margin-top: 20px;
            text-align: center;
            display: block;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
        }

        .go-back-button:hover, .blog-button:hover {
            background-color: #575757;
        }

        .blog-card {
            background-color: rgba(200, 200, 200, 0.9);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-top: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .blog-card h2 {
            font-size: 2em;
            color: #1d1d1d;
        }

        .blog-card p {
            font-size: 1.1em;
            line-height: 1.6;
            color: #555;
        }

        footer {
            background-color: #1d1d1d;
            color: #fff;
            padding: 10px;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
        .contact-section {
            background-color: rgba(200, 200, 200, 0.9);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        .contact-section h2 {
            font-size: 2em;
            color: #1d1d1d;
            margin-bottom: 10px;
        }

        .contact-section p {
            font-size: 1.1em;
            line-height: 1.6;
            color: #444;
        }

        .contact-details p {
            margin-bottom: 15px;
        }

        .social-media a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
            margin: 0 5px;
        }

.social-media a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>

<header>
    <h1>FitZone Fitness Center</h1>
    <p>Your Fitness Journey Starts Here</p>
</header>

<div class="content">
    <!-- About Us Section -->
    <section id="about" class="about-section">
        <h2>About Us</h2>
        <p>Welcome to <strong>FitZone Fitness Center</strong>, your premier fitness destination located in the heart of <strong>Homagama</strong>. We are a newly established gym dedicated to helping you achieve your fitness goals in a welcoming and motivating environment. Whether you're just starting your fitness journey or looking to take your training to the next level, we are here to support you every step of the way.</p>
        
        <p>At <strong>FitZone Fitness Center</strong>, we offer a wide range of fitness programs tailored to meet the unique needs of each individual. Our state-of-the-art equipment, personalized training sessions, and diverse group classes are designed to provide you with everything you need to reach your fitness goals. From <strong>cardio</strong> and <strong>strength training</strong> to <strong>yoga</strong>, we have a program for every fitness level and interest.</p>
        
        <p>Our certified trainers are here to guide and support you through every workout, ensuring you get the most out of your fitness journey. With specialized training in various areas, our trainers can provide you with customized programs that align with your goals, whether you’re aiming for weight loss, muscle gain, or improved overall health.</p>
        
        <p>We understand the importance of proper nutrition in achieving your fitness goals, which is why we also offer <strong>nutrition counseling</strong> to help you make informed decisions about your diet and overall health.</p>
        
        <!-- Go Back Button -->
        <button class="go-back-button" onclick="window.history.back()">Go Back</button>
    </section>

    <!-- Blog Card Section -->
    <section class="blog-card">
        <h2>Stay Updated with Our Blog!</h2>
        <p>Visit our blog for fitness tips, workout routines, nutrition advice, and more. Stay informed and motivated on your fitness journey.</p>
        <a href="/gym/includes/Blog/blog_page.php" class="blog-button">Visit Blog Page</a>
    </section>
    <!-- Contact Us Section -->
<section class="contact-section">
    <h2>Contact Us</h2>
    <p>Have questions or need assistance? Reach out to our team – we're here to help!</p>
    
    <div class="contact-details">
        <p><strong>John Smith</strong> – Front Desk<br>📞 077-123-4567</p>
        <p><strong>Emma Johnson</strong> – Membership Inquiries<br>📞 071-987-6543</p>
        <p><strong>Mark Lee</strong> – Personal Training Coordinator<br>📞 070-222-3344</p>
    </div>

    <div class="social-media">
        <h3>Follow Us</h3>
        <p>
            📘 <a href="https://www.facebook.com">Facebook</a> |
            📸 <a href="https://www.instagram.com">Instagram</a> |
            🐦 <a href="https://www.x.com">Twitter</a>
        </p>
    </div>
</section>
</div>
<?php include 'footer.php'; ?>
</body>