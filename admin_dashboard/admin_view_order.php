<?php
 include("admin_session.php");
include("../connection/connection.php"); // Include the database connection


$order_product_id = $_GET['id']; // Assuming you're retrieving this from a form

// Query to select PRODUCT_ID, PRODUCT_QTY, PRODUCT_PRICE from ORDER_DETAILS
$sql_product_details = "SELECT OD.PRODUCT_ID, OD.PRODUCT_QTY, OD.PRODUCT_PRICE, P.PRODUCT_PICTURE, P.PRODUCT_NAME, P.PRODUCT_PRICE AS ACTUAL_PRICE
FROM ORDER_DETAILS OD
JOIN PRODUCT P ON OD.PRODUCT_ID = P.PRODUCT_ID
WHERE OD.ORDER_PRODUCT_ID = :order_product_id";

$stmt_product_details = oci_parse($conn, $sql_product_details);
oci_bind_by_name($stmt_product_details, ':order_product_id', $order_product_id);
oci_execute($stmt_product_details);

// Fetch product details
$product_details = array();
while ($row = oci_fetch_assoc($stmt_product_details)) {
    $product_details[] = $row;
}

// Query to select ORDER_STATUS, TOTAL_PRICE, DISCOUNT_AMOUNT, CUSTOMER_ID, ORDER_DATE from ORDER_PRODUCT
$sql_order_details = "SELECT ORDER_STATUS, TOTAL_PRICE, DISCOUNT_AMOUNT, CUSTOMER_ID, ORDER_DATE
                      FROM ORDER_PRODUCT
                      WHERE ORDER_PRODUCT_ID = :order_product_id";

$stmt_order_details = oci_parse($conn, $sql_order_details);
oci_bind_by_name($stmt_order_details, ':order_product_id', $order_product_id);
oci_execute($stmt_order_details);

// Fetch order details
$order_details = oci_fetch_assoc($stmt_order_details);

// Query to select SLOT_DATE, SLOT_TIME, LOCATION from COLLECTION_SLOT
$sql_slot_details = "SELECT SLOT_DATE, SLOT_TIME, LOCATION
                     FROM COLLECTION_SLOT
                     WHERE ORDER_PRODUCT_ID = :order_product_id";

$stmt_slot_details = oci_parse($conn, $sql_slot_details);
oci_bind_by_name($stmt_slot_details, ':order_product_id', $order_product_id);
oci_execute($stmt_slot_details);

// Fetch slot details
$slot_details = oci_fetch_assoc($stmt_slot_details);

// Query to select PAYMENT_TYPE from PAYMENT
$sql_payment_type = "SELECT PAYMENT_TYPE
                     FROM PAYMENT
                     WHERE CUSTOMER_ID = :customer_id
                     AND ORDER_PRODUCT_ID = :order_product_id";

$stmt_payment_type = oci_parse($conn, $sql_payment_type);
oci_bind_by_name($stmt_payment_type, ':customer_id', $order_details['CUSTOMER_ID']);
oci_bind_by_name($stmt_payment_type, ':order_product_id', $order_product_id);
oci_execute($stmt_payment_type);

// Fetch payment type
$payment_type = oci_fetch_assoc($stmt_payment_type);

// Free statements and close connection
oci_free_statement($stmt_product_details);
oci_free_statement($stmt_order_details);
oci_free_statement($stmt_slot_details);
oci_free_statement($stmt_payment_type);
oci_close($conn);

// Function to get the status text based on the status value
function getOrderStatusText($status) {
    switch ($status) {
        case 0:
            return "Order Incompleted";
        case 1:
            return "Payment Complete";
        case 2:
            return "Order Prepared";
        case 3:
            return "Order Ready to Pick Up";
        case 4:
            return "Order Delivered";
        default:
            return "Unknown Status";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detailed Order Details</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="admin_navbar.css">
    <link rel="stylesheet" href="admin_products.css">
    <link rel="stylesheet" href="admin_view_order.css">
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
    <?php include("admin_navbar.php"); ?>
    <div id="orderFormContainer" class="order-form-container">
        <img src="../logo.png" alt="Company Logo" class="logo_detail">
        <h2 class="form-heading">Detailed Order Details</h2>
        <form id="orderForm" class="order-form">
    <div class="form-row">
        <div class="form-column">
            <label for="orderId" class="form-label">Order ID:</label>
            <input type="text" id="orderId" name="orderId" class="form-input" placeholder="Enter order ID" readonly value="<?php echo $order_product_id ?>" >
        </div>
        <div class="form-column">
            <label for="customerId" class="form-label">Customer ID:</label>
            <input type="text" id="customerId" name="customerId" class="form-input" placeholder="Enter customer ID" readonly value="<?php echo $order_details['CUSTOMER_ID']; ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="form-column">
            <label for="orderDate" class="form-label">Order Date:</label>
            <input type="date" id="orderDate" name="orderDate" class="form-input" readonly  value="<?php echo date('Y-m-d', strtotime($order_details['ORDER_DATE'])); ?>">
        </div>
        <div class="form-column">
            <label for="pickupDate" class="form-label">Pickup Date:</label>
            <input type="date" id="pickupDate" name="pickupDate" class="form-input" readonly  value="<?php echo date('Y-m-d', strtotime($slot_details['SLOT_DATE'])); ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="form-column">
            <label for="pickupLocation" class="form-label">Pickup Location:</label>
            <input type="text" id="pickupLocation" name="pickupLocation" class="form-input" placeholder="Enter pickup location" readonly  value="<?php echo $slot_details['LOCATION'] . " ," . $slot_details['SLOT_TIME']; ?>">
        </div>
        <div class="form-column">
            <label for="orderStatus" class="form-label">Order Status:</label>
            <input type="text" id="orderId" name="orderId" class="form-input" placeholder="Enter order ID" readonly value="<?php echo htmlspecialchars(getOrderStatusText($order_details['ORDER_STATUS'])); ?>" readonly>
        </div>
    </div>
</form>
    </div>
    <div id="productTableContainer" class="product-table-container">
    <table class="product-table">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Picture</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Actual Price </th>
                <th>Sale Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($product_details as $product): ?>
            <tr>
                <td><?php echo $product['PRODUCT_ID']; ?></td>
                <td><img src="../product_image/<?php echo $product['PRODUCT_PICTURE'];?>" alt="<?php echo $product['PRODUCT_NAME']; ?>" class="product-image"></td>
                <td><?php echo $product['PRODUCT_NAME']; ?></td>
                <td><?php echo $product['PRODUCT_QTY']; ?></td>
                <td><?php echo '$' . $product['ACTUAL_PRICE']; ?></td>
                <td><?php echo '$' . $product['PRODUCT_PRICE']; ?></td>
                <td><?php echo '$' . ($product['PRODUCT_QTY'] * $product['PRODUCT_PRICE']); ?></td>
            </tr>
        <?php endforeach; ?>
            <!-- Add more rows as needed -->
        </tbody>
    </table>
</div>
<div id="paymentFormContainer" class="payment-form-container">
<form id="paymentForm" class="payment-form">
    <div class="form-row">
    <div class="form-column">
        <label for="netTotal" class="form-label">Net Total:</label>
        <?php
            // Calculate total amount after discount
            $total_amount = $order_details['TOTAL_PRICE'] + $order_details['DISCOUNT_AMOUNT'];
        ?>
        <input type="text" id="netTotal" name="netTotal" class="form-input" placeholder="Enter net total" readonly value="<?php echo $total_amount; ?>">
    </div>
    <div class="form-column">
        <label for="discountPercent" class="form-label">Discount Percent:</label>
        <?php
            // Calculate discount percent
            $discount_percent = ($order_details['DISCOUNT_AMOUNT'] / $total_amount) * 100;
        ?>
        <input type="text" id="discountPercent" name="discountPercent" class="form-input" placeholder="Enter discount percent" readonly value="<?php echo $discount_percent; ?>">
    </div>
</div>
<div class="form-row">
    <div class="form-column">
        <label for="discountAmount" class="form-label">Discount Amount:</label>
        <input type="text" id="discountAmount" name="discountAmount" class="form-input" placeholder="Enter discount amount" readonly value="<?php echo $order_details['DISCOUNT_AMOUNT']; ?>">
    </div>
    <div class="form-column">
        <label for="totalAmount" class="form-label">Total Amount:</label>
        
        <input type="text" id="totalAmount" name="totalAmount" class="form-input" placeholder="Enter total amount" readonly value="<?php echo $order_details['TOTAL_PRICE']; ?>">
    </div>
</div>

<div class="form-row">
    <div class="form-column">
        <label for="paymentMode" class="form-label">Payment Mode:</label>
        <input type="text" id="paymentMode" name="paymentMode" class="form-input" placeholder="Enter payment mode" value="<?php echo !empty($payment_type) ? $payment_type['PAYMENT_TYPE'] : 'Payment Incomplete'; ?>" readonly>
    </div>
    <div class="form-column">
        <label for="paymentStatus" class="form-label">Payment Status:</label>
        <input type="text" id="paymentStatus" name="paymentStatus" class="form-input" placeholder="Enter payment status" value="<?php echo !empty($payment_type) ? 'Payment Completed' : 'Payment Incomplete'; ?>" readonly>
    </div>
</div>

</form>
</div>
    <div id="returnToOrdersContainer" class="return-to-orders-container">
        <button id="returnToOrdersBtn" class="return-to-orders-btn">Return to Orders</button>
    </div>
    <script>
        document.getElementById('returnToOrdersBtn').addEventListener('click', function() {
            window.location.href = 'admin_orders.php';
        });
    </script>
    <script src="trader_navbar.js"></script>
</body>
</html>
