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
 oci_close($conn);


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
                <a href="index.php" class="logo"><img src="logo.png"></a>
                <div class="nav-links">
                    <ul>
                        <li class="highlight"><a href="index.php">Home</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contactus.php">Contacts</a></li>
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
                        <li><a href="contactus.php">Contacts</a></li>
                        <li><a href="category.php">Category</a></li>
                        <li><a href="customer_signin.php">Sign In</a></li>
                        <li><a href="customer_signup.php">Sign Up</a></li>
                    </ul>
                </div>
                <div class="icons">
                    <a href="wishlist.php" class="icon"><i class="fas fa-heart" ></i></a>
                    <a href="cart.php" class="icon"><i class="fas fa-shopping-cart"></i></a>
                    <div class="nav-links">
                        <ul>
                            <li><a href="customer_signin.php">Sign In</a></li>
                            <li><a href="customer_signup.php">Sign Up</a></li>
                        </ul>
                    </div>
                    
            </div>
        </nav>
    </header>
