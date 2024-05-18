<?php
require("trader_session.php");
include("../connection/connection.php"); // Include the database connection

$user_id = $_SESSION["userid"]; // Example user ID, you can replace it with a dynamic value

$sql = "SELECT r.REVIEW_ID, r.REVIEW_SCORE, r.REVIEW_DATE, r.FEEDBACK, 
               u.USER_ID, u.FIRST_NAME || ' ' || u.LAST_NAME AS NAME, u.USER_PROFILE_PICTURE, 
               p.PRODUCT_NAME
        FROM review r
        JOIN product p ON r.PRODUCT_ID = p.PRODUCT_ID
        JOIN hudder_user u ON r.USER_ID = u.USER_ID
        WHERE p.USER_ID = :user_id";

$stmt = oci_parse($conn, $sql);
if (!$stmt) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

oci_bind_by_name($stmt, ':user_id', $user_id);

$r = oci_execute($stmt);
if (!$r) {
    $e = oci_error($stmt);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review</title>
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
    <?php include("trader_navbar.php"); ?>
    <h1 class="page-title">Reviews</h1>
    <div class="user-details-container">
        <table border=1 id="myTable">
            <thead>
                <tr>
                    <th>Review ID</th>
                    <th>User Picture</th>
                    <th>User Name</th>
                    <th>User Rating</th>
                    <th>User Review</th>
                    <th>Product Name</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = oci_fetch_assoc($stmt)) { ?>
                    <tr>
                        <td><?php echo $row['REVIEW_ID']; ?></td>
                        <td><img src='../profile_image/<?php echo $row['USER_PROFILE_PICTURE']; ?>' alt='<?php echo $row['NAME']; ?>' style='width:50px;height:50px;'></td>
                        <td><?php echo $row['NAME']; ?></td>
                        <td><?php echo $row['REVIEW_SCORE']; ?></td>
                        <td><?php echo $row['FEEDBACK']; ?></td>
                        <td><?php echo $row['PRODUCT_NAME']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="trader_product.js"></script>
    <script src="trader_navbar.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        let table = new DataTable('#myTable', {
            responsive: true,
        });
    </script>
</body>
</html>
