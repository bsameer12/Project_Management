<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="product.css">
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
    <div id="product_container">
    <div id="product_images">
        <div id="main_image_container">
            <img src="caviber_image.jpg" alt="Product Image" id="main_image">
        </div>
        <div id="thumbnail_images">
            <div class="thumbnail_container">
                <img src="caviber_image.jpg" alt="Thumbnail 1" class="thumbnail" data-index="0">
            </div>
            <div class="thumbnail_container">
                <img src="chese_image.jpg" alt="Thumbnail 2" class="thumbnail" data-index="1">
            </div>
            <!-- Add more thumbnail images as needed -->
            <div class="thumbnail_container">
                <img src="pork_image.jpeg" alt="Thumbnail 3" class="thumbnail" data-index="1">
            </div>
        </div>
    </div>
    <div id="product_details">
        <h2>Product Name</h2>
        <div id="price_container">
            <div id="original_price">€100</div>
            <div id="discount">-20%</div>
            <div id="discount_price">€80</div>
        </div>
        <p>Description: Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean eget tellus at sem convallis auctor ut non metus.</p>
        <div id="actions">
            <div id="quantity">
                <button id="decrease_qty">-</button>
                <input type="text" id="quantity_input" value="1">
                <button id="increase_qty">+</button>
            </div>
            <button id="add_to_wishlist"><i class="far fa-heart"></i></button>
        </div>
        <button id="add_to_cart">Add to Cart</button>
    </div>
</div>

<div id="product_info_container">
        <!-- Navbar -->
        <nav id="product_navbar">
            <button class="nav_btn" data-target="ingredients">Ingredients</button>
            <button class="nav_btn" data-target="allergy">Allergy Info</button>
            <button class="nav_btn" data-target="product_life">Product Life</button>
            <button class="nav_btn" data-target="must_know">Must Know</button>
        </nav>

        <!-- Ingredients Information -->
        <div id="ingredients_info" class="product_info">
            <h2>Ingredients</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus auctor justo nec libero feugiat.</p>
        </div>

        <!-- Allergy Information -->
        <div id="allergy_info" class="product_info">
            <h2>Allergy Information</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus auctor justo nec libero feugiat.</p>
        </div>

        <!-- Product Life Information -->
        <div id="product_life_info" class="product_info">
            <h2>Product Life</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus auctor justo nec libero feugiat.</p>
        </div>

        <!-- Must Know Information -->
        <div id="must_know_info" class="product_info">
            <h2>Must Know</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus auctor justo nec libero feugiat.</p>
        </div>
    </div>

    <div class="comment-container" id="comment-container">
        <div class="customer-comments swiper-container">
            <div class="swiper-wrapper">
                <!-- Comment 1 -->
                <div class="swiper-slide">
                    <div class="comment-card">
                        <div class="customer-profile">
                            <img src="profile.jpg" alt="Customer Profile">
                        </div>
                        <div class="comment-details">
                            <div class="rating">
                                <p> Customer 1 </p>
                                <span class="stars">&#9733;&#9733;&#9733;&#9733;&#9734;</span>
                            </div>
                            <div class="comment-text">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                            </div>
                            <div class="actions">
                                <button class="like-btn">&#x1F44D; Like</button>
                                <button class="reply-btn">&#9993; Reply</button>
                                <form class="reply-form" style="display: none;">
                                    <input type="text" placeholder="Your reply...">
                                    <input type="submit" value="Submit">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Comment 2 -->
                <div class="swiper-slide">
                    <div class="comment-card">
                        <div class="customer-profile">
                            <img src="profile.jpg" alt="Customer Profile">
                        </div>
                        <div class="comment-details">
                            <div class="rating">
                                <p>Customer 2</p>
                                <span class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                                
                            </div>
                            <div class="comment-text">
                                <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            </div>
                            <div class="actions">
                                <button class="like-btn">&#x1F44D; Like</button>
                                <button class="reply-btn">&#9993; Reply</button>
                                <form class="reply-form" style="display: none;">
                                    <input type="text" placeholder="Your reply...">
                                    <input type="submit" value="Submit">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Comment 3 -->
                <div class="swiper-slide">
                    <div class="comment-card">
                        <div class="customer-profile">
                            <img src="profile.jpg" alt="Customer Profile">
                        </div>
                        <div class="comment-details">
                            <div class="rating">
                                <p>Customer 3 </p>
                                <span class="stars">&#9733;&#9733;&#9733;&#9734;&#9734;</span>
                            </div>
                            <div class="comment-text">
                                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                            </div>
                            <div class="actions">
                                <button class="like-btn">&#x1F44D; Like</button>
                                <button class="reply-btn">&#9993; Reply</button>
                                <form class="reply-form" style="display: none;">
                                    <input type="text" placeholder="Your reply...">
                                    <input type="submit" value="Submit">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add pagination and navigation buttons here if needed -->
        </div>
    </div>

    <div id="other_products_container">
    <h2>Other Products from Same Seller</h2>
    <div class="product-card-container">
        <!-- Product Card 1 -->
        <div class="product-card">
            <div class="product-details">
            <div class="product-image">
                <img src="caviber_image.jpg" alt="Product Image">
            </div>
                <p class="product-name">Product Name 1</p>
                <div class="product-rating">
                    <span class="stars">&#9733;&#9733;&#9733;&#9733;&#9734;</span>
                    <span class="total-reviews">(15)</span>
                </div>
                <div id="price_container">
                    <div id="original_price">€100</div>
                    <div id="discount">-20%</div>
                    <div id="discount_price">€80</div>
                </div>
                <div class="button-container">
                    <button class="add-to-cart-btn">Add to Cart</button>
                    <a href="#" class="wishlist-btn"><i class="fas fa-heart"></i></a>
                </div>
            </div>
        </div>
        <!-- Add more product cards as needed -->
        <div class="product-card">
            <div class="product-details">
            <div class="product-image">
                <img src="caviber_image.jpg" alt="Product Image">
            </div>
                <p class="product-name">Product Name 2</p>
                <div class="product-rating">
                    <span class="stars">&#9733;&#9733;&#9733;&#9733;&#9734;</span>
                    <span class="total-reviews">(15)</span>
                </div>
                <div id="price_container">
                    <div id="original_price">€100</div>
                    <div id="discount">-20%</div>
                    <div id="discount_price">€80</div>
                </div>
                <div class="button-container">
                    <button class="add-to-cart-btn">Add to Cart</button>
                    <a href="#" class="wishlist-btn"><i class="fas fa-heart"></i></a>
                </div>
            </div>
        </div>
        <div class="product-card">
            <div class="product-details">
            <div class="product-image">
                <img src="caviber_image.jpg" alt="Product Image">
            </div>
                <p class="product-name">Product Name 3</p>
                <div class="product-rating">
                    <span class="stars">&#9733;&#9733;&#9733;&#9733;&#9734;</span>
                    <span class="total-reviews">(15)</span>
                </div>
                <div id="price_container">
                    <div id="original_price">€100</div>
                    <div id="discount">-20%</div>
                    <div id="discount_price">€80</div>
                </div>
                <div class="button-container">
                    <button class="add-to-cart-btn">Add to Cart</button>
                    <a href="#" class="wishlist-btn"><i class="fas fa-heart"></i></a>
                </div>
            </div>
        </div>
        <div class="product-card">
            <div class="product-details">
            <div class="product-image">
                <img src="caviber_image.jpg" alt="Product Image">
            </div>
                <p class="product-name">Product Name 4</p>
                <div class="product-rating">
                    <span class="stars">&#9733;&#9733;&#9733;&#9733;&#9734;</span>
                    <span class="total-reviews">(15)</span>
                </div>
                <div id="price_container">
                    <div id="original_price">€100</div>
                    <div id="discount">-20%</div>
                    <div id="discount_price">€80</div>
                </div>
                <div class="button-container">
                    <button class="add-to-cart-btn">Add to Cart</button>
                    <a href="#" class="wishlist-btn"><i class="fas fa-heart"></i></a>
                </div>
            </div>
        </div>
        <div class="product-card">
            <div class="product-details">
            <div class="product-image">
                <img src="caviber_image.jpg" alt="Product Image">
            </div>
                <p class="product-name">Product Name 5</p>
                <div class="product-rating">
                    <span class="stars">&#9733;&#9733;&#9733;&#9733;&#9734;</span>
                    <span class="total-reviews">(15)</span>
                </div>
                <div id="price_container">
                    <div id="original_price">€100</div>
                    <div id="discount">-20%</div>
                    <div id="discount_price">€80</div>
                </div>
                <div class="button-container">
                    <button class="add-to-cart-btn">Add to Cart</button>
                    <a href="#" class="wishlist-btn"><i class="fas fa-heart"></i></a>
                </div>
            </div>
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