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

                // Prepare the SQL statement to get PRODUCT_PRICE from product table
                $product_sql = "SELECT PRODUCT_PRICE FROM product WHERE PRODUCT_ID = :product_id";

                // Parse the SQL statement
                $product_stmt = oci_parse($conn, $product_sql);

                // Bind the parameter
                oci_bind_by_name($product_stmt, ':product_id', $product_id);

                // Execute the SQL statement
                oci_execute($product_stmt);

                // Fetch the result
                $product_row = oci_fetch_assoc($product_stmt);

                // Check if a row is returned
                if ($product_row) {
                    // Store the PRODUCT_PRICE in a variable
                    $product_price = $product_row['PRODUCT_PRICE'];

                    // Initialize variables
                    $cart_id = null;
                    $sql_error = "";

                    // Check if the customer has an existing cart
                    $sqlCartCheck = "SELECT cart_id FROM cart WHERE customer_id = :customer_id";
                    $stmtCartCheck = oci_parse($conn, $sqlCartCheck);
                    oci_bind_by_name($stmtCartCheck, ':customer_id', $customer_id);
                    oci_execute($stmtCartCheck);

                    // Fetch the result
                    $rowCartCheck = oci_fetch_assoc($stmtCartCheck);

                    if ($rowCartCheck) {
                        // If the customer has an existing cart, retrieve the cart_id
                        $cart_id = $rowCartCheck['CART_ID'];
                    } else {
                        // If the customer does not have a cart, create a new cart
                        $sqlCreateCart = "INSERT INTO cart (customer_id) VALUES (:customer_id) RETURNING cart_id INTO :new_cart_id";
                        $stmtCreateCart = oci_parse($conn, $sqlCreateCart);
                        oci_bind_by_name($stmtCreateCart, ':customer_id', $customer_id);
                        oci_bind_by_name($stmtCreateCart, ':new_cart_id', $cart_id);
                        oci_execute($stmtCreateCart);
                    }

                    // SQL to count NO_OF_PRODUCTS in CART_ITEM
                    $sqlCountProducts = "SELECT SUM(no_of_products) AS total_products FROM cart_item WHERE cart_id = :cart_id";
                    $stmtCountProducts = oci_parse($conn, $sqlCountProducts);
                    oci_bind_by_name($stmtCountProducts, ':cart_id', $cart_id);
                    oci_execute($stmtCountProducts);
                    $rowCountProducts = oci_fetch_assoc($stmtCountProducts);

                    // Check if the query was successful
                    if ($rowCountProducts) {
                        // Get the total number of products
                        $totalProducts = $rowCountProducts['TOTAL_PRODUCTS'];

                        // Check if total products is less than or equal to 15
                        if ($totalProducts < 15) {
                            // Add products to the cart
                            // Your code here
                            // Check if the cart item already exists
                    $sqlCartItemCheck = "SELECT * FROM cart_item WHERE cart_id = :cart_id AND product_id = :product_id";
                    $stmtCartItemCheck = oci_parse($conn, $sqlCartItemCheck);
                    oci_bind_by_name($stmtCartItemCheck, ':cart_id', $cart_id);
                    oci_bind_by_name($stmtCartItemCheck, ':product_id', $product_id);
                    oci_execute($stmtCartItemCheck);

                    if ($rowCartItemCheck = oci_fetch_assoc($stmtCartItemCheck)) {
                        // If the cart item exists, update the quantity
                        $sqlUpdateQuantity = "UPDATE cart_item SET no_of_products = no_of_products + 1 WHERE cart_id = :cart_id AND product_id = :product_id";
                        $stmtUpdateQuantity = oci_parse($conn, $sqlUpdateQuantity);
                        oci_bind_by_name($stmtUpdateQuantity, ':cart_id', $cart_id);
                        oci_bind_by_name($stmtUpdateQuantity, ':product_id', $product_id);
                        oci_execute($stmtUpdateQuantity);
                            // Free statement resources
                        oci_free_statement($stmtCartItemCheck);
                        oci_free_statement($stmtUpdateQuantity);
                    } else {
                        // If the cart item does not exist, insert a new record
                        $sqlInsertItem = "INSERT INTO cart_item (cart_id, product_id, no_of_products, product_price) VALUES (:cart_id, :product_id, 1, :product_price)";
                        $stmtInsertItem = oci_parse($conn, $sqlInsertItem);
                        oci_bind_by_name($stmtInsertItem, ':cart_id', $cart_id);
                        oci_bind_by_name($stmtInsertItem, ':product_id', $product_id);
                        oci_bind_by_name($stmtInsertItem, ':product_price', $product_price);
                        oci_execute($stmtInsertItem);
                        oci_free_statement($stmtInsertItem);

                    }

                        } else {
                            // Set variable with "Cart Is full and cannot add to cart alert"
                            $cartFullAlert = "Cart Is full and cannot add to cart alert";
                        }
                    } else {
                        // Handle error if the query fails
                        echo "Error in counting products.";
                    }

                    // Free statement resources
                    oci_free_statement($stmtCountProducts);

                    
                    
                   
                } else {
                    echo "No results found for cart items.";
                }

                // Free statement resources
                oci_free_statement($product_stmt);
            } else {
                echo "No results found for customer id";
            }

            // Free statement resources
            oci_free_statement($stmt);

            // Close the connection
            oci_close($conn);

        //Redirect to search_page.php
        if (!empty($search_text)) {
        header("Location:search_page.php?value=$search_text");
        exit();
        }
        else{
            header("Location:index.php");
        exit();
        }

    ?>