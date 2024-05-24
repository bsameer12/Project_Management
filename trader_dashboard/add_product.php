<?php
include("trader_session.php");
// Error Reporting If any error ocuurs
error_reporting(E_ALL);
ini_set('display_errors',1);
// Variable for Input_validation 
$input_validation_passed = true;

include("../connection/connection.php");
// Prepare the SQL statement to fetch CATEGORY_ID and CATEGORY_TYPE
$sql = "SELECT CATEGORY_ID, CATEGORY_TYPE FROM PRODUCT_CATEGORY";

// Parse the SQL statement
$stmt = oci_parse($conn, $sql);

// Execute the SQL statement
oci_execute($stmt);

// Initialize an empty array to store the results
$productCategories = array();

// Fetch the results and store in the array
while ($row = oci_fetch_assoc($stmt)) {
    $productCategories[] = $row;
}

// Free statement resources
oci_free_statement($stmt);

if(isset($_POST["saveChangesBtn"])){
     // Input Sanizatization 
     require("../input_validation/input_sanitization.php");
    // Check if $_POST["shop-name"] Exists before sanitizing 
    $product_name = isset($_POST["productName"]) ? sanitizeShopName($_POST["productName"]) : "";

    // Check if $_POST["company-registration-no"] Exists before sanitizing 
    $product_price = isset($_POST["productPrice"]) ? sanitizeCompanyRegNo($_POST["productPrice"]) : "";

    // Check if $_POST["company-registration-no"] Exists before sanitizing 
    $product_quantity = isset($_POST["stockQuantity"]) ? sanitizeCompanyRegNo($_POST["stockQuantity"]) : "";

    // Check if $_POST["shop-description"] Exists before sanitizing 
    $product_description = isset($_POST["productDescription"]) ? sanitizeShopDescription($_POST["productDescription"]) : "";

    // Check if $_POST["shop-description"] Exists before sanitizing 
    $product_allergy = isset($_POST["productallergy"]) ? sanitizeShopDescription($_POST["productallergy"]) : "";

    // Check if $_POST["category"] Exists before sanitizing 
    $category = isset($_POST["productCategory"]) ? sanitizeCategory($_POST["productCategory"]) : "";

    // Input Validation
    require("../input_validation/input_validation.php");
    $product_name_error = "";
        // Check if products exists
        if (productNameExists($product_name) === "true") {
            $product_name_error = "Email Already Exists!!!";
            $input_validation_passed = false;
        }

        // Validate shop name
        if (validateShopName($product_name) === "false") {
            $product_name_error = "Please Enter Your Product Name Correctly.";
            $input_validation_passed = false;
        }

        // Validate Company Registration Number
        $price_error = "";
        if (validateCompanyRegistrationNo($product_price) === "false") {
            $price_error = "Please Enter Your product price in numbers only";
            $input_validation_passed = false;
        }

        // Validate Company Registration Number
        $quantity_error = "";
        if (validateCompanyRegistrationNo($product_quantity) === "false") {
            $quantity_error = "Please Enter Your product quantity in numbers only";
            $input_validation_passed = false;
        }

        // Validate Shop Descripyion
        $product_description_error = "";
        if (validateShopDescription($product_description) === "false") {
            $product_description_error = "Please Enter product description correctly Correctly.";
            $input_validation_passed = false;
        }

         // Validate Shop Descripyion
         $allergy_description_error = "";
         if (validateShopDescription($product_allergy) === "false") {
             $allergy_description_error = "Please Enter Your product allergy Correctly.";
             $input_validation_passed = false;
         }

         // validate product category
         $category_error = "";

         $profile_upload_error="";
         require("../input_validation/image_upload.php");
         $result = uploadImage("../product_image/", "productImage");
         // Check the result
         if ($result["success"] === 1) {
             // If upload was successful, store the new file name in a unique variable
             $newFileName = $result["fileName"];
         } else {
             // If upload failed, display the error message
             $input_validation_passed = false;
             $profile_upload_error = $result["message"];
         }
        

        
        $todayDate = date('Y-m-d'); // Format: YYYY-MM-DD
        $update_date = date('Y-m-d'); // Format: YYYY-MM-DD
        $isdisabled = 0;
        $admin_verify = 0;
        $user_id = $_SESSION["userid"];
        $stockAvailable = "yes";
        if ($input_validation_passed) {
            include("../connection/connection.php");
            // Prepare the SQL statement for inserting data into the product table
            $sql_insert_product = "
            INSERT INTO product (
                PRODUCT_NAME, 
                PRODUCT_DESCRIPTION, 
                PRODUCT_PRICE, 
                PRODUCT_QUANTITY, 
                STOCK_AVAILABLE, 
                IS_DISABLED,  
                ALLERGY_INFORMATION, 
                PRODUCT_PICTURE, 
                PRODUCT_ADDED_DATE, 
                PRODUCT_UPDATE_DATE, 
                CATEGORY_ID, 
                USER_ID,
                ADMIN_VERIFIED
            ) 
            VALUES (
                :productName, 
                :productDescription, 
                :productPrice, 
                :productQuantity, 
                :stockAvailable, 
                :isDisabled, 
                :allergyInformation, 
                :productPicture, 
                TO_DATE(:productAddedDate, 'YYYY-MM-DD'), 
                TO_DATE(:productUpdateDate, 'YYYY-MM-DD'), 
                :categoryID, 
                :userID,
                :ad_var
            )";

            // Prepare the OCI statement
            $stmt_insert_product = oci_parse($conn, $sql_insert_product);

            // Bind parameters
            oci_bind_by_name($stmt_insert_product, ':productName', $product_name);
            oci_bind_by_name($stmt_insert_product, ':productDescription', $product_description);
            oci_bind_by_name($stmt_insert_product, ':productPrice', $product_price);
            oci_bind_by_name($stmt_insert_product, ':productQuantity', $product_quantity);
            oci_bind_by_name($stmt_insert_product, ':stockAvailable', $stockAvailable);
            oci_bind_by_name($stmt_insert_product, ':isDisabled', $isdisabled);
            oci_bind_by_name($stmt_insert_product, ':allergyInformation', $product_allergy);
            oci_bind_by_name($stmt_insert_product, ':productPicture', $newFileName);
            oci_bind_by_name($stmt_insert_product, ':productAddedDate', $todayDate);
            oci_bind_by_name($stmt_insert_product, ':productUpdateDate', $update_date);
            oci_bind_by_name($stmt_insert_product, ':categoryID', $category);
            oci_bind_by_name($stmt_insert_product, ':userID', $user_id);
            oci_bind_by_name($stmt_insert_product, ':ad_var', $admin_verify);

            // Execute the SQL statement
            if (oci_execute($stmt_insert_product)) {
                header("Location: trader_products.php");
                exit();
            } else {
            $error = oci_error($stmt_insert_product);
            echo "Error inserting product: " . $error['message'];
            }

            // Free the statement and close the connection
            oci_free_statement($stmt_insert_product);
            oci_close($conn);
        }
        else{
            $general_error = "Product Details Could not be updated Validation failed?";
        }




}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="trader_navbar.css">
    <link rel="stylesheet" href="trader_product_view.css">
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
        <h2 class="container-heading">Add Product</h2>
        </div>
        <div id="productDetailsContainer" class="product-details-container">
        <div class="left-div">
        
    </div>
    <div class="right-div" style="text-align: center;">
    <?php
                    if(isset($general_error)){
                        echo "<p style='color: red;'>$general_error</p>";
                    }
                    ?>
        <form id="productDetailsForm" class="product-details-form" name="productDetailsForm" method="POST" action="" enctype="multipart/form-data">
            <div class="form-row">
                <label for="productName" class="form-label">Product Name:</label>
                <input type="text" id="productName" name="productName" class="form-input" placeholder="Enter product name" required>
                <?php
                    if(isset($product_name_error)){
                        echo "<p style='color: red;'>$product_name_error</p>";
                    }
                    ?>
            </div>
            <div class="form-row">
                <label for="productCategory" class="form-label">Product Category:</label>
                <select id="productCategory" name="productCategory" class="form-input" required>
    <?php
    // Assuming $productDetails['CATEGORY_ID'] contains the selected category ID
    $selectedCategoryId = isset($productDetails['CATEGORY_ID']) ? $productDetails['CATEGORY_ID'] : '';

    // Loop through the categories and create an option element for each one
    foreach ($productCategories as $category) {
        $categoryId = $category['CATEGORY_ID'];
        $categoryType = $category['CATEGORY_TYPE'];
        $selected = ($categoryId == $selectedCategoryId) ? 'selected' : '';
        echo "<option value='$categoryId' $selected>$categoryType</option>";
    }
    ?>
</select>
                <?php
                    if(isset($category_error)){
                        echo "<p style='color: red;'>$category_error</p>";
                    }
                    ?>
            </div>
            <div class="form-row">
                <label for="productPrice" class="form-label">Product Price:</label>
                <input type="text" id="productPrice" name="productPrice" class="form-input" placeholder="Enter product price" required>
                <?php
                    if(isset($price_error)){
                        echo "<p style='color: red;'>$price_error</p>";
                    }
                    ?>
            </div>
            <div class="form-row">
                <label for="productDescription" class="form-label">Product Description:</label>
                <textarea id="productDescription" name="productDescription" class="form-textarea" rows="4" placeholder="Enter product description" required></textarea>
                <?php
                    if(isset($product_description_error)){
                        echo "<p style='color: red;'>$product_description_error</p>";
                    }
                    ?>
            </div>
            <div class="form-row">
                <label for="productallergy" class="form-label">Product Allergy Information:</label>
                <textarea id="productallergy" name="productallergy" class="form-textarea" rows="4" placeholder="Enter product allergy information" required></textarea>
                <?php
                    if(isset($allergy_description_error)){
                        echo "<p style='color: red;'>$allergy_description_error</p>";
                    }
                    ?>
            </div>
            <div class="form-row">
                <label for="stockQuantity" class="form-label">Stock Quantity:</label>
                <input type="text" id="stockQuantity" name="stockQuantity" class="form-input" placeholder="Enter stock quantity" required>
                <?php
                    if(isset($quantity_error)){
                        echo "<p style='color: red;'>$quantity_error</p>";
                    }
                    ?>
            </div>
            <div class="form-row">
                    <label for="productImage">Upload Product Image:</label>
                    <input type="file" id="productImage" name="productImage" accept="image/*"  required>
                    <?php
                    if(isset($profile_upload_error)){
                        echo "<p style='color: red;'>$profile_upload_error</p>";
                    }
                    ?>
                </div>
            <div class="form-row">
                <input type="submit" id="saveChangesBtn" name ="saveChangesBtn" class="submit-btn" value="Save Changes">
                <button id="cancelBtn" class="cancel-btn" onclick="window.location.href='trader_products.php' ; return false;">Cancel</button>
            </div>
        </form>
    </div>
</div>
<script src="trader_navbar.js"></script>
</body>
</html>