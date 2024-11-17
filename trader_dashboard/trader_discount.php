<?php
include("trader_session.php");
// Error Reporting If any error ocuurs
error_reporting(E_ALL);
ini_set('display_errors',1);
$user_id = $_SESSION["userid"];
// Variable for Input_validation 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Discount Details</title>
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
    <h1 class="page-title">Product Discount Details</h1>
    <div class="product-container">
        <div class="search-container">
        <button class="create-new-btn" onclick="redirectToAddProduct()">Add Product Discount</button>
        </div>
    </div>


    <div class="user-details-container">
        <table border=1 id="myTable">
        <thead>
        <tr> 
        <th> Discount ID </th> 
        <th> Product ID </th>
        <th> Product Picture </th>
        <th> Product Name </th>
        <th> Product Price </th>
        <th> Discount Occassion </th>
        <th> Discount Percent </th>
        <th> Discounted Price</th>
        <th> Actions </th>
        </tr>
        </thead>
        <tbody>
            <?php
                include("../connection/connection.php");
                    // If sort option is not set, fetch data without sorting
                    $sql = "SELECT d.DISCOUNT_ID, d.DISCOUNT_OCCASSION, d.DISCOUNT_PERCENT, 
                    p.PRODUCT_ID, p.PRODUCT_NAME, p.PRODUCT_PRICE, p.PRODUCT_PICTURE
             FROM DISCOUNT d
             JOIN PRODUCT p ON d.PRODUCT_ID = p.PRODUCT_ID
             WHERE p.USER_ID = :user_id";
                // Parse the SQL statement
                $stmt = oci_parse($conn, $sql);

                // Bind parameters
                oci_bind_by_name($stmt, ':user_id', $user_id);

                // Execute the SQL statement
                oci_execute($stmt);

                // Fetch rows from the result set
                while ($row = oci_fetch_assoc($stmt)) {
                        echo "<tr>";
                        echo "<td>" . $row['DISCOUNT_ID'] . " </td>";
                        echo "<td>" . $row['PRODUCT_ID'] . " </td>";
                        echo "<td><img src='../product_image/" . $row['PRODUCT_PICTURE'] ."' alt='Product Image' style='width:50px;height:50px;'></td>";
                        echo "<td>" . $row['PRODUCT_NAME'] . " </td>";
                        echo "<td>" . $row['PRODUCT_PRICE'] . "</td>";
                        echo "<td>" . $row['DISCOUNT_OCCASSION'] . "</td>";
                        echo "<td>" . $row['DISCOUNT_PERCENT'] . "</td>";
                        $actual_amount = $row['PRODUCT_PRICE'];
                        $discount_percent = $row['DISCOUNT_PERCENT'];
                        $discount_amount = $actual_amount * ($discount_percent / 100);
                        echo "<td>" . $discount_amount . "</td>";
                        echo "<td> <a href=trader_product_discount_view.php?id=". $row['DISCOUNT_ID'] . "&userid=" . $user_id . "&product=" .$row['PRODUCT_ID']. "&action=edit> Edit </a> | <a href=deleteproductdiscount.php?id=" . $row['DISCOUNT_ID'] . "&action=delete> Delete </a> </td>";
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
            window.location.href = 'add_product_discount.php'; // Replace 'add_product.php' with your target URL
        }
    </script>
</body>
</html>