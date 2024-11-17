<?php
include("trader_session.php");
// Error Reporting If any error occurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_SESSION["userid"];
$discount_id = isset($_GET["id"]) ? $_GET["id"] : null;
$product_id = isset($_GET["product"]) ? $_GET["product"] : null;

include("../connection/connection.php");

// Initialize variables
$product_picture = '';
$product_name = '';
$product_price = '';
$discount_occasion = '';
$discount_percent = '';
$discount_amount = '';

if ($discount_id && $product_id) {
    // Prepare the SQL statement to fetch the discount and product details
    $sql = "SELECT d.DISCOUNT_ID, d.DISCOUNT_OCCASSION, d.DISCOUNT_PERCENT, d.PRODUCT_ID, 
                   p.PRODUCT_NAME, p.PRODUCT_PRICE, p.PRODUCT_PICTURE
            FROM DISCOUNT d
            JOIN PRODUCT p ON d.PRODUCT_ID = p.PRODUCT_ID
            WHERE d.DISCOUNT_ID = :discount_id AND d.PRODUCT_ID = :product_id";

    // Prepare the statement
    $stmt = oci_parse($conn, $sql);

    // Bind the values to the placeholders
    oci_bind_by_name($stmt, ':discount_id', $discount_id);
    oci_bind_by_name($stmt, ':product_id', $product_id);

    // Execute the statement
    oci_execute($stmt);

    // Fetch the row
    $row = oci_fetch_assoc($stmt);

    // Store the fetched data into variables
    if ($row) {
        $discount_id = $row['DISCOUNT_ID'];
        $discount_occasion = $row['DISCOUNT_OCCASSION'];
        $discount_percent = $row['DISCOUNT_PERCENT'];
        $product_id = $row['PRODUCT_ID'];
        $product_name = $row['PRODUCT_NAME'];
        $product_price = $row['PRODUCT_PRICE'];
        $product_picture = $row['PRODUCT_PICTURE'];
        $discount_amount = $product_price * ($discount_percent / 100);
    }

    // Free the statement resources
    oci_free_statement($stmt);
}

$input_validation_passed = true;
if (isset($_POST["saveChangesBtn"])) {
    // Input Sanitization
    require("../input_validation/input_sanitization.php");

    // Check if $_POST["shop-description"] Exists before sanitizing
    $discount_occassion = isset($_POST["productDescription"]) ? sanitizeShopDescription($_POST["productDescription"]) : "";

    // Check if $_POST["company-registration-no"] Exists before sanitizing
    $discount_percent = isset($_POST["stockQuantity"]) ? sanitizeCompanyRegNo($_POST["stockQuantity"]) : "";

    // Input Validation
    require("../input_validation/input_validation.php");

    // Validate Shop Description
    $product_description_error = "";
    if (validateShopDescription($discount_occassion) === "false") {
        $product_description_error = "Please Enter product description correctly.";
        $input_validation_passed = false;
    }


    // Validate Company Registration Number
    $quantity_error = "";
    if (validateCompanyRegistrationNo($discount_percent) === "false") {
        $quantity_error = "Please Enter Your product quantity in numbers only";
        $input_validation_passed = false;
    }

    if ($input_validation_passed) {
        // Prepare the SQL insert statement
        $sql = "UPDATE DISCOUNT
        SET DISCOUNT_OCCASSION = :discount_occasion,
            DISCOUNT_PERCENT = :discount_percent
        WHERE DISCOUNT_ID = :discount_id
          AND PRODUCT_ID = :product_id";

        // Prepare the statement
        $stmt = oci_parse($conn, $sql);

        // Bind the values to the placeholders
        oci_bind_by_name($stmt, ':discount_occasion', $discount_occasion);
        oci_bind_by_name($stmt, ':discount_percent', $discount_percent);
        oci_bind_by_name($stmt, ':discount_id', $discount_id);
        oci_bind_by_name($stmt, ':product_id', $product_id);

        // Execute the statement
        if (oci_execute($stmt)) {
            header("Location: ".$_SERVER['PHP_SELF']."?id=$discount_id&userid=$user_id&product=$product_id&action=edit");
            exit; // Make sure to exit after redirection to prevent further script execution
        } else {
            $e = oci_error($stmt);
            echo "Error inserting discount data: " . $e['message'];
        }

        // Free the statement resources
        oci_free_statement($stmt);
    } else {
        $general_error = "Product Details could not be updated. Validation failed.";
    }
}
// Close the connection
oci_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Discount Details</title>
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
        <h2 class="container-heading">Product Discount Details</h2>
    </div>
    <div id="productDetailsContainer" class="product-details-container">
        <div class="left-div">
            <img src="../product_image/<?php echo $product_picture; ?>" alt="Product Picture" class="product-picture">
        </div>

        <div class="right-div">
            <?php
            if (isset($general_error)) {
                echo "<p style='color: red;'>$general_error</p>";
            }
            ?>
            <form id="productDetailsForm" class="product-details-form" name="productDetailsForm" method="POST" action="">
                <div class="form-row">
                    <label for="productName" class="form-label">Product Name:</label>
                    <input type="text" id="productName" name="productName" class="form-input" placeholder="Enter product name" value="<?php echo $product_name;?>" readonly>
                </div>

                <div class="form-row">
                    <label for="productDescription" class="form-label">Discount Occasion:</label>
                    <textarea id="productDescription" name="productDescription" class="form-textarea" rows="4" placeholder="Enter discount occasion"><?php echo $discount_occasion; ?></textarea>
                    <?php
                    if (isset($product_description_error)) {
                        echo "<p style='color: red;'>$product_description_error</p>";
                    }
                    ?>
                </div>
                <div class="form-row">
                    <label for="stockQuantity" class="form-label">Discount Percent:</label>
                    <input type="text" id="stockQuantity" name="stockQuantity" class="form-input" placeholder="Enter discount percent" value="<?php echo $discount_percent; ?>">
                    <?php
                    if (isset($quantity_error)) {
                        echo "<p style='color: red;'>$quantity_error</p>";
                    }
                    ?>
                </div>
                <div class="form-row">
                    <label for="productPrice" class="form-label">Discount Amount:</label>
                    <input type="text" id="productPrice" name="productPrice" class="form-input" placeholder="Enter product price" value="<?php echo $discount_amount;?>" readonly>
                </div>

                <div class="form-row">
                    <input type="submit" id="saveChangesBtn" name="saveChangesBtn" class="submit-btn" value="Create Discount">
                    <button id="cancelBtn" class="cancel-btn" onclick="window.location.href='trader_discount.php'; return false;">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <script src="trader_navbar.js"></script>
</body>

</html>
