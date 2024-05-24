<?php
include("trader_session.php");
// Error Reporting If any error ocuurs
error_reporting(E_ALL);
ini_set('display_errors',1);
$user_id = $_SESSION["userid"];
// Variable for Input_validation 
$input_validation_passed = true;
if(isset($_POST["submit_product"])){
     // Input Sanizatization 
     require("../input_validation/input_sanitization.php");
    // Check if $_POST["shop-name"] Exists before sanitizing 
    $product_name = isset($_POST["productName"]) ? sanitizeShopName($_POST["productName"]) : "";

    // Check if $_POST["company-registration-no"] Exists before sanitizing 
    $product_price = isset($_POST["price"]) ? sanitizeCompanyRegNo($_POST["price"]) : "";

    // Check if $_POST["company-registration-no"] Exists before sanitizing 
    $product_quantity = isset($_POST["quantity"]) ? sanitizeCompanyRegNo($_POST["quantity"]) : "";

    // Check if $_POST["shop-description"] Exists before sanitizing 
    $product_description = isset($_POST["description"]) ? sanitizeShopDescription($_POST["description"]) : "";

    // Check if $_POST["shop-description"] Exists before sanitizing 
    $product_allergy = isset($_POST["allergy"]) ? sanitizeShopDescription($_POST["allergy"]) : "";

    // Check if $_POST["category"] Exists before sanitizing 
    $category = isset($_POST["category"]) ? sanitizeCategory($_POST["category"]) : "";

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
         if(validateCategory($category) === "false"){
            $category_error = "Please Select your category  Correctly.";
            $input_validation_passed = false;
         }

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
                USER_ID
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
                :userID
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

            // Execute the SQL statement
            if (oci_execute($stmt_insert_product)) {
                header("Location: ".$_SERVER['REQUEST_URI']);
                exit();
            } else {
            $error = oci_error($stmt_insert_product);
            echo "Error inserting product: " . $error['message'];
            }

            // Free the statement and close the connection
            oci_free_statement($stmt_insert_product);
            oci_close($conn);
        }




}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="trader_navbar.css">
    <link rel="stylesheet" href="trader_products.css">
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
    <h1 class="page-title">Product Details</h1>
    <div class="product-container">
    <div class="search-container">
        <button class="create-new-btn" onclick="redirectToAddProduct()">Add Product</button>
        </div>
    </div>
    <div class="form-popup" id="productForm">
    <div class="form-container">
        <h2>Product Registration Form</h2>
        <span class="close" onclick="closeForm()">&times;</span>
        <div class="profile-circle" id="productImagePreview"></div>
        <form id="productForms" name="productForms" action="" enctype="multipart/form-data" method="POST">
            <!-- Add fields for product details -->
            <div class="row">
                <div class="col">
                    <label for="productName">Product Name:</label>
                    <input type="text" id="productName" name="productName" required>
                    <?php
                    if(isset($product_name_error)){
                        echo "<p style='color: red;'>$product_name_error</p>";
                    }
                    ?>
                </div>
                <div class="col">
                    <label for="category">Category:</label>
                    <select id="category" name="category" required>
                        <option value="1">Bravery</option>
                        <option value="2">Fruits</option>
                        <option value="3">Drinks</option>
                        <option value="4">Vegetables</option>
                        <option value="5">Meat</option>
                        <!-- Add more options as needed -->
                    </select>
                    <?php
                    if(isset($category_error)){
                        echo "<p style='color: red;'>$category_error</p>";
                    }
                    ?>
                </div>
                <!-- Add more fields as needed -->
            </div>
            <div class="row">
                <div class="col">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" required>
                    <?php
                    if(isset($price_error)){
                        echo "<p style='color: red;'>$price_error</p>";
                    }
                    ?>
                </div>
            </div>
            <div class="row">
            <div class="col">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" required>
                    <?php
                    if(isset($quantity_error)){
                        echo "<p style='color: red;'>$quantity_error</p>";
                    }
                    ?>
                </div>
                <div class="col">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" placeholder="Enter product description" required style="width: 100%; height: 4em;"></textarea>
                    <?php
                    if(isset($product_description_error)){
                        echo "<p style='color: red;'>$product_description_error</p>";
                    }
                    ?>
                </div>

                <div class="col">
                    <label for="description">Allergy:</label>
                    <textarea id="allergy" name="allergy" placeholder="Enter allergy information" required style="width: 100%; height: 4em;"></textarea>
                    <?php
                    if(isset($allergy_description_error)){
                        echo "<p style='color: red;'>$allergy_description_error</p>";
                    }
                    ?>
                </div>

                <div class="col">
                    <label for="productImage">Upload Product Image:</label>
                    <input type="file" id="productImage" name="productImage" accept="image/*" onchange="previewProductImage()" required>
                    <?php
                    if(isset($profile_upload_error)){
                        echo "<p style='color: red;'>$profile_upload_error</p>";
                    }
                    ?>
                </div>

            </div>
                <input type="submit" id="submit_product" name="submit_product" value="Add Product" class="form-buttons" style="background-color: #4CAF50; color: white; text-align: center; ">
        </form>
    </div>
</div>
</div>
</div>


    <div class="user-details-container">
        <table border=1 id="myTable">
        <thead>
        <tr> 
        <th>  Product ID </th> 
        <th> Product Picture </th>
        <th> Product Name </th>
        <th> Category </th>
        <th> Price </th>
        <th> Quantity </th>
        <th> Status </th>
        <th> Description </th>
        <th> Actions </th>
        </tr>
        </thead>
        <tbody>
            <?php
                include("../connection/connection.php");
                    // If sort option is not set, fetch data without sorting
                    $sql = "SELECT 
                                P.PRODUCT_ID, 
                                P.PRODUCT_NAME, 
                                P.PRODUCT_DESCRIPTION, 
                                P.PRODUCT_PRICE, 
                                P.PRODUCT_QUANTITY, 
                                P.IS_DISABLED, 
                                P.CATEGORY_ID, 
                                P.PRODUCT_PICTURE, 
                                PC.CATEGORY_TYPE
                            FROM 
                                product P
                            JOIN 
                                PRODUCT_CATEGORY PC ON P.CATEGORY_ID = PC.CATEGORY_ID
                            WHERE 
                                P.USER_ID = :userID";
                // Parse the SQL statement
                $stmt = oci_parse($conn, $sql);

                // Bind parameters
                oci_bind_by_name($stmt, ':userID', $user_id);

                // Execute the SQL statement
                oci_execute($stmt);

                // Fetch rows from the result set
                while ($row = oci_fetch_assoc($stmt)) {
                        echo "<tr>";
                        echo "<td>" . $row['PRODUCT_ID'] . " </td>";
                        echo "<td><img src='../product_image/" . $row['PRODUCT_PICTURE'] ."' alt='Product Image' style='width:50px;height:50px;'></td>";
                        echo "<td>" . $row['PRODUCT_NAME'] . " </td>";
                        echo "<td>" . $row['CATEGORY_TYPE'] . "</td>";
                        echo "<td>" . $row['PRODUCT_PRICE'] . "</td>";
                        echo "<td>" . $row['PRODUCT_QUANTITY'] . "</td>";
                        echo "<td>" . ($row['IS_DISABLED'] == 1 ? 'Enabled' : 'Disabled') . "</td>";
                        echo "<td>" . $row['PRODUCT_DESCRIPTION'] . "</td>";
                        echo "<td> <a href=trader_product_view.php?id=". $row['PRODUCT_ID'] . "&userid=" . $user_id . "&action=edit> Edit </a>";
                        echo "</tr>";
                }
                // Free statement resources
                oci_free_statement($stmt);
                // Close the Oracle connection
                oci_close($conn);
                ?>
                 </tbody>
                </table>
                </div>
    <script src="trader_product.js"></script>
    <script src="trader_navbar.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js">
    </script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js">
    </script>
    <script>
        let table = new DataTable('#myTable', {
        responsive: true,
        });
        window.onload = function() {
        var sortSelect = document.getElementById("sort");
        var selectedValue = localStorage.getItem("selectedSortValue");
        if (selectedValue) {
            sortSelect.value = selectedValue;
        }
    };

    function redirectToAddProduct() {
            window.location.href = 'add_product.php'; // Replace 'add_product.php' with your target URL
        }
    </script>
</body>
</html>