<?php
session_start();
$orderId = $_GET['order_id']; // Assuming 'order_id' is passed from the previous page
$customer_id = $_GET["customer_id"];
$amount = $_GET['total_price'];
$product = $_GET["total_products"];

// Include connection file to the database
include("connection/connection.php");

// Insert payment details into the payment table
$paymentDate = date('Y-m-d'); // Assuming payment date is today's date
$paymentType = 'PayPal'; // Assuming payment type is PayPal
$paymentAmount = $amount / 100; // Convert amount back to pounds (£)

$insertPaymentSql = "INSERT INTO PAYMENT (PAYMENT_DATE, PAYMENT_TYPE, PAYMENT_AMOUNT, CUSTOMER_ID, ORDER_PRODUCT_ID) 
                     VALUES (TO_DATE(:paymentDate, 'YYYY-MM-DD'), :paymentType, :paymentAmount, :customerId, :orderId)";

$insertPaymentStmt = oci_parse($conn, $insertPaymentSql);
oci_bind_by_name($insertPaymentStmt, ':paymentDate', $paymentDate);
oci_bind_by_name($insertPaymentStmt, ':paymentType', $paymentType);
oci_bind_by_name($insertPaymentStmt, ':paymentAmount', $paymentAmount);
oci_bind_by_name($insertPaymentStmt, ':customerId', $customer_id);
oci_bind_by_name($insertPaymentStmt, ':orderId', $orderId);
$insertSuccess = oci_execute($insertPaymentStmt);

if ($insertSuccess) {
    // Update ORDER_PRODUCT table to mark the order as paid
    $updateOrderStatusSql = "UPDATE ORDER_PRODUCT SET ORDER_STATUS = 1 WHERE ORDER_PRODUCT_ID = :orderId";
    $updateOrderStatusStmt = oci_parse($conn, $updateOrderStatusSql);
    oci_bind_by_name($updateOrderStatusStmt, ':orderId', $orderId);
    oci_execute($updateOrderStatusStmt);

    // Close connections
    oci_free_statement($insertPaymentStmt);
    oci_free_statement($updateOrderStatusStmt);

    // Redirect to order confirmation page with the order ID
    // Prepare SQL query
        $sql = "SELECT OP.NO_OF_PRODUCT, OP.TOTAL_PRICE, OP.DISCOUNT_AMOUNT,
        CS.SLOT_DATE, CS.SLOT_TIME, CS.SLOT_DAY, CS.LOCATION
        FROM ORDER_PRODUCT OP
        JOIN COLLECTION_SLOT CS ON OP.ORDER_PRODUCT_ID = CS.ORDER_PRODUCT_ID
        WHERE OP.ORDER_PRODUCT_ID = :order_id";

        $stmt = oci_parse($conn, $sql);

        if (!$stmt) {
        $e = oci_error($conn);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // Bind the order_id parameter
        oci_bind_by_name($stmt, ':order_id', $orderId);

        // Execute the query
        oci_execute($stmt);

    
            // Fetch the results
            while (($row = oci_fetch_assoc($stmt)) != false) {
                // Append row data to the output variable
                $no_product =   $row['NO_OF_PRODUCT'];
                $total_price = $row['TOTAL_PRICE'];
                $discount = $row['DISCOUNT_AMOUNT'];
                $date = $row['SLOT_DATE'];
                $time =  $row['SLOT_TIME'];
                $day = $row['SLOT_DAY'];
                $location = $row['LOCATION'];
            }
            $email = $_SESSION["email"];
            $pickup_date = $date . " , " . $day;


            require("PHPMailer-master/customer_email_invoice.php");
            sendOrderConfirmationEmail($email, $orderId, $total_price, $no_product, $pickup_date, $time, $location);


        // Free statement resources
        oci_free_statement($stmt);

        // Close the connection
        oci_close($conn);

    header('Location: order_confirmation.php?order_id=' . $orderId);
    exit;
} else {
    echo "Failed to insert payment details.";
}
?>
