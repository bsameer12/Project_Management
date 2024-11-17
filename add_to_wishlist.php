<?php
    // Error Reporting If any error occurs
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require("session/session.php");


    // Get parameters from URL
    $user_id = isset($_GET["user_id"]) ? $_GET["user_id"] : null;
    $product_id = isset($_GET["produt_id"]) ? $_GET["produt_id"] : null;
    $search_text = isset($_GET["searchtext"]) ? $_GET["searchtext"] : "";
    if ($user_id == 0) {
        header("Location:customer_signin.php");
        exit();
    }
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
        $sql_error = "";

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
        } else {
            // If the customer does not have a wishlist, create a new wishlist
            $sqlCreateWishlist = "INSERT INTO WISHLIST (WISHLIST_CREATED_DATE, WISHLIST_UPDATED_DATE, CUSTOMER_ID) VALUES (SYSDATE, SYSDATE, :customer_id) RETURNING WISHLIST_ID INTO :new_wishlist_id";
            $stmtCreateWishlist = oci_parse($conn, $sqlCreateWishlist);
            oci_bind_by_name($stmtCreateWishlist, ':customer_id', $customer_id);
            oci_bind_by_name($stmtCreateWishlist, ':new_wishlist_id', $wishlist_id, -1, OCI_B_INT);
            oci_execute($stmtCreateWishlist);
        }

        // Check if the number of items in WISHLIST_ITEM for this customer exceeds 10
            $wishlistCheckSql = "SELECT COUNT(*) AS TOTAL_ITEMS FROM WISHLIST_ITEM WHERE WISHLIST_ID = (SELECT WISHLIST_ID FROM WISHLIST WHERE CUSTOMER_ID = :customer_id)";
            $wishlistCheckStmt = oci_parse($conn, $wishlistCheckSql);
            oci_bind_by_name($wishlistCheckStmt, ':customer_id', $customer_id);
            oci_execute($wishlistCheckStmt);
            $wishlistCheckRow = oci_fetch_assoc($wishlistCheckStmt);

            if ($wishlistCheckRow && $wishlistCheckRow['TOTAL_ITEMS'] > 10) {
                // Set an alert message
                $wishlistAlert = "Wishlist is full, please remove some items.";
            } else {

                        // Check if the wishlist item already exists
                        $sqlWishlistItemCheck = "SELECT * FROM WISHLIST_ITEM WHERE WISHLIST_ID = :wishlist_id AND PRODUCT_ID = :product_id";
                        $stmtWishlistItemCheck = oci_parse($conn, $sqlWishlistItemCheck);
                        oci_bind_by_name($stmtWishlistItemCheck, ':wishlist_id', $wishlist_id);
                        oci_bind_by_name($stmtWishlistItemCheck, ':product_id', $product_id);
                        oci_execute($stmtWishlistItemCheck);

                        if ($rowWishlistItemCheck = oci_fetch_assoc($stmtWishlistItemCheck)) {
                            // Wishlist item already exists, do nothing
                            oci_free_statement($stmtWishlistItemCheck);
                        } else {
                            // Wishlist item does not exist, insert a new record
                            $sqlInsertItem = "INSERT INTO WISHLIST_ITEM (WISHLIST_ID, PRODUCT_ID) VALUES (:wishlist_id, :product_id)";
                            $stmtInsertItem = oci_parse($conn, $sqlInsertItem);
                            oci_bind_by_name($stmtInsertItem, ':wishlist_id', $wishlist_id);
                            oci_bind_by_name($stmtInsertItem, ':product_id', $product_id);
                            oci_execute($stmtInsertItem);
                            oci_free_statement($stmtInsertItem);
                        }
                    }

    } else {
        echo "No results found for customer id";
    }

    // Free statement resources
    oci_free_statement($stmt);

    // Close the connection
    oci_close($conn);

    // Redirect to search_page.php
    if(!empty($search_text) && $search_text == "p")
    {
        header("Location:product.php?productId=$product_id");
            exit();
    }
    if (!empty($search_text)) {
            header("Location:search_page.php?value=$search_text");
            exit();
    }
    else{
        header("Location:index.php");
        exit();
    }
?>
