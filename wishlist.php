<?php
require("session/session.php");
$user_id = $_SESSION["userid"];
// Include the database connection
include("connection/connection.php");

// Prepare the SQL statement to get CUSTOMER_ID from CUSTOMER table
$sql = "SELECT CUSTOMER_ID FROM CUSTOMER WHERE USER_ID = :user_id";

// Parse the SQL statement
$stmt = oci_parse($conn, $sql);

// Bind the parameter
oci_bind_by_name($stmt, ':user_id', $user_id);

// Execute the SQL statement
oci_execute($stmt);

// Fetch the result
$row = oci_fetch_assoc($stmt);

// Check if a row is returned
if ($row) {
    // Store the CUSTOMER_ID
    $customer_id = $row['CUSTOMER_ID'];

    // Initialize variables
    $wishlist_id = null;
    $results = [];

    // Check if the customer has an existing wishlist
    $sqlWishlistCheck = "SELECT WISHLIST_ID FROM WISHLIST WHERE CUSTOMER_ID = :customer_id";
    $stmtWishlistCheck = oci_parse($conn, $sqlWishlistCheck);
    oci_bind_by_name($stmtWishlistCheck, ':customer_id', $customer_id);
    oci_execute($stmtWishlistCheck);

    // Fetch the result
    $rowWishlistCheck = oci_fetch_assoc($stmtWishlistCheck);

    if ($rowWishlistCheck) {
        // If the customer has an existing wishlist, retrieve the wishlist_id
        $wishlist_id = $rowWishlistCheck['WISHLIST_ID'];
        // Prepare the SQL statement
        $sql = "SELECT p.PRODUCT_ID, p.PRODUCT_NAME, p.PRODUCT_PRICE, p.STOCK_AVAILABLE, p.PRODUCT_PICTURE
        FROM PRODUCT p
        INNER JOIN WISHLIST_ITEM wi ON p.PRODUCT_ID = wi.PRODUCT_ID
        WHERE wi.WISHLIST_ID = :wishlist_id";

        // Parse the SQL statement
        $stmt = oci_parse($conn, $sql);

        // Bind the parameter
        oci_bind_by_name($stmt, ':wishlist_id', $wishlist_id);

        // Execute the SQL statement
        oci_execute($stmt);

        // Fetch the results
        while ($row = oci_fetch_assoc($stmt)) {
            // Append each row to the results array
            $results[] = $row;
        }

        // Free statement resources
        oci_free_statement($stmt);
    }

    // Free statement resources
    oci_free_statement($stmtWishlistCheck);

    // Close the connection
    oci_close($conn);
}
?>
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
    <style>
        .empty-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
        }
        .empty-wishlist-message {
            font-size: 24px;
            color: #333;
        }
    </style>
</head>
<body>
    <?php
         require("navbar_switching.php");
         includeNavbarBasedOnSession();
    ?>

    <div class="container_cat">
        <div class="<?php echo empty($results) ? 'empty-container' : 'content'; ?>">
            <?php if (empty($results)) { ?>
                <div class="empty-wishlist-message">Your Wishlist is Empty !!!</div>
            <?php } else { ?>
                <section id="wishlist" class="product-list">
                    <h1>My Wishlist</h1> <!-- Adding the heading for the wishlist -->
                    <!-- Product items dynamically generated here -->
                    <?php
                    // Access the results using foreach loop
                    foreach ($results as $row) {
                        echo "<div class='product' onclick=\"redirectToProductPage('" . $row['PRODUCT_ID'] . "')\">";
                        echo "<img src='product_image/" . $row['PRODUCT_PICTURE'] . "' alt='" . $row['PRODUCT_NAME']  ."'>";
                        echo "<div class='product-details'>";
                        echo "<h2>" . $row['PRODUCT_NAME'] . "</h2>";
                        // Check if the product is in stock or out of stock
                        if ($row['STOCK_AVAILABLE'] == "no") {
                            echo "<p class='availability'>Availability: <span class='out-of-stock'>Out of stock</span></p>";
                        } else {
                            echo "<p class='availability'>Availability: <span class='in-stock'>In stock</span></p>";
                        }
                        $product_price = $row['PRODUCT_PRICE'];
                        $discount_percent = isset($row['DISCOUNT_PERCENT']) ? $row['DISCOUNT_PERCENT'] : 0;
                        $discounted_price = $product_price - ($product_price * ($discount_percent / 100));
                        echo "<p>Price: " . $discounted_price . "</p>";
                        echo "</div>";
                        echo "<a href='delete_wishlist_item.php?wishlist_id=" . $wishlist_id . "&product_id=" . $row['PRODUCT_ID'] . "' class='remove-button'>Remove</a>";
                        echo "</div>";
                    }
                    ?>
                </section>
            <?php } ?>
        </div>
        <?php include("footer.php"); ?>
    </div>
    
    <script src="without_session_navbar.js"></script>
    <script>
        function redirectToProductPage(productId) {
        // Redirect to the product page with the specific product ID
        window.location.href = "product.php?productId=" + productId;
    }
    </script>
</body>
</html>

