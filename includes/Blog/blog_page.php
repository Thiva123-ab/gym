<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Blog - Fitzone Fitness</title>
    <link rel="icon" type="image/svg+xml" href="/gym/includes/image/logo.svg">

    <style>
        :root {
            --primary-color: #ff4757;
            --primary-hover: #e84148;
            --text-dark: #222;
            --text-light: #555;
            --bg-light: rgba(225, 225, 225, 0.9);
            --shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: url('/gym/includes/image/background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            background: rgba(34, 34, 34, 0.9);
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        .header h1 {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            font-family: 'Montserrat', sans-serif;
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        nav a {
            color: #ddd;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            transition: var(--transition);
        }

        nav a:hover {
            color: var(--primary-color);
        }

        nav .go-back {
            position: relative;
            padding-left: 1.5rem;
        }

        nav .go-back::before {
            content: '←';
            position: absolute;
            left: 0.25rem;
            font-size: 1rem;
            transition: var(--transition);
        }

        nav .go-back:hover::before {
            transform: translateX(-3px);
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 2rem auto;
            flex-grow: 1;
        }

        .blog-section {
            display: flex;
            flex-wrap: wrap;
            gap: 1.25rem;
            justify-content: center;
        }

        .blog-post {
            background: var(--bg-light);
            width: 100%;
            max-width: 360px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }

        .blog-post:nth-child(2) { animation-delay: 0.1s; }
        .blog-post:nth-child(3) { animation-delay: 0.2s; }
        .blog-post:nth-child(4) { animation-delay: 0.3s; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .blog-post:hover {
            transform: translateY(-5px);
        }

        .blog-post img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .blog-content {
            padding: 1.25rem;
        }

        .blog-content h2 {
            font-size: 1.375rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-family: 'Montserrat', sans-serif;
        }

        .blog-content p {
            font-size: 0.9375rem;
            color: var(--text-light);
            line-height: 1.6;
            margin-bottom: 0.75rem;
        }

        .extended-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease, opacity 0.5s ease;
            opacity: 0;
        }

        .extended-content.show {
            max-height: 500px;
            opacity: 1;
        }

        .extended-content h4 {
            font-size: 1.125rem;
            color: var(--text-dark);
            margin: 0.625rem 0;
        }

        .extended-content p {
            font-size: 0.875rem;
            color: var(--text-light);
            line-height: 1.6;
            margin-bottom: 0.5rem;
        }

        .highlight {
            border-left: 4px solid var(--primary-color);
            padding-left: 0.625rem;
            margin-bottom: 0.625rem;
        }

        .read-more {
            display: inline-block;
            background: var(--primary-color);
            color: #fff;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.875rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .read-more:hover {
            background: var(--primary-hover);
        }

        .read-more.active::after {
            content: " (Hide Details)";
        }

        .footer {
            background: #222;
            color: #ccc;
            text-align: center;
            padding: 1.25rem 0;
            margin-top: auto;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.5rem;
            }

            nav a {
                font-size: 0.875rem;
                margin: 0.25rem 0.625rem;
            }

            .container {
                margin: 1.25rem auto;
            }

            .blog-post {
                max-width: 100%;
            }

            .blog-post img {
                height: 180px;
            }

            .blog-content h2 {
                font-size: 1.25rem;
            }

            .blog-content p, .extended-content p {
                font-size: 0.875rem;
            }

            .extended-content h4 {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 1.25rem;
            }

            nav a {
                font-size: 0.75rem;
                margin: 0.2rem 0.5rem;
            }

            nav .go-back {
                padding-left: 1.25rem;
            }

            nav .go-back::before {
                font-size: 0.875rem;
            }

            .blog-post {
                margin: 0 0.625rem;
            }

            .blog-post img {
                height: 150px;
            }

            .blog-content {
                padding: 0.9375rem;
            }

            .blog-content h2 {
                font-size: 1.125rem;
            }

            .blog-content p, .extended-content p {
                font-size: 0.8125rem;
            }

            .extended-content h4 {
                font-size: 0.9375rem;
            }

            .read-more {
                padding: 0.5rem 1rem;
                font-size: 0.8125rem;
            }

            .footer {
                font-size: 0.75rem;
                padding: 0.9375rem 0;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Fitzone Nutrition Blog</h1>
            <nav>
                <a href="/gym/index.php">Home</a>
                <a class="go-back" onclick="window.history.back()">Go Back</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="blog-section">
            <div class="blog-post">
                <img src="/gym/includes/image/istockphoto-1457411409-1024x1024.jpg" alt="Protein-rich foods">
                <div class="blog-content">
                    <h2>The Power of Protein for Muscle Growth</h2>
                    <p>Protein is essential for muscle repair and growth. Learn how to incorporate high-quality protein sources into your diet to fuel your workouts.</p>
                    <div class="extended-content">
                        <div class="highlight">
                            <h4>Key Facts</h4>
                            <p><strong>Daily Requirement:</strong> Aim for 0.8-1.2g of protein per kg of body weight, or up to 1.6-2.2g for intense training.</p>
                            <p><strong>Best Sources:</strong> Lean meats (chicken, turkey), fish (salmon, tuna), eggs, tofu, lentils, Greek yogurt.</p>
                        </div>
                        <h4>Tips for Gym-Goers</h4>
                        <p>Consume protein within 30-60 minutes post-workout to maximize recovery. Pair with carbs (e.g., chicken and sweet potato) to replenish glycogen. Protein shakes are convenient, but whole foods offer better nutrient density.</p>
                    </div>
                    <a class="read-more" href="https://www.bbcgoodfood.com/health/fitness/how-much-protein-to-build-muscle">Read More</a>
                </div>
            </div>

            <div class="blog-post">
                <img src="/gym/includes/image/pexels-andres-ayrton-6551140.jpg" alt="Hydration tips">
                <div class="blog-content">
                    <h2>Stay Hydrated: Why Water Matters</h2>
                    <p>Proper hydration boosts performance and recovery. Discover tips to stay hydrated during workouts and the best electrolyte-rich drinks.</p>
                    <div class="extended-content">
                        <div class="highlight">
                            <h4>Key Facts</h4>
                            <p><strong>Daily Water Intake:</strong> Aim for 2-3 liters daily, more during intense workouts or hot weather.</p>
                            <p><strong>Electrolytes:</strong> Sodium, potassium, and magnesium are critical for muscle function and preventing cramps.</p>
                        </div>
                        <h4>Tips for Gym-Goers</h4>
                        <p>Drink 500ml of water 1-2 hours before exercising. Sip water every 15-20 minutes during workouts. Post-workout, try coconut water or a low-sugar electrolyte drink to restore balance.</p>
                    </div>
                    <a class="read-more" href="https://www.allhealthmatters.co.uk/post/why-staying-hydrated-matters">Read More</a>
                </div>
            </div>

            <div class="blog-post">
                <img src="/gym/includes/image/background.jpg" alt="Meal prep ideas">
                <div class="blog-content">
                    <h2>Meal Prep for Fitness Success</h2>
                    <p>Save time and stay on track with your nutrition goals. Explore easy meal prep ideas that align with your gym routine.</p>
                    <div class="extended-content">
                        <div class="highlight">
                            <h4>Key Facts</h4>
                            <p><strong>Portion Control:</strong> Use containers to pre-portion meals for balanced macros (protein, carbs, fats).</p>
                            <p><strong>Storage:</strong> Most prepped meals stay fresh in the fridge for 3-5 days or can be frozen.</p>
                        </div>
                        <h4>Tips for Gym-Goers</h4>
                        <p>Plan meals weekly: Include a protein (e.g., grilled chicken), carb (e.g., quinoa), and veggies. Prep snacks like boiled eggs or hummus with veggies. Batch-cook on Sundays to save time.</p>
                    </div>
                    <a class="read-more" href="https://www.muscleandfitness.com/nutrition/healthy-eating/beginners-guide-meal-prepping/">Read More</a>
                </div>
            </div>

            <div class="blog-post">
                <img src="/gym/includes/image/pexels-nastyasensei-66707-3951307.jpg" alt="Healthy carbs">
                <div class="blog-content">
                    <h2>Carbs: Your Energy Source</h2>
                    <p>Carbohydrates are key for fueling your workouts. Understand the difference between simple and complex carbs for maximum performance.</p>
                    <div class="extended-content">
                        <div class="highlight">
                            <h4>Key Facts</h4>
                            <p><strong>Types of Carbs:</strong> Simple carbs (fruits, honey) provide quick energy; complex carbs (oats, brown rice) offer sustained energy.</p>
                            <p><strong>Timing:</strong> Consume complex carbs 2-3 hours before workouts and simple carbs during or post-workout.</p>
                        </div>
                        <h4>Tips for Gym-Goers</h4>
                        <p>Start your day with oatmeal or whole-grain toast for steady energy. Post-workout, pair a banana with a protein shake for quick recovery. Avoid high-sugar carbs outside workout windows.</p>
                    </div>
                    <a class="read-more" href="https://www.healthline.com/nutrition/carbohydrate-functions">Read More</a>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../footer.php'; ?>

    <script>
        function toggleContent(element) {
            const content = element.previousElementSibling;
            if (content && content.classList.contains('extended-content')) {
                const isVisible = content.classList.contains('show');
                content.classList.toggle('show', !isVisible);
                element.classList.toggle('active', !isVisible);
            } else {
                console.error('Extended content not found for:', element);
            }
        }
    </script>
</body>
</html>