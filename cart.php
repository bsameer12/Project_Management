<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="cart.css">
    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
</head>
<body>
    <?php
        include("without_session_navbar.php");
    ?>
    <div class="container_cat">
    <section class="cart-section" id="cart-section">
    <div class="cart-container">
    <h3>Cart</h3>
        <!-- Left side - Products -->
        <div class="products">
            <div class="product-item">
                <img src="caviber_image.jpg" alt="Product 1">
                <div class="product-details">
                    <h3>Product 1</h3>
                    <div class="quantity">
                    <button class="decrement" id="decrementBtn">-</button>
                    <input type="number" value="1" min="1" id="quantityInput">
                    <button class="increment" id="incrementBtn">+</button>
                    </div>
                    <p class="price">$10.00</p>
                    <button class="delete">Delete</button>
                </div>
            </div>
            <div class="product-item">
                <img src="caviber_image.jpg" alt="Product 1">
                <div class="product-details">
                    <h3>Product 1</h3>
                    <div class="quantity">
                    <button class="decrement" id="decrementBtn">-</button>
                    <input type="number" value="1" min="1" id="quantityInput">
                    <button class="increment" id="incrementBtn">+</button>
                    </div>
                    <p class="price">$10.00</p>
                    <button class="delete">Delete</button>
                </div>
            </div>
            <div class="product-item">
                <img src="caviber_image.jpg" alt="Product 1">
                <div class="product-details">
                    <h3>Product 1</h3>
                    <div class="quantity">
                    <button class="decrement" id="decrementBtn">-</button>
                    <input type="number" value="1" min="1" id="quantityInput">
                    <button class="increment" id="incrementBtn">+</button>
                    </div>
                    <p class="price">$10.00</p>
                    <button class="delete">Delete</button>
                </div>
        </div>
        <div class="product-item">
                <img src="caviber_image.jpg" alt="Product 1">
                <div class="product-details">
                    <h3>Product 1</h3>
                    <div class="quantity">
                    <button class="decrement" id="decrementBtn">-</button>
                    <input type="number" value="1" min="1" id="quantityInput">
                    <button class="increment" id="incrementBtn">+</button>
                    </div>
                    <p class="price">$10.00</p>
                    <button class="delete">Delete</button>
                </div>
        </div>
        <div class="product-item">
                <img src="caviber_image.jpg" alt="Product 1">
                <div class="product-details">
                    <h3>Product 1</h3>
                    <div class="quantity">
                    <button class="decrement" id="decrementBtn">-</button>
                    <input type="number" value="1" min="1" id="quantityInput">
                    <button class="increment" id="incrementBtn">+</button>
                    </div>
                    <p class="price">$10.00</p>
                    <button class="delete">Delete</button>
                </div>
        </div>
        </div>
    </div>
</section>

<!-- Summary and Discount Section -->
<section class="summary-section" id="summary-section">
    <div class="summary">
        <h3>Summary</h3>
        <p>Number of items: 3</p>
        <p>Total price: $30.00</p>
    </div>
    <div class="discount">
        <h3>Apply Discount</h3>
        <form action="#" method="post">
            <input type="text" name="discount-code" placeholder="Enter code">
            <button type="submit">Apply</button>
        </form>
    </div>
    <div class="total">
        <h3>Total</h3>
        <p>Net total: $30.00</p>
        <p>Discount: $0.00</p>
        <p>Final total: $30.00</p>
    </div>
    <button class="checkout">Checkout</button>
</section>
    </div>
        <?php
        include("footer.php");
    ?>

    <script src="without_session_navbar.js"></script>
    <script src="cart.js"></script>

    <!-- linking external js file -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</body>
</html>