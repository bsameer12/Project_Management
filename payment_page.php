<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="payment_page.css">
    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php
        include("session/session.php");
        include("session_navbar.php");
    ?>
    <div class="container_pay">
        <h2 id="order-summary-title">Order Summary</h2>
        <div class="order-details">
            <div class="detail">
                <span class="detail-title">Total:</span>
                <span class="detail-value" id="total">100</span>
            </div>
            <div class="detail">
                <span class="detail-title">Net Total:</span>
                <span class="detail-value" id="net-total">90</span>
            </div>
            <div class="detail">
                <span class="detail-title">Discount Amount:</span>
                <span class="detail-value" id="discount">10</span>
            </div>
            <div class="detail">
                <span class="detail-title">Number of Items:</span>
                <span class="detail-value" id="item-count">5</span>
            </div>
        </div>
        <div class="payment-section">
            <h3 id="payment-title">Make a Payment</h3>
            <button id="payment-button">
                <img src="paypal_logo.png" alt="PayPal Logo">
            </button>
        </div>
</div>
</div>
<?php
        include("footer.php");
    ?>
    <script src="product.js"> </script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="without_session_navbar.js"> </script>
</body>
</html>