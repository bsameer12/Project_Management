<?php
// Error Reporting If any error occurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("session/session.php");

// Get parameters from URL
$user_id = isset($_GET["userid"]) ? $_GET["userid"] : null;
$product_id = isset($_GET["productid"]) ? $_GET["productid"] : null;
$search_text = isset($_GET["searchtext"]) ? $_GET["searchtext"] : "";

if($user_id == 0){
    header("Location:customer_signin.php");
    exit();
}

// Include the database connection
include("connection/connection.php");

// Prepare the SQL statement to get CUSTOMER_ID from CUSTOMER table
$sql = "SELECT CUSTOMER_ID FROM CUSTOMER WHERE USER_ID = :user_id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ':user_id', $user_id);
oci_execute($stmt);
$row = oci_fetch_assoc($stmt);

if ($row) {
    $customer_id = $row['CUSTOMER_ID'];

    // Prepare the SQL statement to get PRODUCT_PRICE from product table
    $product_sql = "SELECT PRODUCT_PRICE FROM product WHERE PRODUCT_ID = :product_id";
    $product_stmt = oci_parse($conn, $product_sql);
    oci_bind_by_name($product_stmt, ':product_id', $product_id);
    oci_execute($product_stmt);
    $product_row = oci_fetch_assoc($product_stmt);

    if ($product_row) {
        $product_price = $product_row['PRODUCT_PRICE'];

        // Initialize variables
        $cart_id = null;

        // Check if the customer has an existing cart
        $sqlCartCheck = "SELECT cart_id FROM cart WHERE customer_id = :customer_id";
        $stmtCartCheck = oci_parse($conn, $sqlCartCheck);
        oci_bind_by_name($stmtCartCheck, ':customer_id', $customer_id);
        oci_execute($stmtCartCheck);
        $rowCartCheck = oci_fetch_assoc($stmtCartCheck);

        if ($rowCartCheck) {
            $cart_id = $rowCartCheck['CART_ID'];
        } else {
            $sqlCreateCart = "INSERT INTO cart (customer_id, ORDER_PRODUCT_ID) VALUES (:customer_id, :p_id) RETURNING cart_id INTO :new_cart_id";
            $stmtCreateCart = oci_parse($conn, $sqlCreateCart);
            oci_bind_by_name($stmtCreateCart, ':customer_id', $customer_id);
            oci_bind_by_name($stmtCreateCart, ':p_id', $product_id);  // Correct variable used here
            oci_bind_by_name($stmtCreateCart, ':new_cart_id', $cart_id, -1, OCI_B_INT); // Added size and type for OUT bind variable
            oci_execute($stmtCreateCart);
        }

        // SQL to count NO_OF_PRODUCTS in CART_ITEM
        $sqlCountProducts = "SELECT SUM(no_of_products) AS total_products FROM cart_item WHERE cart_id = :cart_id";
        $stmtCountProducts = oci_parse($conn, $sqlCountProducts);
        oci_bind_by_name($stmtCountProducts, ':cart_id', $cart_id);
        oci_execute($stmtCountProducts);
        $rowCountProducts = oci_fetch_assoc($stmtCountProducts);

        if ($rowCountProducts) {
            $totalProducts = $rowCountProducts['TOTAL_PRODUCTS'];

            if ($totalProducts < 20) {
                $sqlCartItemCheck = "SELECT * FROM cart_item WHERE cart_id = :cart_id AND product_id = :product_id";
                $stmtCartItemCheck = oci_parse($conn, $sqlCartItemCheck);
                oci_bind_by_name($stmtCartItemCheck, ':cart_id', $cart_id);
                oci_bind_by_name($stmtCartItemCheck, ':product_id', $product_id);
                oci_execute($stmtCartItemCheck);

                if ($rowCartItemCheck = oci_fetch_assoc($stmtCartItemCheck)) {
                    $sqlUpdateQuantity = "UPDATE cart_item SET no_of_products = no_of_products + 1 WHERE cart_id = :cart_id AND product_id = :product_id";
                    $stmtUpdateQuantity = oci_parse($conn, $sqlUpdateQuantity);
                    oci_bind_by_name($stmtUpdateQuantity, ':cart_id', $cart_id);
                    oci_bind_by_name($stmtUpdateQuantity, ':product_id', $product_id);
                    oci_execute($stmtUpdateQuantity);

                    // Free statement resources
                    oci_free_statement($stmtUpdateQuantity);
                } else {
                    $selectDiscountSql = "SELECT DISCOUNT_PERCENT FROM DISCOUNT WHERE PRODUCT_ID = :productId";
                    $selectDiscountStmt = oci_parse($conn, $selectDiscountSql);
                    oci_bind_by_name($selectDiscountStmt, ':productId', $product_id);
                    oci_execute($selectDiscountStmt);

                    $discount_row = oci_fetch_assoc($selectDiscountStmt);
                    $discountPercent = $discount_row ? $discount_row['DISCOUNT_PERCENT'] : 0;
                    
                    $discountAmount = ($product_price * $discountPercent) / 100;
                    $discountedPrice = $product_price - $discountAmount;

                    $sqlInsertItem = "INSERT INTO cart_item (cart_id, product_id, no_of_products, product_price) VALUES (:cart_id, :product_id, 1, :product_price)";
                    $stmtInsertItem = oci_parse($conn, $sqlInsertItem);
                    oci_bind_by_name($stmtInsertItem, ':cart_id', $cart_id);
                    oci_bind_by_name($stmtInsertItem, ':product_id', $product_id);
                    oci_bind_by_name($stmtInsertItem, ':product_price', $discountedPrice);
                    oci_execute($stmtInsertItem);

                    // Free statement resources
                    oci_free_statement($stmtInsertItem);
                    oci_free_statement($selectDiscountStmt);
                }

                oci_free_statement($stmtCartItemCheck);
            } else {
                echo "Cart is full and cannot add more items.";
            }
        } else {
            echo "Error in counting products.";
        }

        oci_free_statement($stmtCountProducts);
    } else {
        echo "No results found for the specified product.";
    }

    oci_free_statement($product_stmt);
} else {
    echo "No results found for the specified customer ID.";
}

oci_free_statement($stmt);
oci_close($conn);

if (!empty($search_text)) {
    if ($search_text == "p") {
        header("Location:product.php?productId=$product_id");
    } else {
        header("Location:search_page.php?value=$search_text");
    }
} else {
    header("Location:index.php");
}
?>
