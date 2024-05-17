<?php
session_start();
// Include connection file to the database
include("connection/connection.php");
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
        $selectProductSql = "SELECT PRODUCT_ID, PRODUCT_NAME, PRODUCT_PICTURE FROM PRODUCT WHERE PRODUCT_ID = :productId";

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
$selectProductsSql = "SELECT PRODUCT_ID, PRODUCT_DESCRIPTION, PRODUCT_NAME, PRODUCT_PICTURE FROM PRODUCT WHERE IS_DISABLED = 0";

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
    <section class="home" id="home"  style="overflow-x: hidden;">
        <div class="swiper-container home-slider">
            <div class="swiper-wrapper">
                <!-- First slide -->
                <?php
                    // Check if there are products available
                    foreach ($products as $product) {
                        // Extract product details
                        $productId = $product['PRODUCT_ID'];
                        $productName = $product['PRODUCT_NAME'];
                        $productDescription = $product['PRODUCT_DESCRIPTION'];
                        $productPicture = $product['PRODUCT_PICTURE'];
            ?>
                <div class="swiper-slide slide" style="background-image: url('product_image/<?php echo $productPicture; ?>');">
                    <div class="content">
                        <h3><?php echo $productName; ?></h3>
                        <p><?php echo $productDescription; ?></p>
                        <button class="btn" onclick="addToCart(<?php echo $productId; ?>, <?php echo $user_id; ?>, '<?php echo $searchText; ?>')">Add to Cart</button>
                    </div>
                </div>
                <?php
                    }
                    ?>
                
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
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
            $userId = $review['USER_ID'];

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
    <h1 class="heading"> Features Products </h1>
    <div class="box-container">
        <!-- creating first item  box   -->
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="caviber_image.jpg" alt="">
            <!-- item name -->
            <h3>Caviber</h3>
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="chese_image.jpg" alt="">
            <!-- item name -->
            <h3>Cheese</h3>
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="chese_image.jpg" alt="">
            <!-- item name -->
            <h3>Itlian Cheeese</h3>
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="chese_image.jpg" alt="">
            <!-- item name -->
            <h3>Japnese Cheese</h3>
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="pork_image.jpeg" alt="">
            <!-- item name -->
            <h3>Pork Stake</h3>
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="pork_image.jpeg" alt="">
            <!-- item name -->
            <h3>Indian Pork Stake</h3>
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="pork_image.jpeg" alt="">
            <!-- item name -->
            <h3>African Pork Stake</h3>
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="pork_image.jpeg" alt="">
            <!-- item name -->
            <h3>American Pork Stake</h3>
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
        <div class="box">
            <!-- favicon code for heart icon --> <a href="#" class="fas fa-heart"></a>
            <!-- linking image -->
            <img src="pork_image.jpeg" alt="">
            <!-- favicon code for star logo -->
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <!-- item name -->
            <h3>Asian Pork Stake</h3>
            <!-- item price -->
            <span>$ 26.77</span><br>
            <!-- creating add to cart button -->
            <a href="#" class="btn">add to cart</a> 
        </div>
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
                <div class="swiper-slide slide" style="background-image: url('caviber_image.jpg');">
                </div>
                <!-- Second slide -->
                <div class="swiper-slide slide" style="background-image: url('chese_image.jpg');">
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
    <h3 class="sub-heading"> customer's review </h3>
    <h1 class="heading"> what they say </h1>
    <div class="swiper-container review-slider">

        <div class="swiper-wrapper">
            <!-- creating first comment box with slider effect   -->
            <div class="swiper-slide slide">
                <!-- favicon code for quote at right icon   -->
                <i class="fas fa-quote-right"></i>
                <div class="user">
                    <!-- linking images   -->
                    <img src="profile.jpg" alt="">
                    <div class="user-info">
                        <h3>Sabin Khanal</h3>
                        <div class="stars">
                            <!-- favicon code for star icon   -->
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <!-- comments questions   -->
                <p>Really nice place to hangout..</p>
            </div>
            <!-- creating second box with same code as first comment box  -->
            <div class="swiper-slide slide">
                <i class="fas fa-quote-right"></i>
                <div class="user">
                    <img src="profile.jpg" alt="">
                    <div class="user-info">
                        <h3>Shishir Acharya</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p>Good place for a meeting. With good service</p>
            </div>
            <!-- creating third box with same code as first comment box  -->
            <div class="swiper-slide slide">
                <i class="fas fa-quote-right"></i>
                <div class="user">
                    <img src="profile.jpg" alt="">
                    <div class="user-info">
                        <h3>Chadani Thapa</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p>its nice place</p>
            </div>
            <!-- creating fourth box with same code as first comment box  -->
            <div class="swiper-slide slide">
                <i class="fas fa-quote-right"></i>
                <div class="user">
                    <img src="profile.jpg" alt="">
                    <div class="user-info">
                        <h3>Subu Basnet</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p>Good & vintage entertainment, delicious food, reasonable price menu too must visit</p>
            </div>
            <!-- creating fifth box with same code as first comment box  -->
            <div class="swiper-slide slide">
                <i class="fas fa-quote-right"></i>
                <div class="user">
                    <img src="profile.jpg" alt="">
                    <div class="user-info">
                        <h3>Riya Shah</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p>Good & vintage entertainment.</p>
            </div>


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
            <div class="swiper-slide slide">
                <!-- favicon code for quote at right icon   -->
                <div class="user">
                    <!-- linking images   -->
                    <img src="chese_image.jpg" alt="">
                    <div class="user-info">
                        <h3>Cheesy World</h3>
                    </div>
                </div>
                <!-- comments questions   -->
                <p>Itlian cheese</p>
            </div>
            <!-- creating second box with same code as first comment box  -->
            <div class="swiper-slide slide">
                <div class="user">
                    <img src="pork_image.jpeg" alt="">
                    <div class="user-info">
                        <h3>Butcher</h3>
                    </div>
                </div>
                <p>Golden stack</p>
            </div>
            <!-- creating third box with same code as first comment box  -->
            <div class="swiper-slide slide">
                <div class="user">
                    <img src="caviber_image.jpg" alt="">
                    <div class="user-info">
                        <h3>Caviber World</h3>
                    </div>
                </div>
                <p>Best Place to get aunthetic Caviber</p>
            </div>
            <!-- creating third box with same code as first comment box  -->
            <div class="swiper-slide slide">
            <div class="user">
                    <!-- linking images   -->
                    <img src="chese_image.jpg" alt="">
                    <div class="user-info">
                        <h3>Cheesy World</h3>
                    </div>
                </div>
                <!-- comments questions   -->
                <p>Itlian cheese</p>
            </div>
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
</script>
</body>
</html>
