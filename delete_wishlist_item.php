<?php
// Error Reporting If any error occurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("session/session.php");
$wishlist_id = $_GET["wishlist_id"];
$product_id = $_GET["product_id"];
// Include the database connection
include("connection/connection.php");

// Get the wishlist_id and product_id from the URL parameters
$wishlist_id = isset($_GET['wishlist_id']) ? $_GET['wishlist_id'] : null;
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;

if ($wishlist_id && $product_id) {
    // Prepare the DELETE statement
    $sqlDeleteItem = "DELETE FROM WISHLIST_ITEM WHERE WISHLIST_ID = :wishlist_id AND PRODUCT_ID = :product_id";

    // Parse the SQL statement
    $stmtDeleteItem = oci_parse($conn, $sqlDeleteItem);

    // Bind the parameters
    oci_bind_by_name($stmtDeleteItem, ':wishlist_id', $wishlist_id);
    oci_bind_by_name($stmtDeleteItem, ':product_id', $product_id);

    // Execute the SQL statement
    $success = oci_execute($stmtDeleteItem);

    if ($success) {
        // Close the statement
        oci_free_statement($stmtDeleteItem);

        // Close the connection
        oci_close($conn);

        // Redirect to wishlist.php after successful deletion
        header("Location: wishlist.php");
        exit();
    } else {
        // Handle error if deletion fails
        echo "Error: Failed to delete wishlist item.";
    }
} else {
    // Handle missing parameters
    echo "Error: Wishlist ID or Product ID is missing.";
}
?>