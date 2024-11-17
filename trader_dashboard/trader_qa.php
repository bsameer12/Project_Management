<?php
include("trader_session.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QA</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="trader_navbar.css">
    <link rel="stylesheet" href="trader_qa.css">
    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Link to fontawesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <?php
        include("trader_navbar.php");
    ?>
    <div class="container">
        <div class="profile-container">
            <img src="../profile.jpg" alt="Profile Picture" class="profile-pic">
            <h2 class="username">John Doe</h2>
        </div>
        <div class="form-container">
            <form id="userReviewForm">
                <label for="userReview" class="review-label">User Rating:</label>
                <input type="text" id="userReview" name="userReview" class="review-textbox">
                <label for="userReview" class="review-label">User Review:</label>
                <textarea id="userReview" name="userReview" class="review-textbox" rows="4"></textarea>
                <label for="reply" class="reply-label">Reply:</label>
                <textarea id="reply" name="reply" class="reply-textbox" rows="4"></textarea>
                <input type="submit" class="submit-btn" value=" Save Reply" >
                <button type="button" class="cancel-btn" onclick="window.location.href='trader_review.php'">Cancel</button>
            </form>
        </div>
    </div>
    <script src="trader_navbar.js"></script>
</body>
    </html>