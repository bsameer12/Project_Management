<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="category.css">
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
    <section class="category-section" id="category-section">
    <h2>Categories</h2>
    <div class="category-container">
        <div class="category-item">
            <a href="page1.html"><img src="logo.png" alt="Category 1"></a>
            <p>fish</p>
        </div>
        <!-- Repeat the above div for other categories -->
        <div class="category-item">
            <a href="page1.html"><img src="logo.png" alt="Category 1"></a>
            <p>Ice</p>
        </div>
        <div class="category-item">
            <a href="page1.html"><img src="logo.png" alt="Category 1"></a>
            <p>Rum</p>
        </div>
        <div class="category-item">
            <a href="page1.html"><img src="logo.png" alt="Category 1"></a>
            <p>Vodka</p>
        </div>
        <div class="category-item">
            <a href="page1.html"><img src="logo.png" alt="Category 1"></a>
            <p>Car</p>
        </div>
        <div class="category-item">
            <a href="page1.html"><img src="logo.png" alt="Category 1"></a>
            <p>Meat</p>
        </div>
        <div class="category-item">
            <a href="page1.html"><img src="logo.png" alt="Category 1"></a>
            <p>Beer</p>
        </div>
    </div>
</section>
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