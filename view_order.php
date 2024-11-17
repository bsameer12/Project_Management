<?php
require("session/session.php");
include("connection/connection.php"); // Include the database connection

// Initialize variables for placeholders
$order_product_id = $_GET['id']; // Assuming you're retrieving this from a form

// Query to select PRODUCT_ID, PRODUCT_QTY, PRODUCT_PRICE from ORDER_DETAILS
$sql_product_details = "SELECT OD.PRODUCT_ID, OD.PRODUCT_QTY, OD.PRODUCT_PRICE, P.PRODUCT_PICTURE, P.PRODUCT_NAME, P.PRODUCT_PRICE AS ACTUAL_PRICE
FROM ORDER_DETAILS OD
JOIN PRODUCT P ON OD.PRODUCT_ID = P.PRODUCT_ID
WHERE  OD.ORDER_PRODUCT_ID = :order_product_id";

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
    <title>Detailed Order</title>
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="customer.css">
    <link rel="stylesheet" href="trader_dashboard/trader_products.css">
    <link rel="stylesheet" href="trader_dashboard/trader_view_order.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons.js"></script>
    <style>

        body{
            padding: 25px;
        }

        .order-details-table th, .order-details-table td {
        padding-right: 10px; /* Adjust spacing to your preference */
        vertical-align: top; /* Aligns text to the top of the cell */
        }

        .order-details-table {
        width: 45%; /* Adjust table width as needed */
        margin-left: auto;
        margin-right: auto;
        }
        .payment-details-table th, .payment-details-table td{
         padding-right: 30px; /* Adjust spacing to your preference */
        vertical-align: top; /* Aligns text to the top of the cell */
        }

        .payment-details-table{
        width: 50%; /* Adjust table width as needed */
        margin-left: auto;
        margin-right: auto;
        }


    
</style>

</head>
<body>
    <?php include("session_navbar.php"); ?>
    <div id="orderFormContainer" class="order-form-container">
        <img src="logo.png" alt="Company Logo" class="logo_detail">
        <h2 class="form-heading">Detailed Order</h2>
        <table class="order-details-table">
            <tbody>
                <tr>
                    <th>Order ID:</th>
                    <td><?php echo $order_product_id; ?></td>
                </tr>
                <tr>
                    <th>Customer ID:</th>
                    <td><?php echo $order_details['CUSTOMER_ID']; ?></td>
                </tr>
                <tr>
                    <th>Order Date:</th>
                    <td><?php echo date('Y-m-d', strtotime($order_details['ORDER_DATE'])); ?></td>
                </tr>
                <tr>
                    <th>Pickup Date:</th>
                    <td><?php echo date('Y-m-d', strtotime($slot_details['SLOT_DATE'])); ?></td>
                </tr>
                <tr>
                    <th>Pickup Location:</th>
                    <td><?php echo $slot_details['LOCATION'] . " ," . $slot_details['SLOT_TIME']; ?></td>
                </tr>
                <tr>
                    <th>Order Status:</th>
                    <td><?php echo htmlspecialchars(getOrderStatusText($order_details['ORDER_STATUS'])); ?></td>
                </tr>
            </tbody>
        </table>
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
                <td><img src="product_image/<?php echo $product['PRODUCT_PICTURE'];?>" alt="<?php echo $product['PRODUCT_NAME']; ?>" class="product-image"></td>
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
    <h2 class="form-heading">Payment Details</h2>
    <table class="payment-details-table">
        <tbody>
            <tr>
                <th>Net Total:</th>
                <td>
                    <?php
                        // Calculate total amount after discount
                        $total_amount = $order_details['TOTAL_PRICE'] + $order_details['DISCOUNT_AMOUNT'];
                        echo $total_amount;
                    ?>
                </td>
            </tr>
            <tr>
                <th>Discount Percent:</th>
                <td>
                    <?php
                        // Calculate discount percent
                        $discount_percent = ($order_details['DISCOUNT_AMOUNT'] / $total_amount) * 100;
                        echo number_format($discount_percent, 2); // Formats the discount percent to 2 decimal places
                    ?>
                </td>
            </tr>
            <tr>
                <th>Discount Amount:</th>
                <td><?php echo $order_details['DISCOUNT_AMOUNT']; ?></td>
            </tr>
            <tr>
                <th>Total Amount:</th>
                <td><?php echo $order_details['TOTAL_PRICE']; ?></td>
            </tr>
            <tr>
                <th>Payment Mode:</th>
                <td><?php echo !empty($payment_type) ? $payment_type['PAYMENT_TYPE'] : 'Payment Incomplete'; ?></td>
            </tr>
            <tr>
                <th>Payment Status:</th>
                <td><?php echo !empty($payment_type) ? 'Payment Completed' : 'Payment Incomplete'; ?></td>
            </tr>
        </tbody>
    </table>
</div>

</div>
<div id="returnToOrdersContainer" class="return-to-orders-container">
    <button onclick="window.location.href='customer.php'" class="return-to-orders-btn">Return to Profile</button>
</div>
<?php
        include("footer.php");
    ?>
 <script src="without_session_navbar.js"></script>
<script src="customer.js"></script>
</body>
</html>
