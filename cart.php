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
        $sql = "SELECT ci.NO_OF_PRODUCTS, ci.PRODUCT_ID, ci.PRODUCT_PRICE, p.PRODUCT_NAME, p.PRODUCT_PICTURE,
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
        $cart_id = null;
    }
} else {
    echo "No customer found.";
    exit();
}

// Initialize variables
$totalAmount = 0;
$discountAmount = 0;
$actualPrice = 0;

if ($cart_id) {
    $sql = "
    SELECT 
        OP.PRODUCT_ID, 
        OP.NO_OF_PRODUCTS, 
        OP.PRODUCT_PRICE, 
        P.PRODUCT_PRICE AS ACTUAL_PRICE
    FROM 
        CART_ITEM OP
    JOIN 
        PRODUCT P ON OP.PRODUCT_ID = P.PRODUCT_ID
    WHERE 
        OP.CART_ID = :cart_id
    ";

    // Parse the SQL statement
    $stmt = oci_parse($conn, $sql);

    // Bind the CART_ID parameter
    oci_bind_by_name($stmt, ':cart_id', $cart_id);

    // Execute the SQL statement
    oci_execute($stmt);

    // Fetch the results and calculate totals
    while ($row = oci_fetch_assoc($stmt)) {
        // Check if the array keys exist before using them
        $productQty = isset($row['NO_OF_PRODUCTS']) ? $row['NO_OF_PRODUCTS'] : 0;
        $productPrice = isset($row['PRODUCT_PRICE']) ? $row['PRODUCT_PRICE'] : 0;
        $actualPricePerProduct = isset($row['ACTUAL_PRICE']) ? $row['ACTUAL_PRICE'] : 0;

        // Calculate total amount for this product
        $totalAmount += $productQty * $productPrice;

        // Calculate discount amount for this product
        $discountAmount += ($actualPricePerProduct - $productPrice) * $productQty;

        // Calculate actual price
        $actualPrice += $actualPricePerProduct * $productQty;
    }

    oci_free_statement($stmt);
    oci_close($conn);
} else {
    $total_products = 0;
    $discountAmount =0;
}

if(isset($_POST['checkout'])) {
    // Redirect to checkout page with customerid and cartid parameters
    header("Location: check_out.php?customerid=$customer_id&cartid=$cart_id&nuber_product=$total_products&total_price=$total_price&discount=$discountAmount");
    exit(); // Stop further execution
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
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
        }
        .container_cat {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .empty-cart-message {
            font-size: 24px;
            color: #333;
        }
    </style>
</head>
<body>
    <?php include("session_navbar.php"); ?>

    <div class="container_cat">
        <div class="content">
            <?php if (empty($results)) { ?>
                <div class="empty-cart-message">Your Cart is Empty !!!</div>
            <?php } else { ?>
                <section class="cart-section" id="cart-section">
    <div class="cart-container">
        <h3>Cart</h3>
        <!-- Table for Cart Items -->
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 18px; background-color: #f9f9f9; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
            <thead style="background-color: #007bff; color: white;">
                <tr>
                    <th style="padding: 12px 15px; text-align: center;">Image</th>
                    <th style="padding: 12px 15px; text-align: center;">Product Name</th>
                    <th style="padding: 12px 15px; text-align: center;">Quantity</th>
                    <th style="padding: 12px 15px; text-align: center;">Price</th>
                    <th style="padding: 12px 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($results as $row) {
                    $disableIncrement = ($total_products > 19) ? "disabled" : "";
                    echo "<tr style='border-bottom: 1px solid #ddd;'>
                        <td style='padding: 12px 15px; text-align: center;'><img src='product_image/" . $row['PRODUCT_PICTURE'] . "' alt='" . $row['PRODUCT_NAME'] . "' style='max-width: 50px; border-radius: 5px;'></td>
                        <td style='padding: 12px 15px; text-align: center;'>" . $row['PRODUCT_NAME'] . "</td>
                        <td style='padding: 12px 15px; text-align: center;'>
                            <form method='POST' action='add_qty_to_cart.php' style='display: inline-block;'>
                                <input type='hidden' name='product_id' value='" . $row['PRODUCT_ID'] . "'>
                                <input type='hidden' name='cart_id' value='" . $cart_id . "'>
                                <button type='submit' name='action' value='decrease' class='decrement' id='decrementBtn' style='padding: 5px 10px; border: none; background-color: #dc3545; color: white; cursor: pointer; border-radius: 5px;'>-</button>
                                <input type='number' min='1' value='" . $row['NO_OF_PRODUCTS'] . "' id='quantityInput' readonly style='width: 50px; text-align: center; border: none; background-color: transparent;'>
                                <button type='submit' name='action' value='increase' class='increment' id='incrementBtn' $disableIncrement style='padding: 5px 10px; border: none; background-color: #007bff; color: white; cursor: pointer; border-radius: 5px;'>+</button>
                            </form>
                        </td>
                        <td style='padding: 12px 15px; text-align: center;'>&pound;" . $row['PRODUCT_PRICE'] . "</td>
                        <td style='padding: 12px 15px; text-align: center;'>
                            <a href='delete_cart_item.php?cart_id=" . $cart_id . "&product_id=" . $row['PRODUCT_ID'] . "' class='delete' style='padding: 8px 12px; border: 1px solid #007bff; background-color: transparent; color: black; text-decoration: none; border-radius: 5px; transition: color 0.3s, background-color 0.3s, box-shadow 0.3s ease; font-size: 16px; cursor: pointer;'>Remove</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

                <!-- Summary and Discount Section -->
                <section class="summary-section" id="summary-section">
                    <div class="summary">
                        <h3>Summary</h3>
                        <p>Number of items: <?php echo $total_products; ?></p>
                        <p>Total price: &pound;<?php echo number_format($actualPrice, 2); ?></p>
                        <p>Net total: &pound;<?php echo number_format($totalAmount, 2); ?></p>
                        <p>Discount: &pound;<?php echo number_format($discountAmount, 2); ?></p>
                        <p>Final total: &pound;<?php echo number_format($totalAmount, 2); ?></p>
                    </div>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <button type="submit" name="checkout" class="checkout">Checkout</button>
                    </form>
                </section>
            <?php } ?>
        </div>

        <?php include("footer.php"); ?>
    </div>

    <script src="js/script.js"></script>
    <!-- swiper js file -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</body>
</html>
