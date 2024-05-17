<?php
// Include the database connection
include("connection/connection.php");

// Check if cartid is set in the URL
if(isset($_GET['cartid'])) {
    $cart_id = $_GET['cartid'];
    $total_price = $_GET["total_price"];
    $total_products = $_GET["nuber_product"];
    $customer_id = $_GET["customerid"];
    

    // Begin transaction
    $stmtBeginTransaction = oci_parse($conn, "BEGIN");
    oci_execute($stmtBeginTransaction);

    // Insert into ORDER_PRODUCT
    $sqlInsertOrderProduct = "BEGIN 
                                INSERT INTO ORDER_PRODUCT (NO_OF_PRODUCT, ORDER_STATUS, TOTAL_PRICE, SLOT_ID, CUSTOMER_ID, ORDER_DATE, ORDER_TIME, DISCOUNT_AMOUNT, CART_ID) 
                                VALUES (:total_products, 0, :total_price, 0, :customer_id, SYSDATE, SYSTIMESTAMP, 0, :cart_id)
                                RETURNING ORDER_PRODUCT_ID INTO :order_product_id;
                              END;";
    $stmtInsertOrderProduct = oci_parse($conn, $sqlInsertOrderProduct);
    oci_bind_by_name($stmtInsertOrderProduct, ":total_products", $total_products);
    oci_bind_by_name($stmtInsertOrderProduct, ":total_price", $total_price);
    oci_bind_by_name($stmtInsertOrderProduct, ":customer_id", $customer_id);
    oci_bind_by_name($stmtInsertOrderProduct, ":cart_id", $cart_id);
    oci_bind_by_name($stmtInsertOrderProduct, ":order_product_id", $order_product_id, -1, OCI_B_INT);
    oci_execute($stmtInsertOrderProduct);

    // Check if ORDER_PRODUCT_ID was returned
    if (!$order_product_id) {
        // Rollback transaction and display error message
        oci_execute(oci_parse($conn, "ROLLBACK"));
        echo "Failed to insert data into ORDER_PRODUCT table.";
        exit();
    }

    // Fetch the returned ORDER_PRODUCT_ID
    oci_fetch($stmtInsertOrderProduct);

    // Debug statement
    echo "Data inserted into ORDER_PRODUCT table. ORDER_PRODUCT_ID: $order_product_id";

    // Loop through the products in the cart
    $sql = "SELECT ci.NO_OF_PRODUCTS, ci.PRODUCT_ID, p.PRODUCT_PRICE 
            FROM CART_ITEM ci
            JOIN PRODUCT p ON ci.PRODUCT_ID = p.PRODUCT_ID
            WHERE ci.CART_ID = :cart_id";
    $stmtSelectProducts = oci_parse($conn, $sql);
    oci_bind_by_name($stmtSelectProducts, ":cart_id", $cart_id);
    oci_execute($stmtSelectProducts);

    while ($row = oci_fetch_assoc($stmtSelectProducts)) {
        $product_qty = $row['NO_OF_PRODUCTS'];
        $product_id = $row['PRODUCT_ID'];
        $product_price = $row['PRODUCT_PRICE'];

        // Insert into ORDER_DETAILS
        $sqlInsertOrderDetails = "INSERT INTO ORDER_DETAILS (ORDER_PRODUCT_ID, PRODUCT_ID, PRODUCT_QTY, PRODUCT_PRICE, TRADER_USER_ID) 
                                  VALUES (:order_product_id, :product_id, :product_qty, :product_price, (SELECT USER_ID FROM PRODUCT WHERE PRODUCT_ID = :product_id))";
        $stmtInsertOrderDetails = oci_parse($conn, $sqlInsertOrderDetails);
        oci_bind_by_name($stmtInsertOrderDetails, ":order_product_id", $order_product_id);
        oci_bind_by_name($stmtInsertOrderDetails, ":product_id", $product_id);
        oci_bind_by_name($stmtInsertOrderDetails, ":product_qty", $product_qty);
        oci_bind_by_name($stmtInsertOrderDetails, ":product_price", $product_price);
        oci_execute($stmtInsertOrderDetails);

        // Delete the product from CART_ITEM
        $sqlDeleteCartItem = "DELETE FROM CART_ITEM WHERE CART_ID = :cart_id AND PRODUCT_ID = :product_id";
        $stmtDeleteCartItem = oci_parse($conn, $sqlDeleteCartItem);
        oci_bind_by_name($stmtDeleteCartItem, ":cart_id", $cart_id);
        oci_bind_by_name($stmtDeleteCartItem, ":product_id", $product_id);
        oci_execute($stmtDeleteCartItem);
    }

    oci_free_statement($stmtSelectProducts);

    // Commit transaction
    $stmtCommit = oci_parse($conn, "COMMIT");
    oci_execute($stmtCommit);

    // Redirect to checkout page with customerid, cartid, number_product, and total_price parameters
    $url = "slot_time.php?customerid=$customer_id&order_id=$order_product_id&cartid=$cart_id&nuber_product=$total_products&total_price=$total_price";
    header("Location: $url");
    exit();
} else {

    // Redirect if cartid is not set
    header("Location: error_page.php");
    exit();
}

oci_close($conn);
?>

