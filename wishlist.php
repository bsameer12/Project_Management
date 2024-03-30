<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="wishlist.css">
    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php
        include("without_session_navbar.php");
    ?>
    <section id="wishlist" class="product-list">
        <h1>My Wishlist</h1> <!-- Adding the heading for the wishlist -->
        <!-- Product items dynamically generated here -->
        <div class="product">
            <img src="caviber_image.jpg" alt="Product 1">
            <div class="product-details">
                <h2>Caviber</h2>
                <p class="availability">Availability: <span class = "out-of-stock"> Out of stock </span></p>
                <p>Price: $19.99</p>
            </div>
            <button class="remove-button">Remove</button>
        </div>

        <!-- Repeat the above structure for each wishlist item -->
        <div class="product">
            <img src="chese_image.jpg" alt="Product 1">
            <div class="product-details">
                <h2>Itlian Cheese</h2>
                <p class="availability" >Availability: <span class="in-stock"> In stock</span></p>
                <p>Price: $19.99</p>
            </div>
            <button class="remove-button">Remove</button>
        </div>
        <div class="product">
            <img src="pork_image.jpeg" alt="Product 1">
            <div class="product-details">
                <h2>Pork Steak</h2>
                <p class="availability" >Availability: <span class="in-stock"> In stock</span></p>
                <p>Price: $19.99</p>
            </div>
            <button class="remove-button">Remove</button>
        </div>
    </section>
        <?php
        include("footer.php");
    ?>
    <script src="without_session_navbar.js"></script>
</body>
</html>