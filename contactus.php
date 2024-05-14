<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="contactus.css">
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
    <div id="contact-container" class="container">
    <h2>Contact Us</h2>
    <form action="#" method="post">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label for="phone">Contact No.</label>
            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
        </div>
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="Enter the subject" required>
        </div>
        <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" placeholder="Enter your message" required></textarea>
        </div>
        <input type="submit" value="Submit Your Query">
    </form>
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