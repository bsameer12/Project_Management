<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Query Success</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="query_success.css">
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
    <div class="container" id="response-container">
    <div class="row">
    <div class="icon">
        <i class="fas fa-check-circle"></i> <!-- Assuming you're using Font Awesome for the green right icon -->
    </div>
    </div>
    <div class="row">
    <div class="message">
        <p>Your query has been received, and we will get in touch shortly. Be patient and enjoy our platform!</p>
    </div>
    </div>
    <div class="row">
    <div class="button">
        <a href="#" class="return-button">Return to Home</a>
    </div>
    </div>
</div>
<?php
        include("footer.php");
    ?>

    <script src="without_session_navbar.js"></script>
    <script src="index.js"></script>
    <!-- linking external js file -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</body>
</html>