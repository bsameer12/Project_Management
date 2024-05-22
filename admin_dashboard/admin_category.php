<?php
 include("admin_session.php");
// Error Reporting If any error occurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../connection/connection.php");

// Variable for Input_validation 
$input_validation_passed = true;

if (isset($_POST["submit_product"])) {
    // Input Sanitization 
    require("../input_validation/input_sanitization.php");

    // Check if $_POST["categoryName"] exists before sanitizing 
    $category_name = isset($_POST["categoryName"]) ? sanitizeShopName($_POST["categoryName"]) : "";

    // Input Validation
    require("../input_validation/input_validation.php");
    $product_name_error = "";

    // Validate category name
    if (validateShopName($category_name) === "false") {
        $product_name_error = "Please Enter Your Category Name Correctly.";
        $input_validation_passed = false;
    }

    $profile_upload_error = "";
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

    if ($input_validation_passed) {
        // Prepare the SQL insert statement
        $sql = "INSERT INTO PRODUCT_CATEGORY (CATEGORY_TYPE, CATEGORY_IMAGE) VALUES (:category_type, :category_image)";

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

        // Execute the statement
        if (oci_execute($stmt)) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $e = oci_error($stmt);
            echo "Error inserting record: " . $e['message'];
        }

        // Free the statement resources
        oci_free_statement($stmt);

        // Close the connection
        oci_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="admin_navbar.css">
    <link rel="stylesheet" href="admin_product.css">
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
    <h1 class="page-title">Category Details</h1>
    <div class="product-container">
        <div class="search-container">
            <button class="create-new-btn" onclick="openForm()">Add Category</button>
        </div>
    </div>
    <div class="form-popup" id="productForm">
        <div class="form-container">
            <h2>Category Registration Form</h2>
            <span class="close" onclick="closeForm()">&times;</span>
            <div class="profile-circle" id="productImagePreview"></div>
            <form id="productForms" name="productForms" action="" enctype="multipart/form-data" method="POST">
                <div class="row">
                    <div class="col">
                        <label for="categoryName">Category Name:</label>
                        <input type="text" id="categoryName" name="categoryName" required>
                        <?php
                        if (isset($product_name_error)) {
                            echo "<p style='color: red;'>$product_name_error</p>";
                        }
                        ?>
                    </div>

                    <div class="col">
                        <label for="productImage">Upload Product Image:</label>
                        <input type="file" id="productImage" name="productImage" accept="image/*" onchange="previewProductImage()" required>
                        <?php
                        if (isset($profile_upload_error)) {
                            echo "<p style='color: red;'>$profile_upload_error</p>";
                        }
                        ?>
                    </div>
                </div>
                <input type="submit" id="submit_product" name="submit_product" value="Add Product" class="form-buttons" style="background-color: #4CAF50; color: white; text-align: center; ">
            </form>
        </div>
    </div>

    <div class="user-details-container">
        <table border=1 id="myTable">
            <thead>
                <tr>
                    <th>Category ID</th>
                    <th>Category Picture</th>
                    <th>Category Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    include("../connection/connection.php");
                    $sql = "SELECT * FROM PRODUCT_CATEGORY";
                    $stmt = oci_parse($conn, $sql);
                    oci_execute($stmt);

                    while ($row = oci_fetch_assoc($stmt)) {
                        echo "<tr>";
                        echo "<td>" . $row['CATEGORY_ID'] . " </td>";
                        echo "<td><img src='../category_picture/" . $row['CATEGORY_IMAGE'] . "' alt='Product Image' style='width:50px;height:50px;'></td>";
                        echo "<td>" . $row['CATEGORY_TYPE'] . " </td>";
                        echo "<td> <a href='admin_category_view.php?id=" . $row['CATEGORY_ID'] . "&action=edit'> Edit </a> | <a href='deletecategory.php?id=" . $row['CATEGORY_ID'] . "&action=delete'> Delete </a> </td>";
                        echo "</tr>";
                    }
                    oci_free_statement($stmt);
                    oci_close($conn);
                ?>
            </tbody>
        </table>
    </div>
    <script src="admin_product.js"></script>
    <script src="admin_navbar.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
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

        function submitForm() {
            var sortSelect = document.getElementById("sort");
            localStorage.setItem("selectedSortValue", sortSelect.value);
            document.getElementById("sortForm").submit();
            // Reload the page
            location.reload();
        }
    </script>
</body>
</html>
