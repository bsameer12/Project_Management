<?php
 include("admin_session.php");
include("../connection/connection.php");

// Handle IS_DISABLED form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateForm'])) {
    $product_id = $_POST['product_id'];
    $is_disabled = $_POST['is_disabled'];

    // Prepare the SQL statement to update the IS_DISABLED field
    $sql = "UPDATE PRODUCT SET IS_DISABLED = :is_disabled WHERE PRODUCT_ID = :product_id";

    // Prepare the statement
    $stmt = oci_parse($conn, $sql);

    // Bind the parameters
    oci_bind_by_name($stmt, ':is_disabled', $is_disabled);
    oci_bind_by_name($stmt, ':product_id', $product_id);

    // Execute the statement
    $success = oci_execute($stmt);

    // Free the statement
    oci_free_statement($stmt);

    // Reload the page on success
    if ($success) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// Handle ADMIN_VERIFIED form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verifyForm'])) {
    $product_id = $_POST['product_id'];
    $admin_verified = $_POST['admin_verified'];

    // Prepare the SQL statement to update the ADMIN_VERIFIED field
    $sql = "UPDATE PRODUCT SET ADMIN_VERIFIED = :admin_verified WHERE PRODUCT_ID = :product_id";

    // Prepare the statement
    $stmt = oci_parse($conn, $sql);

    // Bind the parameters
    oci_bind_by_name($stmt, ':admin_verified', $admin_verified);
    oci_bind_by_name($stmt, ':product_id', $product_id);

    // Execute the statement
    $success = oci_execute($stmt);

    // Free the statement
    oci_free_statement($stmt);

    // Reload the page on success
    if ($success) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// Fetch product details
$sql = "SELECT P.*, U.FIRST_NAME || ' ' || U.LAST_NAME AS NAME 
        FROM PRODUCT P
        JOIN HUDDER_USER U ON P.USER_ID = U.USER_ID";

$stmt = oci_parse($conn, $sql);
oci_execute($stmt);

$products = [];
while ($row = oci_fetch_assoc($stmt)) {
    $products[] = $row;
}

oci_free_statement($stmt);
oci_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
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
    <h1 class="page-title">Product Details</h1>


    <div class="user-details-container">
        <table border=1 id="myTable">
        <thead>
        <tr> 
                    <th> Product ID </th> 
                    <th> Product Image </th>
                    <th> Product Name </th>
                    <th> Product Category </th>
                    <th> Price </th>
                    <th> Product Description </th>
                    <th> Allergy Information </th>
                    <th> Trader Name </th>
                    <th> Prooduct Stock </th>
                    <th> Product Status </th>
                    <th> Verification Status </th>
                    <!-- Add more headers for product details -->
                    <th> Actions </th> 
        </tr>
        </thead>
        <tbody>
        
        <?php foreach ($products as $product) { ?>
                    <tr>
                        <td><?php echo $product['PRODUCT_ID']; ?></td>
                        <td><img src='../product_image/<?php echo $product['PRODUCT_PICTURE']; ?>' alt='Product Image' style='width:50px;height:50px;'></td>
                        <td><?php echo $product['PRODUCT_NAME']; ?></td>
                        <td><?php echo $product['CATEGORY_ID']; ?></td>
                        <td><?php echo $product['PRODUCT_PRICE']; ?></td>
                        <td><?php echo $product['PRODUCT_DESCRIPTION']; ?></td>
                        <td><?php echo $product['ALLERGY_INFORMATION']; ?></td>
                        <td><?php echo $product['NAME']; ?></td>
                        <td><?php echo $product['PRODUCT_QUANTITY']; ?></td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product['PRODUCT_ID']; ?>">
                                <input type="hidden" name="updateForm">
                                <select name="is_disabled" onchange="this.form.submit()">
                                    <option value="0" <?php echo ($product['IS_DISABLED'] == 0) ? 'selected' : ''; ?>>Disabled</option>
                                    <option value="1" <?php echo ($product['IS_DISABLED'] == 1) ? 'selected' : ''; ?>>Not Disabled</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product['PRODUCT_ID']; ?>">
                                <input type="hidden" name="verifyForm">
                                <select name="admin_verified" onchange="this.form.submit()">
                                    <option value="0" <?php echo ($product['ADMIN_VERIFIED'] == 0) ? 'selected' : ''; ?>>Unverified</option>
                                    <option value="1" <?php echo ($product['ADMIN_VERIFIED'] == 1) ? 'selected' : ''; ?>>Verified</option>
                                </select>
                            </form>
                        </td>

                        <td>
                            <a href="admin_product_view.php?id=<?php echo $product['PRODUCT_ID']; ?>&action=edit">Edit</a> | 
                            <a href="deleteproduct.php?id=<?php echo $product['PRODUCT_ID']; ?>&action=delete">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
        </tbody>
        </table>
    </div>
    <script src="admin_product.js"></script>
    <script src="admin_navbar.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js">
    </script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js">
    </script>
    <script>
        let table = new DataTable('#myTable', {
        responsive: true,
        });
        </script>
</body>
</html>