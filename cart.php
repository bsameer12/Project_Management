<?php
require("session/session.php");
$user_id = $_SESSION["userid"];


// Include the database connection
include("connection/connection.php");

// Function to execute a query and return the result
function executeQuery($conn, $sql, $params = []) {
    $stmt = oci_parse($conn, $sql);
    if (!$stmt) {
        $e = oci_error($conn);
        echo htmlentities($e['message']);
        exit;
    }

    foreach ($params as $key => $val) {
        oci_bind_by_name($stmt, $key, $params[$key]);
    }

    $r = oci_execute($stmt);
    if (!$r) {
        $e = oci_error($stmt);
        echo htmlentities($e['message']);
        exit;
    }

    return $stmt;
}

// Get CUSTOMER_ID
$sql = "SELECT CUSTOMER_ID FROM CUSTOMER WHERE USER_ID = :user_id";
$params = [':user_id' => $user_id];
$stmt = executeQuery($conn, $sql, $params);

$row = oci_fetch_assoc($stmt);
oci_free_statement($stmt);

if ($row) {
    $customer_id = $row['CUSTOMER_ID'];

    // Get CART_ID
    $sqlCartCheck = "SELECT CART_ID FROM CART WHERE CUSTOMER_ID = :customer_id";
    $params = [':customer_id' => $customer_id];
    $stmtCartCheck = executeQuery($conn, $sqlCartCheck, $params);

    $rowCartCheck = oci_fetch_assoc($stmtCartCheck);
    oci_free_statement($stmtCartCheck);

    if ($rowCartCheck) {
        $cart_id = $rowCartCheck['CART_ID'];

        // Get cart items and sum of total products
        $sql = "SELECT ci.NO_OF_PRODUCTS, ci.PRODUCT_ID, p.PRODUCT_PRICE, p.PRODUCT_NAME, p.PRODUCT_PICTURE,
                       SUM(ci.NO_OF_PRODUCTS) OVER() AS TOTAL_PRODUCTS
                FROM CART_ITEM ci
                INNER JOIN PRODUCT p ON ci.PRODUCT_ID = p.PRODUCT_ID
                WHERE ci.CART_ID = :cart_id";
        $params = [':cart_id' => $cart_id];
        $stmt = executeQuery($conn, $sql, $params);

        $results = [];
        $total_products = 0;
        $total_price = 0;

        while ($row = oci_fetch_assoc($stmt)) {
            $results[] = $row;
            $total_products = $row['TOTAL_PRODUCTS'];
            $total_price += $row['NO_OF_PRODUCTS'] * $row['PRODUCT_PRICE'];
        }

        oci_free_statement($stmt);
    } else {
        echo "No cart found for customer.";
    }
} else {
    echo "No customer found.";
}

oci_close($conn);
// Check if the checkout button is clicked
if(isset($_POST['checkout'])) {
    // Redirect to checkout page with customerid and cartid parameters
    header("Location: check_out.php?customerid=$customer_id&cartid=$cart_id&nuber_product=$total_products&total_price=$total_price");
    exit; // Stop further execution
}
?>

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
    <?php include("session_navbar.php"); ?>
    <div class="container_cat">
        <section class="cart-section" id="cart-section">
            <div class="cart-container">
                <h3>Cart</h3>
                <!-- Left side - Products -->
                <?php
                foreach ($results as $row) {
                    $disableIncrement = ($total_products >= 15) ? "disabled" : "";
                    echo "<div class='products'>";
                        echo "<div class='product-item'>";
                            echo "<img src='product_image/" .$row['PRODUCT_PICTURE'] ."' alt='" . $row['PRODUCT_NAME'] ."'>";
                            echo "<div class='product-details'>";
                                echo "<h3>" . $row['PRODUCT_NAME'] ."</h3>";
                                echo "<div class='quantity'>";
                                echo "<form method='POST' action='add_qty_to_cart.php'>";
                                echo "<input type='hidden' name='product_id' value='" . $row['PRODUCT_ID'] . "'>";
                                echo "<input type='hidden' name='cart_id' value='" . $cart_id . "'>";
                                echo "<button type='submit' name='action' value='decrease' class='decrement' id='decrementBtn'>-</button>";
                                echo "<input type='number' min='1' value='" . $row['NO_OF_PRODUCTS'] . "' id='quantityInput' readonly>";
                                echo "<button type='submit' name='action' value='increase' class='increment' id='incrementBtn' $disableIncrement>+</button>";
                                echo "</form>";
                                echo "</div>";
                                echo "<p class='price'>&pound;" . $row['PRODUCT_PRICE'] ."</p>";
                                echo "<a href='delete_cart_item.php?cart_id=" . $cart_id . "&product_id=" . $row['PRODUCT_ID'] . "' class='delete'>Remove</a>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>

        <!-- Summary and Discount Section -->
        <section class="summary-section" id="summary-section">
            <div class="summary">
                <h3>Summary</h3>
                <p>Number of items: <?php echo $total_products; ?></p>
                <p>Total price: &pound;<?php echo number_format($total_price, 2); ?></p>
                <p>Net total: &pound;<?php echo number_format($total_price, 2); ?></p>
                <p>Discount: &pound;0.00</p>
                <p>Final total: &pound;<?php echo number_format($total_price, 2); ?></p>
            </div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <button type="submit" name="checkout" class="checkout">Checkout</button>
    </form>
        </section>
    </div>
    <?php include("footer.php"); ?>

    <script src="without_session_navbar.js"></script>
    <script src="cart.js"></script>

    <!-- linking external js file -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</body>
</html>

