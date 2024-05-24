<?php
include("trader_session.php");
// Error Reporting If any error occurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_SESSION["userid"];

include("../connection/connection.php");

// Define an array to store the results
$productList = array();

// Prepare the SQL statement
$sql = "SELECT PRODUCT_ID, PRODUCT_NAME FROM PRODUCT WHERE USER_ID = :user_id";

// Prepare the statement
$stmt = oci_parse($conn, $sql);

// Bind the value to the placeholder
oci_bind_by_name($stmt, ':user_id', $user_id);

// Execute the statement
oci_execute($stmt);

// Fetch rows from the result set
while ($row = oci_fetch_assoc($stmt)) {
    // Add the row to the product list array
    $productList[] = $row;
}

// Free statement resources
oci_free_statement($stmt);

$product_id = null; // Initialize product_id variable
$product_picture = ''; // Initialize product_picture variable

if (isset($_POST["productCategory"])) {
    $product_id = $_POST["productCategory"];
    $sql = "SELECT PRODUCT_PICTURE FROM PRODUCT WHERE PRODUCT_ID = :product_id";

    // Prepare the statement
    $stmt = oci_parse($conn, $sql);

    // Bind the value to the placeholder
    oci_bind_by_name($stmt, ':product_id', $product_id);

    // Execute the statement
    oci_execute($stmt);

    // Fetch the row
    $row = oci_fetch_assoc($stmt);

    // Store the PRODUCT_PICTURE in a variable
    $product_picture = $row ? $row['PRODUCT_PICTURE'] : '';

    // Free the statement resources
    oci_free_statement($stmt);
}

$input_validation_passed = true;
if (isset($_POST["saveChangesBtn"])) {
    // Input Sanitization
    require("../input_validation/input_sanitization.php");
    // Check if $_POST["category"] Exists before sanitizing
    $product_id = isset($_POST["productCategory"]) ? sanitizeCategory($_POST["productCategory"]) : "";

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
        $sql = "INSERT INTO DISCOUNT (DISCOUNT_OCCASSION, DISCOUNT_PERCENT, PRODUCT_ID) VALUES (:discount_occasion, :discount_percent, :discount_amount)";

        // Prepare the statement
        $stmt = oci_parse($conn, $sql);

        // Bind the values to the placeholders
        oci_bind_by_name($stmt, ':discount_occasion', $discount_occassion);
        oci_bind_by_name($stmt, ':discount_percent', $discount_percent);
        oci_bind_by_name($stmt, ':discount_amount', $product_id);

        // Execute the statement
        if (oci_execute($stmt)) {
            header("Location: trader_discount.php");
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
    <title>Product Discount Add</title>
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
        <h2 class="container-heading">Add Product Discount</h2>
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
                    <label for="productCategory" class="form-label">Select A Product:</label>
                    <select id="productCategory" name="productCategory" class="form-input" onchange="this.form.submit()">
                        <?php foreach ($productList as $product) : ?>
                            <option value="<?php echo $product['PRODUCT_ID']; ?>" <?php echo ($product['PRODUCT_ID'] == $product_id) ? 'selected' : ''; ?>>
                                <?php echo $product['PRODUCT_NAME']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php
                    if (isset($category_error)) {
                        echo "<p style='color: red;'>$category_error</p>";
                    }
                    ?>
                </div>
                <div class="form-row">
                    <label for="productDescription" class="form-label">Discount Occasion:</label>
                    <textarea id="productDescription" name="productDescription" class="form-textarea" rows="4" placeholder="Enter discount occasion"><?php echo isset($discount_occassion) ? $discount_occassion : ''; ?></textarea>
                    <?php
                    if (isset($product_description_error)) {
                        echo "<p style='color: red;'>$product_description_error</p>";
                    }
                    ?>
                </div>
                <div class="form-row">
                    <label for="stockQuantity" class="form-label">Discount Percent:</label>
                    <input type="text" id="stockQuantity" name="stockQuantity" class="form-input" placeholder="Enter discount percent" value="<?php echo isset($discount_percent) ? $discount_percent : ''; ?>">
                    <?php
                    if (isset($quantity_error)) {
                        echo "<p style='color: red;'>$quantity_error</p>";
                    }
                    ?>
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

