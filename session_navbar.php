<?php
include("connection/connection.php");
require("PHPMailer-master/trader_verify_email.php");

$query = '
    SELECT 
        TRADER.TRADER_ID, 
        TRADER.SHOP_NAME, 
        TRADER.TRADER_TYPE, 
        HUDDER_USER.FIRST_NAME || \' \' || HUDDER_USER.LAST_NAME AS NAME, 
        HUDDER_USER.USER_EMAIL,
        PRODUCT_CATEGORY.CATEGORY_TYPE,
        SHOP.SHOP_ID
    FROM 
        TRADER
    JOIN 
        HUDDER_USER ON TRADER.USER_ID = HUDDER_USER.USER_ID
    JOIN 
        PRODUCT_CATEGORY ON TRADER.TRADER_TYPE = PRODUCT_CATEGORY.CATEGORY_ID
    JOIN 
        SHOP ON TRADER.USER_ID = SHOP.USER_ID
    WHERE 
        TRADER.VERIFICATION_STATUS = 1 
        AND TRADER.VERFIED_ADMIN = 1 
        AND TRADER.VERIFICATION_SEND = 0';

$stid = oci_parse($conn, $query);
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

oci_execute($stid);

while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
    $trader_id = $row['TRADER_ID'];
    $shop_name = $row['SHOP_NAME'];
    $trader_type = $row['TRADER_TYPE'];
    $name = $row['NAME'];
    $user_email = $row['USER_EMAIL'];
    $shop_category = $row['CATEGORY_TYPE'];
    $shop_id = $row['SHOP_ID'];

    // Send the approval email
    sendApprovalEmail($user_email, $name, $shop_id, $trader_id, $shop_name, $shop_category);
}

oci_free_statement($stid);


$user_id =  $_SESSION["userid"] ;
// SQL query
$query = 'SELECT CUSTOMER_ID FROM CUSTOMER WHERE USER_ID = :user_id';

// Preparing the statement
$stid = oci_parse($conn, $query);
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Binding the parameter
oci_bind_by_name($stid, ':user_id', $user_id);

// Executing the statement
oci_execute($stid);

// Fetching the result
$row = oci_fetch_array($stid, OCI_ASSOC);
if ($row) {
    $customer_id = $row['CUSTOMER_ID'];

} 

// Free the statement and close the connection
oci_free_statement($stid);

$query2 = 'SELECT CART_ID FROM CART WHERE CUSTOMER_ID = :customer_id';
    $stid2 = oci_parse($conn, $query2);
    if (!$stid2) {
        $e = oci_error($conn);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    oci_bind_by_name($stid2, ':customer_id', $customer_id);
    oci_execute($stid2);

    $row2 = oci_fetch_array($stid2, OCI_ASSOC);
    if ($row2) {
        $cart_id = $row2['CART_ID'];
    } 

    // Free the second statement
    oci_free_statement($stid2);

    $query3 = 'SELECT SUM(NO_OF_PRODUCTS) AS TOTAL_PRODUCTS FROM CART_ITEM WHERE CART_ID = :cart_id';
        $stid3 = oci_parse($conn, $query3);
        if (!$stid3) {
            $e = oci_error($conn);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        oci_bind_by_name($stid3, ':cart_id', $cart_id);
        oci_execute($stid3);

        $row3 = oci_fetch_array($stid3, OCI_ASSOC);
        if ($row3) {
            $total_products = $row3['TOTAL_PRODUCTS'];
        } 

        // Free the third statement
        oci_free_statement($stid3);

        $query4 = 'SELECT WISHLIST_ID FROM WISHLIST WHERE CUSTOMER_ID = :customer_id';
    $stid4 = oci_parse($conn, $query4);
    if (!$stid4) {
        $e = oci_error($conn);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    oci_bind_by_name($stid4, ':customer_id', $customer_id);
    oci_execute($stid4);

    $row4 = oci_fetch_array($stid4, OCI_ASSOC);
    if ($row4) {
        $wishlist_id = $row4['WISHLIST_ID'];
    }
    oci_free_statement($stid4);

    $query5 = 'SELECT COUNT(PRODUCT_ID) AS TOTAL_WISHLIST_ITEMS FROM WISHLIST_ITEM WHERE WISHLIST_ID = :wishlist_id';
        $stid5 = oci_parse($conn, $query5);
        if (!$stid5) {
            $e = oci_error($conn);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        oci_bind_by_name($stid5, ':wishlist_id', $wishlist_id);
        oci_execute($stid5);

        $row5 = oci_fetch_array($stid5, OCI_ASSOC);
        if ($row5) {
            $total_wishlist_items = $row5['TOTAL_WISHLIST_ITEMS'];
        }
        oci_free_statement($stid5);

        // Close the connection
oci_close($conn);

// Ensure $total_products and $total_wishlist_items are set before echoing
$total_products = isset($total_products) ? $total_products : 0;
$total_wishlist_items = isset($total_wishlist_items) ? $total_wishlist_items : 0;


 if(isset($_POST["search"])){
     // Input Sanizatization 
     require("input_validation\input_sanitization.php");
    $search_text = isset($_POST["searchText"]) ? sanitizeFirstName($_POST["searchText"]) : "";;
    header("Location: search_page.php?value=" . urlencode($search_text)); // URL encode the search text
    exit();

 }
 ?>
 <header>
        <nav>
            <div class="container">
                <div class="logo"><img src="logo.png"></div>
                <div class="nav-links">
                    <ul>
                        <li class="highlight"><a href="index.php">Home</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="session_contactus.php">Contacts</a></li>
                    </ul>
                </div>
                <div class="search">
                <form method="POST" action="" namme="search_form" id="search_form">
                    <input type="text" name="searchText" placeholder="<?php if(isset($search_text)){echo $search_text ; } else { echo "Search...";}?>" id="searchText" required>
                    <input type="submit" value="Search" name="search" id="search">
                </form>
                </div>
                <div class="menu-toggle"><i class="fas fa-bars"></i></div>
                <div class="submenu">
                    <ul>
                        <li class="highlight"><a href="index.php">Home</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contactus.php">Contact Us</a></li>
                        
                    </ul>
                </div>
                <div class="icons">
                    <a href="wishlist.php" class="icon"><i class="fas fa-heart" ></i><span style="background-color: red; color: white; border-radius: 50%; font-size: 15px;"><?php echo $total_wishlist_items; ?></span></a>
                    <a href="cart.php" class="icon"><i class="fas fa-shopping-cart"></i><span style="background-color: red; color: white; border-radius: 50%; font-size: 15px;"><?php echo $total_products; ?></span></a>
                    <div class="profile-icon">
                        <div class="profile-image">
                            <img src="profile_image/<?php echo $_SESSION["picture"]; ?>" alt="<?php echo $_SESSION["name"] ; ?>">
                        </div>
                        <div class="submenu-profile">
                            <ul>
                                <li><a href="customer.php">Profile</a></li>
                                <li><a href="change_password.php">Change Password</a></li>
                                <li><a href="session/logout.php">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                    
            </div>
        </nav>
    </header>

