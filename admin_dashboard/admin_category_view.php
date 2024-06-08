<?php
include("admin_session.php");
// Error Reporting If any error ocuurs
error_reporting(E_ALL);
ini_set('display_errors',1);
// Variable for Input_validation 
$input_validation_passed = true;
$category_id = $_GET["id"];
include("../connection/connection.php");


// Prepare the SQL statement to fetch the row
$sql = "SELECT * FROM PRODUCT_CATEGORY WHERE CATEGORY_ID = :category_id";

// Prepare the statement
$stmt = oci_parse($conn, $sql);

if (!$stmt) {
    $e = oci_error($conn);
    echo "SQL parse error: " . $e['message'];
    exit;
}

// Bind the value to the placeholder
oci_bind_by_name($stmt, ':category_id', $category_id);

// Execute the statement
if (oci_execute($stmt)) {
    // Fetch the row
    $product_category = oci_fetch_assoc($stmt);
    if ($product_category) {
        $category_id = $product_category['CATEGORY_ID'];
        $category_type = $product_category['CATEGORY_TYPE'];
        $category_image = $product_category['CATEGORY_IMAGE'];
    } 
} else {
    $e = oci_error($stmt);
    echo "Error fetching record: " . $e['message'];
}

// Free the statement resources
oci_free_statement($stmt);


if(isset($_POST["saveChangesBtn"])){
     // Input Sanizatization 
     require("../input_validation/input_sanitization.php");
    // Check if $_POST["shop-name"] Exists before sanitizing 
    $category_name = isset($_POST["productName"]) ? sanitizeShopName($_POST["productName"]) : "";

    // Input Validation
    require("../input_validation/input_validation.php");
    $product_name_error = "";
        // Validate shop name
        if (validateShopName($category_name) === "false") {
            $product_name_error = "Please Enter Your Product Name Correctly.";
            $input_validation_passed = false;
        }

         $profile_upload_error="";
         if(isset($_FILES["productImage"]) && $_FILES["productImage"]["error"] == 0){
            // File is uploaded, proceed with image upload logic
            require("../input_validation/image_upload.php");
            $result = uploadImage("../category_picture/", "productImage");
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
            $newFileName =  $category_image;
        }

        if ($input_validation_passed) {
            // Prepare the SQL update statement
$sql = "UPDATE PRODUCT_CATEGORY 
SET CATEGORY_TYPE = :category_type, CATEGORY_IMAGE = :category_image 
WHERE CATEGORY_ID = :category_id";

// Prepare the statement
$stmt = oci_parse($conn, $sql);

if (!$stmt) {
$e = oci_error($conn);
echo "SQL parse error: " . $e['message'];
exit;
}

// Bind the values to the placeholders
oci_bind_by_name($stmt, ':category_type', $category_name);
oci_bind_by_name($stmt, ':category_image', $newFileName);
oci_bind_by_name($stmt, ':category_id', $category_id);

// Execute the statement
if (oci_execute($stmt)) {
// Free the statement resources
oci_free_statement($stmt);

// Close the connection
oci_close($conn);


            // Reload the page with the same GET parameter
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $category_id);
exit();
} else {
$e = oci_error($stmt);
echo "Error updating record: " . $e['message'];
}

// Free the statement resources
oci_free_statement($stmt);

// Close the connection
oci_close($conn);
        }
            
        else{
            $general_error = "Category  Could not be updated Validation failed?";
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
    <link rel="stylesheet" href="admin_navbar.css">
    <link rel="stylesheet" href="admin_product_view.css">
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
        include("admin_navbar.php");
    ?>
    <div class="container-heading">
        <h2 class="container-heading">Product  Details</h2>
        </div>
        <div id="productDetailsContainer" class="product-details-container">
    <div class="left-div">
        <img src="../category_picture/<?php echo $category_image ?>" alt="Product Picture" class="product-picture">
    </div>
    <div class="right-div">
    <?php
                    if(isset($general_error)){
                        echo "<p style='color: red;'>$general_error</p>";
                    }
                    ?>
        <form id="productDetailsForm" class="product-details-form" name="productDetailsForm" method="POST" action="">
            <div class="form-row">
                <label for="productId" class="form-label">Product ID:</label>
                <input type="text" id="productId" name="productId" class="form-input" placeholder="Enter product ID" value="<?php  echo  $category_id; ?>" readonly>
            </div>
            <div class="form-row">
                <label for="productName" class="form-label">Product Name:</label>
                <input type="text" id="productName" name="productName" class="form-input" placeholder="Enter product name" value="<?php echo $category_type;?>">
                <?php
                    if(isset($product_name_error)){
                        echo "<p style='color: red;'>$product_name_error</p>";
                    }
                    ?>
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
                <button id="cancelBtn" class="cancel-btn" onclick="window.location.href='admin_CATEGORY.php' ; return false;">Cancel</button>
            </div>
        </form>
    </div>
</div>
    
<script src="admin_navbar.js"></script>
</body>
</html>