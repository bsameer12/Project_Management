<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="faqs.css">
    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
</head>
<body>
    <?php
         require("navbar_switching.php");
         includeNavbarBasedOnSession();
    ?>
    <div class="faq-container" id="faq-container">
    <h2>FAQs</h2>
    <div class="faq-item">
        <div class="question">
            <h3>Question 1: What is HudderFoods?</h3>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="answer">
            <p>HudderFoods is an e-commerce platform that connects customers with local food traders and merchants.</p>
        </div>
    </div>
    <div class="faq-item">
        <div class="question">
            <h3>Question 2: How can I order from HudderFoods?</h3>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="answer">
            <p>You can order from HudderFoods by browsing through our selection of food items, adding them to your cart, and completing the checkout process.</p>
        </div>
    </div>
    <div class="faq-item">
        <div class="question">
            <h3>Question 3: How can I order from HudderFoods?</h3>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="answer">
            <p>You can order from HudderFoods by browsing through our selection of food items, adding them to your cart, and completing the checkout process.</p>
        </div>
    </div>
    <!-- Add more FAQ items as needed -->
</div>
<?php
        include("footer.php");
    ?>

    <script src="without_session_navbar.js"></script>
    <script src="index.js"></script>
    <script src="faqs.js"></script>
    <!-- linking external js file -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</body>
</html>