<?php
$order_id = $_GET["order_id"];


// Include the database connection
include("connection/connection.php");


 // Prepare SQL statement
 $sql = "UPDATE PRODUCT 
 SET STOCK_AVAILABLE = 'no', IS_DISABLED = 0 
 WHERE PRODUCT_QUANTITY < 1";

// Parse the SQL query
$stmt = oci_parse($conn, $sql);
if (!$stmt) {
$e = oci_error($conn);
throw new Exception("Failed to prepare statement: " . $e['message']);
}

// Execute the SQL statement
$r = oci_execute($stmt);
if (!$r) {
$e = oci_error($stmt);
throw new Exception("Failed to execute statement: " . $e['message']);
}


// Free the statement identifier
oci_free_statement($stmt);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pickup slot</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="order_confirmation.css">
    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php
        include("session/session.php");
        include("session_navbar.php");
    ?>
    <div class="confirmation-container">
        <div class="confirmation-header">
        <span class="confirmation-icon mr-2"><i class="fas fa-check-circle text-success"></i></span>
            <h1 class="confirmation-title">Ordered Confirmed</h1>
        </div>
        <div class="confirmation-message">
            <h2>Your order has been confirmed and your order number is <strong id="order-number"><?php echo $order_id; ?></strong>.</h2>
        </div>
        <div class="return-home">
            <a href="index.php" class="return-home-link">Return to Home</a>
        </div>
    </div>
    </div>
    <?php
        include("footer.php");
    ?>
    <script src="slot_time.js"> </script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="without_session_navbar.js"> </script>
</body>
</html>