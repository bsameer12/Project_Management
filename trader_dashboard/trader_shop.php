<?php
require("trader_session.php");
include("../connection/connection.php");

// Initialize variables for placeholders
$trader_user_id = $_SESSION["userid"];

// Query to select user and shop details using JOIN
$sql_user_shop_details = "SELECT 
    U.FIRST_NAME || ' ' || U.LAST_NAME AS NAME, 
    U.USER_EMAIL, 
    U.USER_CONTACT_NO,
    S.SHOP_NAME, 
    S.SHOP_ID, 
    S.SHOP_DESCRIPTION, 
    S.SHOP_PROFILE, 
    S.SHOP_CATEGORY_ID, 
    S.REGISTRATION_NO,
    PC.CATEGORY_TYPE
FROM 
    HUDDER_USER U
JOIN 
    SHOP S ON U.USER_ID = S.USER_ID
JOIN 
    PRODUCT_CATEGORY PC ON S.SHOP_CATEGORY_ID = PC.CATEGORY_ID
WHERE 
    U.USER_ID = :user_id
";

$stmt_user_shop_details = oci_parse($conn, $sql_user_shop_details);
oci_bind_by_name($stmt_user_shop_details, ':user_id', $trader_user_id);
oci_execute($stmt_user_shop_details);

// Fetch user and shop details
$user_shop_details = oci_fetch_assoc($stmt_user_shop_details);

// Free statement and close connection
oci_free_statement($stmt_user_shop_details);


// Extracting data from the array
$shop_id = $user_shop_details['SHOP_ID'];
$registration_no = $user_shop_details['REGISTRATION_NO'];; // You need to fetch this data from the database
$shop_category = $user_shop_details['CATEGORY_TYPE']; // Assuming this is the category ID
$shop_owner = $user_shop_details['NAME']; // Assuming NAME contains the shop owner's name
$shop_description = $user_shop_details['SHOP_DESCRIPTION'];
$email = $user_shop_details['USER_EMAIL'];
$contact_number = $user_shop_details['USER_CONTACT_NO'];
$shop_profile = $user_shop_details['SHOP_PROFILE'];
$shop_name =  $user_shop_details['SHOP_NAME'];


if(isset($_POST["saveChangesBtn"])){
    // Variable for Input_validation 
    $input_validation_passed = true;
    // Input Sanizatization 
    require("../input_validation/input_sanitization.php");
    // Check if $_POST["shop-name"] Exists before sanitizing 
    $shop_name = isset($_POST["shopname"]) ? sanitizeShopName($_POST["shopname"]) : "";

    // Check if $_POST["company-registration-no"] Exists before sanitizing 
    $company_no = isset($_POST["registrationNo"]) ? sanitizeCompanyRegNo($_POST["registrationNo"]) : "";

     // Check if $_POST["shop-description"] Exists before sanitizing 
     $shop_description = isset($_POST["shopDescription"]) ? sanitizeShopDescription($_POST["shopDescription"]) : "";

     // Input Validation
    require("../input_validation/input_validation.php");

     // Validate shop name
     $shop_name_error = "";
     if (validateShopName($shop_name) === "false") {
         $shop_name_error = "Please Enter Your Shop Name Correctly.";
         $input_validation_passed = false;
     }

     // Validate Company Registration Number
     $company_no_error = "";
     if (validateCompanyRegistrationNo($company_no) === "false") {
         $company_no_error = "Please Enter Your Comapany Registration Number Correctly.";
         $input_validation_passed = false;
     }

      // Validate Shop Descripyion
      $shop_description_error = "";
      if (validateShopDescription($shop_description) === "false") {
          $shop_description_error = "Please Enter Your Comapany Registration Number Correctly.";
          $input_validation_passed = false;
      }

      require("../input_validation/image_upload.php");
      if(isset($_FILES["shop-logo"])){
      $shop_profile_upload_error="";
      $result2 = uploadImage("../shop_profile_image/", "shop-logo");
          // Check the result
          if ($result2["success"] === 1) {
              // If upload was successful, store the new file name in a unique variable
              $newFileName_shop = $result2["fileName"];
          } else {
              // If upload failed, display the error message
              $input_validation_passed = false;
              $shop_profile_upload_error = $result2["message"];
          }
        }else{
            $newFileName_shop = $shop_profile;
        }


        if ($input_validation_passed) {
             // Construct the SQL UPDATE statement
                    $sql_update_shop = "UPDATE SHOP 
                    SET SHOP_NAME = :shop_name, 
                        SHOP_DESCRIPTION = :shop_description, 
                        SHOP_PROFILE = :shop_profile, 
                        REGISTRATION_NO = :registration_no 
                    WHERE SHOP_ID = :shop_id";

                // Prepare the statement
                $stmt_update_shop = oci_parse($conn, $sql_update_shop);

                // Bind the parameters
                oci_bind_by_name($stmt_update_shop, ':shop_name', $shop_name);
                oci_bind_by_name($stmt_update_shop, ':shop_description', $shop_description);
                oci_bind_by_name($stmt_update_shop, ':shop_profile', $newFileName_shop);
                oci_bind_by_name($stmt_update_shop, ':registration_no', $company_no);
                oci_bind_by_name($stmt_update_shop, ':shop_id', $shop_id);

                // Execute the statement
                oci_execute($stmt_update_shop);

                // Free the statement
                oci_free_statement($stmt_update_shop);

                // Redirect to the same page to reload
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit;

                
        }

        
     // Close the connection
     oci_close($conn);   



}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="trader_navbar.css">
    <link rel="stylesheet" href="trader_shop.css">
    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Link to fontawesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <?php
        include("trader_navbar.php");
    ?>
        <div class="container-heading">
        <h2 class="container-heading">Shop Details</h2>
        </div>
        <div id="shopDetailsContainer" class="shop-details-container">
        <div class="left-div">
            <img src="../shop_profile_image/<?php echo $shop_profile;?>" alt="<?php echo $shop_name;?>" class="shop-picture">
        </div>
        <div class="right-div">
            <form id="shopDetailsForm" class="shop-details-form" name="shopDetailsForm" method="POST" action="" enctype="multipart/form-data">
                <div class="form-row">
                    <label for="shopId" class="form-label">Shop ID:</label>
                    <input type="text" id="shopId" name="shopId" class="form-input" placeholder="Enter shop ID" readonly value="<?php echo $shop_id; ?>">
                </div>
                <div class="form-row">
                    <label for="shopname" class="form-label">Shop Name:</label>
                    <input type="text" id="shopname" name="shopname" class="form-input" placeholder="Enter shop ID"  value="<?php echo $shop_name; ?>">
                </div>
                <div class="form-row">
                    <label for="registrationNo" class="form-label">Registration No:</label>
                    <input type="text" id="registrationNo" name="registrationNo" class="form-input" placeholder="Enter registration number" value="<?php echo $registration_no; ?>">
                </div>
                <div class="form-row">
                    <label for="shopCategory" class="form-label">Shop Category:</label>
                    <input type="text" id="shopCategory" name="shopCategory" class="form-input" placeholder="Enter shop category" value="<?php echo $shop_category; ?>" readonly>
                </div>
                <div class="form-row">
                    <label for="shopOwner" class="form-label">Shop Owner:</label>
                    <input type="text" id="shopOwner" name="shopOwner" class="form-input" placeholder="Enter shop owner" value="<?php echo $shop_owner; ?>" readonly>
                </div>
                <div class="form-row">
                    <label for="shopDescription" class="form-label">Shop Description:</label>
                    <textarea id="shopDescription" name="shopDescription" class="form-textarea" rows="4" placeholder="Enter shop description"><?php echo $shop_description; ?></textarea>
                </div>
                <div class="form-row">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Enter email" readonly value="<?php echo $email; ?>">
                </div>
                <div class="form-row">
                    <label for="contactNumber" class="form-label">Contact Number:</label>
                    <input type="text" id="contactNumber" name="contactNumber" class="form-input" placeholder="Enter contact number" readonly value="<?php echo $contact_number; ?>">
                </div>
                <div class="form-row">
            <label for="shop-logo">Shop Logo</label>
            <input type="file" id="shop-logo" name="shop-logo" accept="image/*">
            <?php
            if (!empty($shop_profile_upload_error)) {
                    echo "<p style='color: red;'>$shop_profile_upload_error</p>";
                }
                ?>
        </div>
                <div class="form-row">
                    <input type="submit" id="saveChangesBtn" class="submit-btn" value="Save Changes" name="saveChangesBtn">
                    <button id="cancelBtn" class="cancel-btn" onclick="window.location.href='trader_dashboard.php' ; return false;">Cancel</button>
                </div>
            </form>
        </div>
    </div>
<script src="trader_navbar.js"></script>
</body>
</html>