<?php
$total_price = $_GET["total_price"];
$total_products = $_GET["nuber_product"];
$order_id = $_GET["order_id"];
$customer_id = $_GET["customerid"];
$cart_id = $_GET["cartid"];

if (isset($_POST['submit'])) {
    // Process the form data
    $selectedDay = $_POST['day'];
    $selectedTime = $_POST['time'];
    $selectedLocation = $_POST['location'];

    // Split the selected value into date and day
    list($selectedDayName, $selectedDate) = explode(', ', $selectedDay);

    // Include the database connection
    include("connection/connection.php");

    // Check if a slot is already allocated for the given order_id
    $checkSlotSql = "SELECT SLOT_ID FROM COLLECTION_SLOT WHERE ORDER_PRODUCT_ID = :order_id";
    $checkSlotStmt = oci_parse($conn, $checkSlotSql);
    oci_bind_by_name($checkSlotStmt, ':order_id', $order_id);
    oci_execute($checkSlotStmt);
    $slotRow = oci_fetch_assoc($checkSlotStmt);
    
    if ($slotRow) {
        // Slot already allocated, store the SLOT_ID in a variable
        $slot_id = $slotRow['SLOT_ID'];
        
        // Update the existing slot
        $updateSlotSql = "UPDATE COLLECTION_SLOT SET SLOT_DATE = TO_DATE(:selectedDate, 'YYYY-MM-DD'), SLOT_TIME = :selectedTime, SLOT_DAY = :selectedDayName, LOCATION = :selectedLocation WHERE SLOT_ID = :slot_id";
        $updateSlotStmt = oci_parse($conn, $updateSlotSql);
        
        // Bind the parameters
        oci_bind_by_name($updateSlotStmt, ':selectedDate', $selectedDate);
        oci_bind_by_name($updateSlotStmt, ':selectedTime', $selectedTime);
        oci_bind_by_name($updateSlotStmt, ':selectedDayName', $selectedDayName);
        oci_bind_by_name($updateSlotStmt, ':selectedLocation', $selectedLocation);
        oci_bind_by_name($updateSlotStmt, ':slot_id', $slot_id);
        
        // Execute the SQL statement
        $updateSuccess = oci_execute($updateSlotStmt);

        if (!$updateSuccess) {
            echo "Failed to update the COLLECTION_SLOT table.";
        }

        // Free statement resources
        oci_free_statement($updateSlotStmt);
    } else {
        // No slot found, insert a new slot
        $insertSlotSql = "INSERT INTO COLLECTION_SLOT (SLOT_DATE, SLOT_TIME, SLOT_DAY, ORDER_PRODUCT_ID, LOCATION)
                          VALUES (TO_DATE(:selectedDate, 'YYYY-MM-DD'), :selectedTime, :selectedDayName, :order_id, :selectedLocation)
                          RETURNING SLOT_ID INTO :slot_id";
        $insertSlotStmt = oci_parse($conn, $insertSlotSql);

        // Bind the parameters
        oci_bind_by_name($insertSlotStmt, ':selectedDate', $selectedDate);
        oci_bind_by_name($insertSlotStmt, ':selectedTime', $selectedTime);
        oci_bind_by_name($insertSlotStmt, ':selectedDayName', $selectedDayName);
        oci_bind_by_name($insertSlotStmt, ':order_id', $order_id);
        oci_bind_by_name($insertSlotStmt, ':selectedLocation', $selectedLocation);
        oci_bind_by_name($insertSlotStmt, ':slot_id', $slot_id, -1, OCI_B_INT);

        // Execute the SQL statement
        $success = oci_execute($insertSlotStmt);

        if (!$success) {
            echo "Failed to insert into COLLECTION_SLOT table.";
        }

        // Free statement resources
        oci_free_statement($insertSlotStmt);
    }

    if (isset($slot_id)) {
        // Proceed with updating ORDER_PRODUCT with SLOT_ID
        $updateSql = "UPDATE ORDER_PRODUCT SET SLOT_ID = :slot_id WHERE ORDER_PRODUCT_ID = :order_id";
        $updateStmt = oci_parse($conn, $updateSql);

        // Bind the parameters
        oci_bind_by_name($updateStmt, ':slot_id', $slot_id);
        oci_bind_by_name($updateStmt, ':order_id', $order_id);

        // Execute the update SQL statement
        $updateSuccess = oci_execute($updateStmt);

        if (!$updateSuccess) {
            echo "Failed to update SLOT_ID in ORDER_PRODUCT table.";
        }

        // Free statement resources
        oci_free_statement($updateStmt);
    }

    // Free statement resources
    oci_free_statement($checkSlotStmt);

    // Close the connection
    oci_close($conn);
}
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
    <link rel="stylesheet" href="slot_time.css">

    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php
        include("without_session_navbar.php");

        // Get current date and time
        $currentDateTime = new DateTime();
        $currentDateTime->modify('+24 hours'); // Add 24 hours to the current date and time

        // Generate options for the next available dates
        $dates = array();
        while (count($dates) < 3) {
            if (in_array($currentDateTime->format('l'), array('Monday', 'Wednesday', 'Friday'))) {
                $dates[] = $currentDateTime->format('Y-m-d'); // Format: YYYY-MM-DD
            }
            $currentDateTime->modify('+1 day'); // Move to the next day
        }

        // Generate time slots
        $timeSlots = array(
            array('10:00', '13:00'),
            array('13:00', '16:00'),
            array('16:00', '19:00')
        );
    ?>
    <div class="container1">
        <div class="left-container">
            <h2 id="order-summary-title">Order Summary</h2>
            <div class="order-details">
                <div class="detail">
                    <span class="detail-title">Total:</span>
                    <span class="detail-value" id="total"><?php echo $total_price; ?></span>
                </div>
                <div class="detail">
                    <span class="detail-title">Net Total:</span>
                    <span class="detail-value" id="net-total"><?php echo $total_price; ?></span>
                </div>
                <div class="detail">
                    <span class="detail-title">Discount Amount:</span>
                    <span class="detail-value" id="discount">0</span>
                </div>
                <div class="detail">
                    <span class="detail-title">Number of Items:</span>
                    <span class="detail-value" id="item-count"><?php echo $total_products; ?></span>
                </div>
            </div>
        </div>
        
        <div class="right-container">
            <form class="top-form" id="pickup-form" name="pickup-form" method="POST" action="">
                <h3>Select Pick Up Time and Date:</h3>
                <label for="day">Pick a Day:</label>
                <select id="day" name="day">
                    <?php
                         foreach ($dates as $date) {
                            $day = date('l', strtotime($date)); // Get the day
                            echo '<option value="' . $day . ', ' . $date . '">' . $day . ', ' . $date . '</option>';
                        }
                    ?>
                </select>
                <label>Time Slots:</label><br>
                <?php
                    foreach ($timeSlots as $slot) {
                        $start = new DateTime($slot[0]);
                        $end = new DateTime($slot[1]);
                        $slotLabel = $start->format('H:i') . ' - ' . $end->format('H:i');
                        $radioId = 'time-' . str_replace(':', '-', $slotLabel); // Unique ID for each radio button
                        echo '<label for="' . $radioId . '"><input type="radio" id="' . $radioId . '" name="time" value="' . $slotLabel . '">  ' . $slotLabel . '</label><br>';
                    }
                ?>
                <br>
                <label for="location">Pick a Location:</label>
                <select id="location" name="location">
                    <option value="Location 1">Location 1</option>
                    <option value="Location 2">Location 2</option>
                    <option value="Location 3">Location 3</option>
                </select>
                <button type="submit" name="submit">Submit</button>
            </form>
            <?php
            if(isset($_POST['submit'])){
                echo "<form class='bottom-form' id='payment-form'>";
                echo "<h3>Select Payment Option:</h3>";
                echo "<input type='radio' id='paypal' name='payment' value='PayPal'>";
                echo "<label for='paypal'>PayPal</label><br>";
                echo "<button type='button' id='paypal-button'>Proceed to PayPal</button>";
                echo "</form>";
            }?>
        </div>
    </div>
<?php
        include("footer.php");
    ?>
    <script src="slot_time.js"> </script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="without_session_navbar.js"> </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    var paypalButton = document.getElementById('paypal-button');
    if (paypalButton) {
        paypalButton.addEventListener('click', function() {
            var selectedPaymentMethod = document.querySelector('input[name="payment"]:checked');
            if (selectedPaymentMethod) {
                var paymentMethod = selectedPaymentMethod.value;
                var totalPrice = "<?php echo $total_price; ?>";
                var totalProducts = "<?php echo $total_products; ?>";
                var orderId = "<?php echo $order_id; ?>";
                var customerId = "<?php echo $customer_id; ?>";
                window.location.href = 'payment.php?method=' + encodeURIComponent(paymentMethod) + 
                    '&total_price=' + encodeURIComponent(totalPrice) + 
                    '&total_products=' + encodeURIComponent(totalProducts) +
                    '&order_id=' + encodeURIComponent(orderId) +
                    '&customer_id=' + encodeURIComponent(customerId);
            } else {
                alert('Please select a payment method.');
            }
        });
    }
});
</script>

</body>
</html>
