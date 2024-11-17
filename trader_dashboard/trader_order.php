<?php
require("trader_session.php");
include("../connection/connection.php"); // Include the database connection

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected order status from the POST data
    $order_status = $_POST['order_status'];
    $order_id = $_POST['order_id'];
    // Prepare the SQL statement to update the ORDER_STATUS
$sql = "UPDATE ORDER_PRODUCT SET ORDER_STATUS = :order_status WHERE ORDER_PRODUCT_ID = :order_id";

// Prepare the statement
$stmt = oci_parse($conn, $sql);
if (!$stmt) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Bind the parameters
oci_bind_by_name($stmt, ':order_status', $order_status);
oci_bind_by_name($stmt, ':order_id', $order_id);

// Execute the statement
$r = oci_execute($stmt);
if (!$r) {
    $e = oci_error($stmt);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
} 

// Free the statement
oci_free_statement($stmt);


    
}

// Get the trader user ID passed by us
$trader_user_id = $_SESSION["userid"];;

$sql = "SELECT 
            o.ORDER_PRODUCT_ID, 
            o.NO_OF_PRODUCT, 
            o.ORDER_STATUS, 
            o.TOTAL_PRICE, 
            o.ORDER_DATE, 
            o.CUSTOMER_ID, 
            c.SLOT_DATE, 
            c.SLOT_TIME, 
            c.SLOT_DAY, 
            c.LOCATION 
        FROM 
            ORDER_PRODUCT o
        JOIN 
            COLLECTION_SLOT c 
        ON 
            o.SLOT_ID = c.SLOT_ID 
        WHERE 
            o.ORDER_PRODUCT_ID IN (SELECT DISTINCT ORDER_PRODUCT_ID FROM ORDER_DETAILs WHERE TRADER_USER_ID = :trader_user_id)
        ORDER BY 
            c.SLOT_DATE DESC";

$stmt = oci_parse($conn, $sql);
if (!$stmt) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Bind the trader user ID parameter
oci_bind_by_name($stmt, ':trader_user_id', $trader_user_id);

$r = oci_execute($stmt);
if (!$r) {
    $e = oci_error($stmt);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Initialize an array to store the order details
$order_details = array();

// Fetch the results and store them in the array
while ($row = oci_fetch_assoc($stmt)) {
    $order_details[] = $row;
}

// Free the statement
oci_free_statement($stmt);

// Close the connection
oci_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
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
    <h1 class="page-title">Orders Details</h1>
    <div class="user-details-container">
        <table border=1 id="myTable">
        <thead>
        <tr> 
                    <th> Order ID </th> 
                    <th> Total Amount </th>
                    <th> Order Date</th>
                    <th> Customer Id </th>
                    <th> Pick Up Date </th>
                    <!-- Add more headers for product details -->
                    <th> Order Status </th>
                    <th> Pick Up Location </th>
                    <th> Customer Time Slot</th>
                    <th> Actions </th> 
        </tr>
        </thead>
        <tbody>
        <?php foreach ($order_details as $order) { ?>
                <tr>
                    <td><?php echo $order['ORDER_PRODUCT_ID']; ?></td>
                    <td><?php echo $order['TOTAL_PRICE']; ?></td>
                    <td><?php echo $order['ORDER_DATE']  ; ?></td>
                    <td><?php echo $order['CUSTOMER_ID']  ?></td>
                    <td><?php echo $order['SLOT_DATE'] . " ," . $order['SLOT_DAY'] ; ?></td>
                    <td>
                    <form id="statusForm" method="post">
                    <!-- Hidden input field to store the order ID -->
                    <input type="hidden" name="order_id" value="<?php echo $order['ORDER_PRODUCT_ID']; ?>">
                    
                        <select name="order_status" onchange="submitForm(this)">
                            <option value="0" <?php echo ($order['ORDER_STATUS'] == 0) ? 'selected' : ''; ?>  readonly>Order Incompleted</option>
                            <option value="1" <?php echo ($order['ORDER_STATUS'] == 1) ? 'selected' : ''; ?> readonly>Payment Complete</option>
                            <option value="2" <?php echo ($order['ORDER_STATUS'] == 2) ? 'selected' : ''; ?>>Order Prepared</option>
                            <option value="3" <?php echo ($order['ORDER_STATUS'] == 3) ? 'selected' : ''; ?>>Order Ready to Pick Up</option>
                            <option value="4" <?php echo ($order['ORDER_STATUS'] == 4) ? 'selected' : ''; ?>>Order Delivered</option>
                        </select>
                    </form>
                </td>

                    <td><?php echo $order['LOCATION']; ?></td>
                    <td><?php echo $order['SLOT_TIME']; ?></td>
                    <td>
                        <a href="trader_view_order.php?id=<?php echo $order['ORDER_PRODUCT_ID']; ?>&action=edit">View</a>
                    </td>
                </tr>
            <?php } ?>
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

        function submitForm(select) {
        // Get the form element containing the select input
        var form = select.form;
        
        // Submit the form
        form.submit();
    }
    </script>
</body>
</html>