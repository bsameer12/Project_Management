<?php
include("trader_session.php");
// Error Reporting If any error ocuurs
error_reporting(E_ALL);
ini_set('display_errors',1);
// Variable for Input_validation 
$input_validation_passed = true;
$product_id = $_GET["id"];
$user_id = $_GET["userid"];
include("../connection/connection.php");

$query = '
    SELECT 
        CATEGORY_ID, 
        CATEGORY_TYPE 
    FROM 
        PRODUCT_CATEGORY
';

$stid = oci_parse($conn, $query);
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Executing the statement
oci_execute($stid);

$categories = array();

// Fetching the results
while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
    $categories[] = array(
        'CATEGORY_ID' => $row['CATEGORY_ID'],
        'CATEGORY_TYPE' => $row['CATEGORY_TYPE']
    );
}

// Free the statement and close the connection
oci_free_statement($stid);

// Prepare the SQL statement
$sql = "SELECT PRODUCT_ID, PRODUCT_NAME, CATEGORY_ID, PRODUCT_PRICE, PRODUCT_DESCRIPTION, ALLERGY_INFORMATION, PRODUCT_QUANTITY, STOCK_AVAILABLE, IS_DISABLED, PRODUCT_ADDED_DATE, PRODUCT_PICTURE
        FROM product 
        WHERE PRODUCT_ID = :product_id AND USER_ID = :user_id";

// Parse the SQL statement
$stmt = oci_parse($conn, $sql);

// Bind parameters
oci_bind_by_name($stmt, ':product_id', $product_id);
oci_bind_by_name($stmt, ':user_id', $user_id);

// Execute the SQL statement
oci_execute($stmt);

// Fetch the row
$row = oci_fetch_assoc($stmt);

// Define an array to store the product details
$productDetails = array();

// Check if a row was found
if ($row) {
    // Store product details in the array
    $productDetails['PRODUCT_ID'] = $row['PRODUCT_ID'];
    $productDetails['PRODUCT_NAME'] = $row['PRODUCT_NAME'];
    $productDetails['CATEGORY_ID'] = $row['CATEGORY_ID'];
    $productDetails['PRODUCT_PRICE'] = $row['PRODUCT_PRICE'];
    $productDetails['PRODUCT_DESCRIPTION'] = $row['PRODUCT_DESCRIPTION'];
    $productDetails['ALLERGY_INFORMATION'] = $row['ALLERGY_INFORMATION'];
    $productDetails['PRODUCT_QUANTITY'] = $row['PRODUCT_QUANTITY'];
    $productDetails['STOCK_AVAILABLE'] = $row['STOCK_AVAILABLE'];
    $productDetails['IS_DISABLED'] = $row['IS_DISABLED'];
    $productDetails['PRODUCT_ADDED_DATE'] = $row['PRODUCT_ADDED_DATE'];
    $productDetails['PRODUCT_PICTURE'] = $row['PRODUCT_PICTURE'];
} else {
    // Set product details to null if no product found
    $productDetails = null;
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
    $category = isset($_POST["productCategory"]) ? $_POST["productCategory"] : "";

    // Check if $_POST["company-registration-no"] Exists before sanitizing 
    $status = isset($_POST["productStatus"]) ? $_POST["productStatus"] : "";

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


         $profile_upload_error="";
         if(isset($_FILES["productImage"]) && $_FILES["productImage"]["error"] == 0){
            // File is uploaded, proceed with image upload logic
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
        } else {
            // No file uploaded, use the existing product picture
            $newFileName =  $productDetails['PRODUCT_PICTURE'];
        }

        $update_date = date('Y-m-d'); // Format: YYYY-MM-DD
        $user_id = $_SESSION["userid"];
        if ($input_validation_passed) {
            // Prepare the SQL statement for updating data in the product table
                $sql_update_product = "
                UPDATE product 
                SET 
                    PRODUCT_NAME = :productName, 
                    PRODUCT_DESCRIPTION = :productDescription, 
                    PRODUCT_PRICE = :productPrice, 
                    PRODUCT_QUANTITY = :productQuantity, 
                    ALLERGY_INFORMATION = :allergyInformation, 
                    PRODUCT_PICTURE = :productPicture, 
                    PRODUCT_UPDATE_DATE = TO_DATE(:productUpdateDate, 'YYYY-MM-DD'), 
                    CATEGORY_ID = :categoryID,
                    IS_DISABLED = :status
                WHERE 
                    PRODUCT_ID = :productID
                    AND USER_ID = :userID"; // assuming PRODUCT_ID is the primary key

                // Prepare the OCI statement for updating
                $stmt_update_product = oci_parse($conn, $sql_update_product);

                // Bind parameters
                oci_bind_by_name($stmt_update_product, ':productName', $product_name);
                oci_bind_by_name($stmt_update_product, ':productDescription', $product_description);
                oci_bind_by_name($stmt_update_product, ':productPrice', $product_price);
                oci_bind_by_name($stmt_update_product, ':productQuantity', $product_quantity);
                oci_bind_by_name($stmt_update_product, ':allergyInformation', $product_allergy);
                oci_bind_by_name($stmt_update_product, ':productPicture', $newFileName);
                oci_bind_by_name($stmt_update_product, ':productUpdateDate', $update_date);
                oci_bind_by_name($stmt_update_product, ':categoryID', $category);
                oci_bind_by_name($stmt_update_product, ':status', $status);
                oci_bind_by_name($stmt_update_product, ':userID', $user_id);
                oci_bind_by_name($stmt_update_product, ':productID', $product_id); // Assuming $product_id is the ID of the product to update

                // Execute the SQL statement
                if (oci_execute($stmt_update_product)) {
                // Update successful
                header("Location: ".$_SERVER['REQUEST_URI']);
                exit();
                } else {
                $error = oci_error($stmt_update_product);
                echo "Error updating product: " . $error['message'];
                }

                // Free the statement
                oci_free_statement($stmt_update_product);
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
    <title>Product</title>
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
        <h2 class="container-heading">Product  Details</h2>
        </div>
        <div id="productDetailsContainer" class="product-details-container">
    <div class="left-div">
        <img src="../product_image/<?php echo $productDetails['PRODUCT_PICTURE']; ?>" alt="Product Picture" class="product-picture">
    </div>
    <div class="right-div">
    <?php
                    if(isset($general_error)){
                        echo "<p style='color: red;'>$general_error</p>";
                    }
                    ?>
        <form id="productDetailsForm" class="product-details-form" name="productDetailsForm" method="POST" action="" enctype="multipart/form-data">
            <div class="form-row">
                <label for="productId" class="form-label">Product ID:</label>
                <input type="text" id="productId" name="productId" class="form-input" placeholder="Enter product ID" value="<?php  echo  $productDetails['PRODUCT_ID']?>" readonly>
            </div>
            <div class="form-row">
                <label for="productName" class="form-label">Product Name:</label>
                <input type="text" id="productName" name="productName" class="form-input" placeholder="Enter product name" value="<?php echo $productDetails['PRODUCT_NAME'];?>">
                <?php
                    if(isset($product_name_error)){
                        echo "<p style='color: red;'>$product_name_error</p>";
                    }
                    ?>
            </div>
            <div class="form-row">
                <label for="productCategory" class="form-label">Product Category:</label>
                <select id="productCategory" name="productCategory" class="form-input">
                <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['CATEGORY_ID']; ?>" <?php echo ($productDetails['CATEGORY_ID'] == $category['CATEGORY_ID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['CATEGORY_TYPE']); ?>
                        </option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row">
                <label for="productPrice" class="form-label">Product Price:</label>
                <input type="text" id="productPrice" name="productPrice" class="form-input" placeholder="Enter product price" value="<?php echo $productDetails['PRODUCT_PRICE'];?>">
                <?php
                    if(isset($price_error)){
                        echo "<p style='color: red;'>$price_error</p>";
                    }
                    ?>
            </div>
            <div class="form-row">
                <label for="productDescription" class="form-label">Product Description:</label>
                <textarea id="productDescription" name="productDescription" class="form-textarea" rows="4" placeholder="Enter product description"><?php echo $productDetails['PRODUCT_DESCRIPTION'];?></textarea>
                <?php
                    if(isset($product_description_error)){
                        echo "<p style='color: red;'>$product_description_error</p>";
                    }
                    ?>
            </div>
            <div class="form-row">
                <label for="productallergy" class="form-label">Product Allergy Information:</label>
                <textarea id="productallergy" name="productallergy" class="form-textarea" rows="4" placeholder="Enter product allergy information"><?php echo $productDetails['ALLERGY_INFORMATION'];?></textarea>
                <?php
                    if(isset($allergy_description_error)){
                        echo "<p style='color: red;'>$allergy_description_error</p>";
                    }
                    ?>
            </div>
            <div class="form-row">
                <label for="stockQuantity" class="form-label">Stock Quantity:</label>
                <input type="text" id="stockQuantity" name="stockQuantity" class="form-input" placeholder="Enter stock quantity" value="<?php echo $productDetails['PRODUCT_QUANTITY'];?>">
                <?php
                    if(isset($quantity_error)){
                        echo "<p style='color: red;'>$quantity_error</p>";
                    }
                    ?>
            </div>
            <div class="form-row">
                <label for="quantityStatus" class="form-label">Quantity Status:</label>
                <input type="text" id="quantityStatus" name="quantityStatus" class="form-input" placeholder="Enter quantity status" value="<?php echo $productDetails['STOCK_AVAILABLE'];?>" readonly>
            </div>
            <div class="form-row">
            <label for="productStatus" class="form-label">Product Status:</label>
                <select id="productStatus" name="productStatus" class="form-input">
                    <option value="1" <?php echo ($productDetails['IS_DISABLED'] == 1) ? 'selected' : ''; ?>>Enabled</option>
                    <option value="0" <?php echo ($productDetails['IS_DISABLED'] == 0) ? 'selected' : ''; ?>>Disabled</option>
                </select>
            </div>
            <div class="form-row">
                <label for="productdate" class="form-label">Product Added On:</label>
                <input type="date" id="productdate" name="productdate" class="form-input" placeholder="Enter product status" value="<?php echo date('Y-m-d', strtotime($productDetails['PRODUCT_ADDED_DATE']));?>" readonly>
            </div>
            <div class="form-row">
                    <label for="productImage">Upload Product Image:</label>
                    <input type="file" id="productImage" name="productImage" accept="image/*" onchange="previewProductImage()">
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