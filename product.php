<?php
session_start();
$product_id = $_GET["productId"];
$user_id = isset($_SESSION["userid"]) ? $_SESSION["userid"] : 0;
$searchText = "p";

// Include the database connection
include("connection/connection.php");

// Prepare the SQL statement
$sql = "SELECT 
            PRODUCT_ID, 
            PRODUCT_NAME, 
            PRODUCT_DESCRIPTION, 
            PRODUCT_PRICE, 
            ALLERGY_INFORMATION, 
            USER_ID, 
            PRODUCT_PICTURE 
        FROM 
            PRODUCT 
        WHERE 
            PRODUCT_ID = :product_id";

// Parse the SQL statement
$stmt = oci_parse($conn, $sql);

// Bind the parameter
oci_bind_by_name($stmt, ':product_id', $product_id);

// Execute the SQL statement
oci_execute($stmt);

// Fetch the result
$row = oci_fetch_assoc($stmt);

// Assign values to individual variables
$productId = $row['PRODUCT_ID'];
$productName = $row['PRODUCT_NAME'];
$productDescription = $row['PRODUCT_DESCRIPTION'];
$productPrice = $row['PRODUCT_PRICE'];
$allergyInformation = $row['ALLERGY_INFORMATION'];
$userId = $row['USER_ID'];
$productPicture = $row['PRODUCT_PICTURE'];

// Free statement resources
oci_free_statement($stmt);

// Prepare the SQL statement
$sql = "SELECT 
r.REVIEW_SCORE, 
r.FEEDBACK, 
u.FIRST_NAME || ' ' || u.LAST_NAME AS NAME, 
u.USER_PROFILE_PICTURE 
FROM 
REVIEW r 
JOIN 
HUDDER_USER u ON r.USER_ID = u.USER_ID 
WHERE 
r.PRODUCT_ID = :product_id";

// Parse the SQL statement
$stmt = oci_parse($conn, $sql);

// Bind the parameter
oci_bind_by_name($stmt, ':product_id', $product_id);

// Execute the SQL statement
oci_execute($stmt);

// Initialize an array to store the results
$user_review = array();

// Fetch the results
while ($row = oci_fetch_assoc($stmt)) {
    $user_review[] = $row;
}

// Free statement resources
oci_free_statement($stmt);

$sql = "SELECT p.PRODUCT_ID, p.PRODUCT_NAME, p.PRODUCT_PRICE, p.PRODUCT_PICTURE, 
               AVG(r.REVIEW_SCORE) AS AVG_REVIEW_SCORE,
               COUNT(r.REVIEW_ID) AS REVIEW_COUNT
        FROM product p
        LEFT JOIN review r ON p.PRODUCT_ID = r.PRODUCT_ID
        WHERE p.IS_DISABLED = 1 AND p.USER_ID = :user_id AND ADMIN_VERIFIED=1
        GROUP BY p.PRODUCT_ID, p.PRODUCT_NAME, p.PRODUCT_PRICE, p.PRODUCT_PICTURE";


// Prepare the SQL statement
$stmt = oci_parse($conn, $sql);

// Bind the parameter
oci_bind_by_name($stmt, ':user_id', $userId);

// Execute the SQL statement
oci_execute($stmt);

// Initialize an array to store the results
$products = array();

// Fetch the results
while ($row = oci_fetch_assoc($stmt)) {
    $products[] = $row;
}

// Free statement resources
oci_free_statement($stmt);
?>

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
            <img src="product_image/<?php echo $productPicture; ?>" alt="<?php echo $productName; ?>" id="main_image">
        </div>
        <?php /* 
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
         */
        ?>
    </div>
    <div id="product_details">
        <h2><?php echo $productName; ?></h2>
        <div id="price_container">
            <div id="original_price"><?php echo $productPrice; ?> </div>
            <div id="discount">-20%</div>
            <div id="discount_price">€<?php echo $productPrice; ?></div>
        </div>
        <p><?php echo $productDescription; ?></p>
        <div id="actions">
       
            <?php /* 
            <div id="quantity">
                <button id="decrease_qty">-</button>
                <input type="text" id="quantity_input" value="1">
                <button id="increase_qty">+</button>
            </div>
             */ ?>
        </div>
        <button id="add_to_cart" onclick="addToCart(<?php echo $product_id; ?>, <?php echo $user_id; ?>, '<?php echo $searchText; ?>')">Add to Cart</button>
        <button id="add_to_wishlist" onclick="addToWishlist(<?php echo $product_id; ?>, <?php echo $user_id; ?>, '<?php echo $searchText; ?>')"><i class="far fa-heart"></i></button>
       
    </div>
</div>

<div id="product_info_container">
        <!-- Navbar -->
        <nav id="product_navbar">
            <button class="nav_btn" data-target="ingredients">Ingredients</button>
            <button class="nav_btn" data-target="allergy">Allergy Info</button>
            <?php /*
            <button class="nav_btn" data-target="product_life">Product Life</button>
            <button class="nav_btn" data-target="must_know">Must Know</button>
            */ ?>
        </nav>

        <!-- Ingredients Information -->
        <div id="ingredients_info" class="product_info">
            <h2>Ingredients</h2>
            <p><?php echo $productDescription; ?></p>
        </div>

        <!-- Allergy Information -->
        <div id="allergy_info" class="product_info">
            <h2>Allergy Information</h2>
            <p><?php echo $allergyInformation; ?></p>
        </div>

        <?php /*
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
        */ ?>
    </div>

    <div class="comment-container" id="comment-container">
        <div class="customer-comments swiper-container">
            
            <div class="swiper-wrapper">
            <?php foreach ($user_review as $review): ?>
                <!-- Comment 1 -->
                <div class="swiper-slide">
                    <div class="comment-card">
                        <div class="customer-profile">
                            <img src="profile_image/<?php echo $review['USER_PROFILE_PICTURE']; ?>" alt="<?php echo $review['NAME']; ?>">
                        </div>
                        <div class="comment-details">
                            <div class="rating">
                                <p> <?php echo $review['NAME']; ?> </p>
                                <span class="stars"> <?php
                            // Convert REVIEW_SCORE to star ratings
                            $rating = round($review['REVIEW_SCORE']);
                            for ($i = 0; $i < 5; $i++) {
                                if ($i < $rating) {
                                    echo '&#9733;'; // Full star
                                } else {
                                    echo '&#9734;'; // Empty star
                                }
                            }
                        ?></span>
                            </div>
                            <div class="comment-text">
                                <p><?php echo $review['FEEDBACK']; ?></p>
                            </div>
                            <?php /*
                            <div class="actions">
                                <button class="like-btn">&#x1F44D; Like</button>
                                <button class="reply-btn">&#9993; Reply</button>
                                <form class="reply-form" style="display: none;">
                                    <input type="text" placeholder="Your reply...">
                                    <input type="submit" value="Submit">
                                </form>
                            </div>
                            */ ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                        </div>
        </div>
    </div>

    <div id="other_products_container">
    <h2>Other Products from Same Seller</h2>
    <div class="product-card-container">
    <?php
    foreach ($products as $product):
?>
        <!-- Product Card 1 -->
        <div class="product-card">
            <div class="product-details">
            <div class="product-image">
                <img src="product_image/<?php echo $product['PRODUCT_PICTURE']; ?>" alt="<?php echo $product['PRODUCT_NAME']; ?>">
            </div>
                <p class="product-name"><?php echo $product['PRODUCT_NAME']; ?></p>
                <div class="product-rating">
                    <span class="stars"><?php
                            // Convert REVIEW_SCORE to star ratings
                            $rating = round($product['AVG_REVIEW_SCORE']);
                            for ($i = 0; $i < 5; $i++) {
                                if ($i < $rating) {
                                    echo '&#9733;'; // Full star
                                } else {
                                    echo '&#9734;'; // Empty star
                                }
                            }
                            ?>
                            </span>
                    <span class="total-reviews">(<?php echo $product['REVIEW_COUNT']; ?>)</span>
                </div>
                <div id="price_container">
                    <div id="original_price"><?php echo '€' . number_format($product['PRODUCT_PRICE'], 2); ?></div>
                    <div id="discount">-20%</div>
                    <div id="discount_price"><?php echo '€' . number_format($product['PRODUCT_PRICE'], 2); ?></div>
                </div>
                <div class="button-container">
                    <a href="add_to_cart.php?productid=<?php echo $product['PRODUCT_ID']; ?>&userid=<?php echo $user_id; ?>&searchtext=<?php echo $searchText; ?>" class="add-to-cart-btn">add to cart</a> 
                    <a href="add_to_wishlist.php?produt_id=<?php echo $product['PRODUCT_ID']; ?>&user_id=<?php echo $user_id; ?>&searchtext=<?php echo $searchText; ?>" class="wishlist-btn"><i class="fas fa-heart"></i></a>
                </div>
            </div>
        </div>
        <?php
         endforeach;

?>
        
    </div>
</div>
    <?php
        include("footer.php");
    ?>
    <script src="product.js"> </script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="without_session_navbar.js"> </script>
    <script>
        // JavaScript
function addToCart(productId, userId, searchText) {
    // Redirect to add_to_cart.php with the productId, userId, and searchText parameters
    window.location.href = 'add_to_cart.php?productid=' + productId + '&userid=' + userId + '&searchtext=' + searchText;
}

function addToWishlist(productId, userId, searchText) {
    // Redirect to add_to_wishlist.php with the productId, userId, and searchText parameters
    window.location.href = 'add_to_wishlist.php?produt_id=' + productId + '&user_id=' + userId + '&searchtext=' + searchText;
}
</script>
</body>
</html>