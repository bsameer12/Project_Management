<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Page</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="search_page.css">
    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php
        include("without_session_navbar.php");
    ?>
    <div class="container_search">
        <div class="left-sidebar">
            <h2>Filter</h2>
            <form class="filter-form" id="price-filter">
            <h3>Price</h3>
            <label for="min-price">Min:</label>
            <select name="min-price" id="min-price">
                <option value="0">0</option>
                <option value="10">$10</option>
                <option value="20">$20</option>
                <option value="30">$30</option>
                <!-- Add more options as needed -->
            </select>
            <label for="max-price">Max:</label>
            <select name="max-price" id="max-price">
                <option value="50">$50</option>
                <option value="100">$100</option>
                <option value="200">$200</option>
                <option value="500">$500</option>
                <!-- Add more options as needed -->
            </select>
            </form>
            <form class="filter-form" id="category-filter">
            <h3>Category</h3>
            <label for="category1"> <input type="checkbox" id="category1" name="category1"> Category 1</label><br>
            <label for="category2"> <input type="checkbox" id="category2" name="category2"> Category 2</label><br>
            <label for="category3"> <input type="checkbox" id="category3" name="category3"> Category 3</label><br>
            <!-- Add more checkbox inputs for categories as needed -->
            </form>
            <form class="filter-form" id="rating-filter">
                <h3>Rating</h3>
                <label for="rating">Rating:</label>
                <select name="rating" id="rating">
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select>
            </form>
            <form class="filter-form" id="brand-filter">
                <h3>Brand</h3>
                <label><input type="checkbox" name="brand" value="brand1"> Brand 1</label><br>
                <label><input type="checkbox" name="brand" value="brand2"> Brand 2</label><br>
                <label><input type="checkbox" name="brand" value="brand3"> Brand 3</label><br>
                <!-- Add more checkbox inputs for other brands -->
            </form>
        </div>

        <div class="right-content">
            <div class="top-section">
                <p>This is a paragraph.</p>
                <form class="sort-form">
                    <label for="sort-by">Sort By: <select name="sort-by" id="sort-by">
                        <option value="price-low-to-high">Price: Low to High</option>
                        <option value="price-high-to-low">Price: High to Low</option>
                        <option value="rating-high-to-low">Rating: High to Low</option>
                        <option value="rating-low-to-high">Rating: Low to High</option>
                        <!-- Add more sorting options as needed -->
                    </select></label>
                </form>
            </div>
            <div class="bottom-section">
                <!-- Container for other content -->
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
        </div>
    </div>
    </div>
    <?php
        include("footer.php");
    ?>
    <script src="without_session_navbar.js"> </script>
</body>
</html>