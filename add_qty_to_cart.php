<?php
require("session/session.php");
$user_id = $_SESSION["userid"];

// Include the database connection
include("connection/connection.php");

$cart_id = $_POST['cart_id'];
$product_id = $_POST['product_id'];
$action = $_POST['action'];

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

// Update the quantity based on the action
if ($action == 'increase') {
    $sql = "UPDATE CART_ITEM SET NO_OF_PRODUCTS = NO_OF_PRODUCTS + 1 WHERE CART_ID = :cart_id AND PRODUCT_ID = :product_id";
} else if ($action == 'decrease') {
    $sql = "UPDATE CART_ITEM SET NO_OF_PRODUCTS = NO_OF_PRODUCTS - 1 WHERE CART_ID = :cart_id AND PRODUCT_ID = :product_id AND NO_OF_PRODUCTS > 0";
}
$params = [':cart_id' => $cart_id, ':product_id' => $product_id];
executeQuery($conn, $sql, $params);

oci_close($conn);

// Redirect back to the cart page
header("Location: cart.php");
exit();
?>
