<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HudderFoods</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="about.css">
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
    <!-- about section starts here  -->
<section class="about" id="about">
    <!-- heding section -->
    <h3 class="sub-heading"> About us </h3>
    <h1 class="heading"> why choose us? </h1>
    <div class="row">
        <!-- linking image-->
        <div class="image">
            <img src="logo.png" alt="">
        </div>
        <!-- main context -->
        <div class="content">
            <h3>"HudderFoods Grocery at Finger Tip"</h3>
            <p>"Indulge in Flavorful Delights, Exclusively at HudderFoods - Where Taste Meets Quality! Explore the Taste of Great Britain with HudderFoods - Your Trusted Destination for Quality Ingredients, Crafted with British Excellence."
            </p>
            <div class="icons-container">
                <div class="icons">
                    <!-- favicon code for delivery logo -->
                    <i class="fas fa-shipping-fast"></i>
                    <span>Pick Up </span>
                </div>
                <div class="icons">
                    <!-- favicon code for dollars logo -->
                    <i class="fas fa-dollar-sign"></i>
                    <span>Easy payments</span>
                </div>
                <div class="icons">
                    <!-- favicon code for headphone logo -->
                    <i class="fas fa-headset"></i>
                    <span>24/7 service</span>
                </div>
            </div>
        </div>

    </div>

</section>
    <?php
        include("footer.php");
    ?>
    <script src="without_session_navbar.js"></script>
</body>
</html>