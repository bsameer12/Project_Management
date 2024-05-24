<?php
session_start();
$orderId = $_GET['order_id']; // Assuming 'order_id' is passed from the previous page
$customer_id = $_GET["customer_id"];
$amount = $_GET['total_price'];
$product = $_GET["total_products"];
$userId = $_SESSION["userid"];
$reviewProvided = 0;

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

    // Close the payment statement
    oci_free_statement($insertPaymentStmt);
    oci_free_statement($updateOrderStatusStmt);

    // Prepare SQL query to get order details
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
    oci_execute($stmt);

    // Fetch the results
    $row = oci_fetch_assoc($stmt);
    if ($row) {
        $no_product = $row['NO_OF_PRODUCT'];
        $total_price = $row['TOTAL_PRICE'];
        $discount = $row['DISCOUNT_AMOUNT'];
        $date = $row['SLOT_DATE'];
        $time = $row['SLOT_TIME'];
        $day = $row['SLOT_DAY'];
        $location = $row['LOCATION'];
    }
    oci_free_statement($stmt);

    $email = $_SESSION["email"];
    $pickup_date = $date . " , " . $day;

    // Select all PRODUCT_ID from ORDER_DETAILS where ORDER_ID matches the given order ID
    $selectProductIdsSql = "SELECT PRODUCT_ID FROM ORDER_DETAILS WHERE ORDER_PRODUCT_ID = :orderId";
    $selectProductIdsStmt = oci_parse($conn, $selectProductIdsSql);
    oci_bind_by_name($selectProductIdsStmt, ':orderId', $orderId);
    oci_execute($selectProductIdsStmt);

    $productIds = [];
    while ($row = oci_fetch_assoc($selectProductIdsStmt)) {
        $productIds[] = $row['PRODUCT_ID'];
    }
    oci_free_statement($selectProductIdsStmt);

    // Iterate over each product ID to check and insert the review if it does not already exist
    foreach ($productIds as $productId) {
        // Check if the combination of PRODUCT_ID and CUSTOMER_ID already exists in the REVIEW table
        $checkExistingSql = "SELECT 1 FROM REVIEW WHERE PRODUCT_ID = :productId AND CUSTOMER_ID = :customerId";
        $checkExistingStmt = oci_parse($conn, $checkExistingSql);
        oci_bind_by_name($checkExistingStmt, ':productId', $productId);
        oci_bind_by_name($checkExistingStmt, ':customerId', $customer_id);
        oci_execute($checkExistingStmt);
        $existingRow = oci_fetch_assoc($checkExistingStmt);

        // If the combination does not exist, insert the new review
        if (!$existingRow) {
            // Insert into the REVIEW table
            $insertReviewSql = "INSERT INTO REVIEW (PRODUCT_ID, USER_ID, REVIEW_PROCIDED, ORDER_ID, CUSTOMER_ID) 
                                VALUES (:productId, :userId, :reviewProvided, :orderId, :customerId)";
            $insertReviewStmt = oci_parse($conn, $insertReviewSql);
            oci_bind_by_name($insertReviewStmt, ':productId', $productId);
            oci_bind_by_name($insertReviewStmt, ':userId', $userId);
            oci_bind_by_name($insertReviewStmt, ':reviewProvided', $reviewProvided);
            oci_bind_by_name($insertReviewStmt, ':orderId', $orderId);
            oci_bind_by_name($insertReviewStmt, ':customerId', $customer_id);
            $insertReviewSuccess = oci_execute($insertReviewStmt);

            if (!$insertReviewSuccess) {
                $error = oci_error($insertReviewStmt);
                echo "Failed to insert review for product ID: $productId. Error: " . $error['message'] . "<br>";
            } else {
                echo "Review inserted successfully for product ID: $productId<br>";
            }

            oci_free_statement($insertReviewStmt);
        } else {
            echo "Review for product ID $productId by the customer already exists.<br>";
        }

        oci_free_statement($checkExistingStmt);
    }

    // Send the order confirmation email
    require_once("PHPMailer-master/customer_email_invoice.php");
    sendOrderConfirmationEmail($email, $orderId, $total_price, $no_product, $pickup_date, $time, $location);

    //send the invoice through email
    require_once("PHPMailer-master/invoice_email.php");
    sendInvoiceEmail($orderId,$userId);

    // Close the connection
    oci_close($conn);

    header('Location: order_confirmation.php?order_id=' . $orderId);
    exit;
} else {
    $error = oci_error($insertPaymentStmt);
    echo "Failed to insert payment details. Error: " . $error['message'];
}
?>
