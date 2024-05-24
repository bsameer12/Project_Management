<?php
session_start();
// Include connection file to the database
include("connection/connection.php");


 // Prepare SQL statement
 $sql = "UPDATE PRODUCT 
 SET STOCK_AVAILABLE = 'no', IS_DISABLED = 0 
 WHERE PRODUCT_QUANTITY < 1";

// Parse the SQL query
$stmt = oci_parse($conn, $sql);
if (!$stmt) {
$e = oci_error($conn);
throw new Exception("Failed to prepare statement: " . $e['message']);
}

// Execute the SQL statement
$r = oci_execute($stmt);
if (!$r) {
$e = oci_error($stmt);
throw new Exception("Failed to execute statement: " . $e['message']);
}


// Free the statement identifier
oci_free_statement($stmt);


// Define an array to store the category data
$categoryArray = [];

// Query to select CATEGORY_ID and CATEGORY_TYPE from PRODUCT_CATEGORY
$sql = "SELECT CATEGORY_ID, CATEGORY_TYPE, CATEGORY_IMAGE FROM PRODUCT_CATEGORY";

// Execute the query
$result = oci_parse($conn, $sql);
oci_execute($result);

// Fetch the rows and store them in the category array
while ($row = oci_fetch_assoc($result)) {
    $categoryArray[] = $row;
}

// Free the statement resources
oci_free_statement($result);

$user_id = isset($_SESSION["userid"]) ? $_SESSION["userid"] : 0;
$searchText = "";
if($user_id > 0){

// Initialize an empty array to store the results
$reviews = [];

// SQL query to select REVIEW_ID, PRODUCT_ID
$selectReviewSql = "SELECT REVIEW_ID, PRODUCT_ID FROM REVIEW WHERE REVIEW_PROCIDED = 0 AND USER_ID = :customerId";

// Prepare the OCI statement
$selectReviewStmt = oci_parse($conn, $selectReviewSql);
if (!$selectReviewStmt) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Bind the CUSTOMER_ID parameter
oci_bind_by_name($selectReviewStmt, ':customerId', $user_id);

// Execute the query
if (oci_execute($selectReviewStmt)) {
    // Fetch the results and populate the array
    while ($row = oci_fetch_assoc($selectReviewStmt)) {
        // Fetch additional product details for each review
        $productId = $row['PRODUCT_ID'];
        $productDetails = [];

        // Select PRODUCT_ID, PRODUCT_NAME, and PRODUCT_PICTURE from PRODUCT table
        $selectProductSql = "SELECT PRODUCT_ID, PRODUCT_NAME, PRODUCT_PICTURE FROM PRODUCT WHERE PRODUCT_ID = :productId AND IS_DISABLED=1 AND ADMIN_VERIFIED=1";

        // Prepare the OCI statement
        $selectProductStmt = oci_parse($conn, $selectProductSql);
        if (!$selectProductStmt) {
            $e = oci_error($conn);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // Bind the PRODUCT_ID parameter
        oci_bind_by_name($selectProductStmt, ':productId', $productId);

        // Execute the query
        if (oci_execute($selectProductStmt)) {
            // Fetch the product details
            $productDetails = oci_fetch_assoc($selectProductStmt);
        } else {
            $e = oci_error($selectProductStmt);
            echo "Error executing product query: " . htmlentities($e['message'], ENT_QUOTES);
        }

        // Free statement resources
        oci_free_statement($selectProductStmt);

        // Append the review details along with product details to the reviews array
        $reviews[] = [
            'REVIEW_ID' => $row['REVIEW_ID'],
            'PRODUCT_ID' => $productId,
            'PRODUCT_NAME' => $productDetails['PRODUCT_NAME'],
            'PRODUCT_PICTURE' => $productDetails['PRODUCT_PICTURE']
        ];
    }
} else {
    $e = oci_error($selectReviewStmt);
    echo "Error executing review query: " . htmlentities($e['message'], ENT_QUOTES);
}

// Free statement resources
oci_free_statement($selectReviewStmt);

if(isset($_POST["review_submit"]))
{
    // Function to sanitize input data
function sanitizeInput($data) {
    // Remove whitespace from the beginning and end of the string
    $data = trim($data);
    // Remove backslashes (\)
    $data = stripslashes($data);
    // Convert special characters to HTML entities
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    // Allow only text, numbers, and some special characters
    $data = preg_replace("/[^a-zA-Z0-9\-_.,?!()'\"\s]/", "", $data);
    return $data;
}

    $submittedRating = sanitizeInput($_POST["rating"]);
    $submittedReview = sanitizeInput($_POST["review"]);
    $reviewId = sanitizeInput($_POST["review_id"]);

    // Update the review in the database
    $updateReviewSql = "UPDATE REVIEW SET REVIEW_SCORE = :rating, FEEDBACK = :feedback, REVIEW_PROCIDED = 1, REVIEW_DATE = CURRENT_DATE WHERE REVIEW_ID = :reviewId";

// Prepare the OCI statement
$updateReviewStmt = oci_parse($conn, $updateReviewSql);
if (!$updateReviewStmt) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Bind parameters
oci_bind_by_name($updateReviewStmt, ':rating', $submittedRating);
oci_bind_by_name($updateReviewStmt, ':feedback', $submittedReview);
oci_bind_by_name($updateReviewStmt, ':reviewId', $reviewId);

// Execute the statement
if (oci_execute($updateReviewStmt)) {
    // Review updated successfully
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
} else {
    // Error updating review
    $e = oci_error($updateReviewStmt);
    echo "Error updating review: " . htmlentities($e['message'], ENT_QUOTES);
}

// Free statement resources
oci_free_statement($updateReviewStmt);


}

}
// Initialize an empty array to store the products
$products = [];

// SQL query to select products
$selectProductsSql = "SELECT PRODUCT_ID, PRODUCT_DESCRIPTION, PRODUCT_NAME, PRODUCT_PICTURE FROM PRODUCT WHERE IS_DISABLED = 1 AND ADMIN_VERIFIED=1";

// Prepare the OCI statement
$selectProductsStmt = oci_parse($conn, $selectProductsSql);
if (!$selectProductsStmt) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Execute the query
if (oci_execute($selectProductsStmt)) {
    // Fetch the results and populate the array
    while ($row = oci_fetch_assoc($selectProductsStmt)) {
        $products[] = $row;
    }
} else {
    $e = oci_error($selectProductsStmt);
    echo "Error executing product query: " . htmlentities($e['message'], ENT_QUOTES);
}

// Free statement resources
oci_free_statement($selectProductsStmt);

// Prepare the SQL statement to get the required data
$sql = "SELECT 
p.PRODUCT_ID, 
p.PRODUCT_NAME, 
p.PRODUCT_PRICE, 
p.PRODUCT_PICTURE, 
AVG(r.REVIEW_SCORE) AS AVG_REVIEW_SCORE,
COUNT(r.REVIEW_SCORE) AS TOTAL_REVIEWS, -- Count of reviews
COALESCE(d.DISCOUNT_PERCENT, '') AS DISCOUNT_PERCENT -- If no discount is available, default to 0
FROM 
product p
LEFT JOIN 
review r ON p.PRODUCT_ID = r.PRODUCT_ID
LEFT JOIN 
discount d ON p.PRODUCT_ID = d.PRODUCT_ID
WHERE 
p.IS_DISABLED = 1 AND
ADMIN_VERIFIED = 1
GROUP BY 
p.PRODUCT_ID, 
p.PRODUCT_NAME, 
p.PRODUCT_PRICE, 
p.PRODUCT_PICTURE, 
d.DISCOUNT_PERCENT
";

// Parse the SQL statement
$stmt = oci_parse($conn, $sql);

// Execute the SQL statement
oci_execute($stmt);

// Initialize an array to store the results
$products_review = array();

// Fetch the results
while ($row = oci_fetch_assoc($stmt)) {
    $products_review[] = $row;
}

// Free statement resources
oci_free_statement($stmt);

//Randomly select 6 products from the array
$selected_indices = array_rand($products_review, min(6, count($products_review)));

 //Ensure $selected_indices is an array if only one product is returned
if (!is_array($selected_indices)) {
   $selected_indices = array($selected_indices);
}


// Prepare the SQL statement to get the review data
$sql = "SELECT 
            r.REVIEW_SCORE, 
            r.FEEDBACK, 
            h.FIRST_NAME || ' ' || h.LAST_NAME AS NAME, 
            h.USER_PROFILE_PICTURE 
        FROM 
            REVIEW r 
        JOIN 
            HUDDER_USER h ON r.USER_ID = h.USER_ID 
        WHERE 
            r.REVIEW_PROCIDED = 1";
// Parse the SQL statement
$stmt = oci_parse($conn, $sql);

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

// Prepare the SQL statement to get the trader's information
$sql = "SELECT 
            u.FIRST_NAME || ' ' || u.LAST_NAME AS NAME, 
            u.USER_PROFILE_PICTURE,
            s.SHOP_DESCRIPTION
        FROM 
            HUDDER_USER u 
        JOIN 
            SHOP s ON u.USER_ID = s.USER_ID 
        WHERE 
            u.USER_TYPE = 'trader'";

// Parse the SQL statement
$stmt = oci_parse($conn, $sql);

// Execute the SQL statement
oci_execute($stmt);

// Initialize an array to store the results
$trader_shop = array();

// Fetch the results
while ($row = oci_fetch_assoc($stmt)) {
    $trader_shop[] = $row;
}

// Free statement resources
oci_free_statement($stmt);

// Close the connection
oci_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HudderFoods</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="style.css">
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
    
    <!-- Slider Section -->
    <section class="home" id="home"  style="overflow-x: hidden; width:100%; margin-left: auto; margin-right:auto;">
        <div class="swiper-container home-slider">
            <div class="swiper-wrapper">
                <!-- First slide -->
                
                <div class="swiper-slide slide" style="background-image: url('deli.jpeg');">
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
</div>
<div class="swiper-wrapper">
                <!-- First slide -->
                
                <div class="swiper-slide slide" style="background-image: url('green.png');">
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </section>
    <section class="category-section" id="category-section">
    <h2>Categories</h2>
    <div class="category-container">
            <?php foreach ($categoryArray as $category): ?>
        <div class="category-item">
            <a href="search_page.php?category_id=<?php echo $category['CATEGORY_ID'];?>value=<?php echo urlencode(''); ?>"><img src="category_picture/<?php echo $category['CATEGORY_IMAGE']; ?>" alt="<?php echo $category['CATEGORY_TYPE']; ?>"></a>
            <p><?php echo $category['CATEGORY_TYPE']; ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

        <!-- review section starts here -->
        <?php
        if($user_id > 0 && !empty($reviews)){
            ?>
    <section class="review" id="review" style="overflow-x: hidden;">
     <!-- Adding heading to section  -->
    <h1 class="heading"> Give Review On Your Purchase </h1>
    <div class="swiper-container review-slider">    
        <?php
        echo "<div class='swiper-wrapper'>";
        // Loop through each review in the $reviews array
        foreach ($reviews as $review) {
            // Fetch data for each review
            $productId = $review['PRODUCT_ID'];
            $productName = $review['PRODUCT_NAME'];
            $productPicture = $review['PRODUCT_PICTURE'];
            $userId = $_SESSION["userid"];

            // Generate HTML for each review
            ?>
            <div class="swiper-slide slide">
                <div class="profile-container">
                    <div class="profile">
                        <img src="product_image/<?php echo $productPicture; ?>" alt="Product Image" class="profile-image">
                        <div class="profile-info">
                            <h2 class="profile-name"><?php echo $productName; ?></h2>
                        </div>
                    </div>
                    <div class="review-form">
                        <form action="" method="post" name="rating_form" id="rating_form">
                            <div class="form-group">
                                <label for="rating">Select Rating:</label>
                                <select id="rating" name="rating" required>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="review">Provide a Review:</label>
                                <textarea id="review" name="review" rows="4" cols="50" required></textarea>
                            </div>
                            <input type="hidden" name="review_id" value="<?php echo $review['REVIEW_ID']; ?>">
                            <input type="submit" value="Submit your Review" class="submit-btn" name="review_submit" id="review_submit">
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
        echo "</div>";
       echo " </section>";
    }
    ?>


    <section class="dishes" id="dishes">
    <!-- heading context section  -->
    <h1 class="heading"> Just In Season </h1>
    <div class="product-card-container">
    <?php foreach ($selected_indices as $index): ?>
            <?php $product = $products_review[$index]; ?>
    <div class="product-card" onclick="redirectToProductPage(<?php echo $product['PRODUCT_ID']; ?>)">
        <div class="product-details">
            <div class="product-image">
                <img src="product_image/<?php echo $product['PRODUCT_PICTURE']; ?>" alt="<?php echo $product['PRODUCT_NAME']; ?>">
            </div>
            <p class="product-name"><?php echo $product['PRODUCT_NAME']; ?></p>
            <div class="product-rating">
                <span class="stars">
                <?php
                    $rating = round($product['AVG_REVIEW_SCORE']);
                    for ($i = 0; $i < 5; $i++) {
                        if ($i < $rating) {
                            echo '&#9733;';
                        } 
                    }
                    ?>
                </span>
                <span class="total-reviews">(<?php echo number_format($product['TOTAL_REVIEWS']); ?>)</span>
            </div>
            <div id="price_container">
                <div id="original_price"><?php echo number_format($product['PRODUCT_PRICE'], 2); ?></div>
                <div id="discount"><?php echo number_format($product['DISCOUNT_PERCENT'], 2); ?> %</div>
                <?php
                        $original_price = $product['PRODUCT_PRICE'];
                        $discount_percent = $product['DISCOUNT_PERCENT'];
                        $discount_amount = ($original_price * $discount_percent) / 100;
                        $discount_price = $original_price - $discount_amount;
                        ?>

                <div id="discount_price">$<?php  echo number_format($discount_price, 2); ?></div>
            </div>
            <div class="button-container">
                <a href="add_to_cart.php?productid=<?php echo $product['PRODUCT_ID']; ?>&userid=<?php echo $user_id; ?>&searchtext= <?php echo $searchText; ?>" class="add-to-cart-btn">add to cart</a> 
                <a href="add_to_wishlist.php?produt_id=<?php echo $product['PRODUCT_ID']; ?>&user_id=<?php echo $user_id; ?>&searchtext= <?php echo $searchText; ?>" class="wishlist-btn"><i class="fas fa-heart"></i></a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
                </div>
                </section>

    <div class="container_dash">
    <div class="content">
        <h3>Selected UK Traders</h3>
        <h3>Freshly Picked </h3>
        <h3> Carefully Packaged </h3> 
        <h3>Ethical And Sustainable</h3>
    </div>
    </div>

    <section class="home" id="home" style="overflow-x: hidden;">
        <div class="swiper-container home-slider">
            <div class="swiper-wrapper">
                <!-- First slide -->
                <div class="swiper-slide slide" style="background-image: url('grocer_banner.jpeg');">
                </div>
                <!-- Second slide -->
                <div class="swiper-slide slide" style="background-image: url('charcuterie_banner.jpg');">
                </div>
            </div>
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- review section starts here -->
<section class="review" id="review" style="overflow-x: hidden;">
     <!-- Adding heading to section  -->
    <h3 class="sub-heading"> Customer's Review </h3>
    <h1 class="heading"> What they say?</h1>
    <div class="swiper-container review-slider">

        <div class="swiper-wrapper">
            <!-- creating first comment box with slider effect   -->
            <?php
            foreach ($user_review as $review) {
                ?>
            <!-- creating second box with same code as first comment box  -->
            <div class="swiper-slide slide">
                <i class="fas fa-quote-right"></i>
                <div class="user">
                    <img src="profile_image/<?php echo $review['USER_PROFILE_PICTURE'] ; ?>" alt="<?php echo $review['NAME']; ?>">
                    <div class="user-info">
                        <h3><?php echo $review['NAME']; ?></h3>
                        <div class="stars">
                        <?php
                    $rating = round($review['REVIEW_SCORE']);
                    for ($i = 0; $i < 5; $i++) {
                        if ($i < $rating) {
                            echo '<i class="fas fa-star"></i>';
                        } else {
                            echo '<i class="far fa-star"></i>';
                        }
                    }
                    ?>
                        </div>
                    </div>
                </div>
                <p><?php echo $review['FEEDBACK']; ?></p>
            </div>
            <?php
            }
            ?>
            </div>

    </div>
    
</section>
<!-- review section ends here -->

<!-- review section starts here -->
<section class="review" id="review" style="overflow-x: hidden;">
     <!-- Adding heading to section  -->
    <h1 class="heading"> Meet Our Traders </h1>
    <div class="swiper-container review-slider">

        <div class="swiper-wrapper">
            <!-- creating first comment box with slider effect   -->
            <?php foreach ($trader_shop as $shop): ?>
            <div class="swiper-slide slide">
                <!-- favicon code for quote at right icon   -->
                <div class="user">
                    <!-- linking images   -->
                    <img src="profile_image/<?php echo $shop['USER_PROFILE_PICTURE']; ?>" alt="<?php echo $shop['NAME']; ?>">
                    <div class="user-info">
                        <h3><?php echo $shop['NAME']; ?></h3>
                    </div>
                </div>
                <!-- comments questions   -->
                <p><?php echo $shop['SHOP_DESCRIPTION']; ?></p>
            </div>
            <?php endforeach; ?>
            
    </div>
            </div>
</section>
<!-- review section ends here -->


    <?php
        include("footer.php");
    ?>

    <script src="without_session_navbar.js"></script>
    <script src="index.js"></script>
    <!-- linking external js file -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
       
    function addToCart(productId, userId, searchText) {
        // Test if the function is called and print the parameters to console
        console.log('Adding to cart:', productId, userId, searchText);
        // Redirect to add_to_cart.php with the parameters
         window.location.href = 'add_to_cart.php?productid=' + productId + '&userid=' + userId + '&searchtext=' + searchText;
    }
    function redirectToProductPage(productId) {
        // Redirect to the product page with the specific product ID
        window.location.href = "product.php?productId=" + productId;
    }
</script>
</body>
</html>
