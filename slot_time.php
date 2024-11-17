<?php
include("session/session.php");
$total_price = $_GET["total_price"];
$total_products = $_GET["nuber_product"];
$order_id = $_GET["order_id"];
$customer_id = $_GET["customerid"];
$cart_id = $_GET["cartid"];
$discount_amount = isset($_GET["discount"]) ? $_GET["discount"] : 0;
$selectedTime = isset($_POST['time']) ? $_POST['time'] : '';
$selectedDay = isset($_POST['day']) ? $_POST['day'] : '';
$selectedLocation = isset($_POST['location']) ? $_POST['location'] : '';

if (isset($_POST['submit'])) {
    // Process the form data
    $selectedDay = $_POST['day'];
    $selectedTime = $_POST['time'];
    $selectedLocation = $_POST['location'];

    // Ensure selectedDay splits into exactly two parts
    $dayParts = explode(', ', $selectedDay);
    if (count($dayParts) == 2) {
        $selectedDayName = $dayParts[0];
        $selectedDate = $dayParts[1];
    } else {
        // Default values or error handling
        $selectedDayName = '';
        $selectedDate = '';
    }

    $oracleFormattedDate = date('Y-m-d', strtotime($selectedDate));

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
        oci_bind_by_name($updateSlotStmt, ':selectedDate', $oracleFormattedDate);
        oci_bind_by_name($updateSlotStmt, ':selectedTime', $selectedTime);
        oci_bind_by_name($updateSlotStmt, ':selectedDayName', $selectedDayName);
        oci_bind_by_name($updateSlotStmt, ':selectedLocation', $selectedLocation);
        oci_bind_by_name($updateSlotStmt, ':slot_id', $slot_id);

        // Execute the SQL statement
        $updateSuccess = oci_execute($updateSlotStmt);

        if (!$updateSuccess) {
            $e = oci_error($updateSlotStmt);
            echo "Failed to update the COLLECTION_SLOT table: " . htmlentities($e['message']);
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
        oci_bind_by_name($insertSlotStmt, ':selectedDate', $oracleFormattedDate);
        oci_bind_by_name($insertSlotStmt, ':selectedTime', $selectedTime);
        oci_bind_by_name($insertSlotStmt, ':selectedDayName', $selectedDayName);
        oci_bind_by_name($insertSlotStmt, ':order_id', $order_id);
        oci_bind_by_name($insertSlotStmt, ':selectedLocation', $selectedLocation);
        oci_bind_by_name($insertSlotStmt, ':slot_id', $slot_id, -1, OCI_B_INT);

        // Execute the SQL statement
        $success = oci_execute($insertSlotStmt);

        if (!$success) {
            $e = oci_error($insertSlotStmt);
            echo "Failed to insert into COLLECTION_SLOT table: " . htmlentities($e['message']);
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
            $e = oci_error($updateStmt);
            echo "Failed to update SLOT_ID in ORDER_PRODUCT table: " . htmlentities($e['message']);
        }

        // Free statement resources
        oci_free_statement($updateStmt);
    }

    // Free statement resources
    oci_free_statement($checkSlotStmt);

    // Close the connection
    oci_close($conn);
}

function getUpcomingAvailability() {
    $days = ["wednesday", "thursday", "friday"];
    $timeSlots = ["10:00-13:00", "13:00-16:00", "16:00-19:00"];
    $currentTime = time();
    $futureTime = $currentTime + (24 * 60 * 60); // Add 24 hours

    $availability = [];
    for ($i = 0; $i < 3; $i++) {
        $targetDay = $days[$i];
        $nextTargetDate = strtotime("next " . $targetDay, $futureTime);

        if (date("H", $nextTargetDate) < 16) {
            $date = date("Y-m-d", $nextTargetDate);
            $availability[] = [
                'day_name' => date('l', $nextTargetDate),
                'date' => $date,
                'time_slot' => $timeSlots,
            ];
        } else {
            $futureTime = strtotime("next " . $targetDay, $nextTargetDate);
        }
    }
    return $availability;
}

// Get upcoming availability
$availability = getUpcomingAvailability();
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
    <?php include("session_navbar.php"); ?>
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
                    <span class="detail-value" id="discount"><?php echo $discount_amount; ?></span>
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
                <select id="day" name="day" onchange="updateTimeSlots()">
                    <?php if (!empty($availability)) : ?>
                        <?php foreach ($availability as $index => $availableItem) : ?>
                            <option value="<?php echo $availableItem['day_name'] . ', ' . $availableItem['date']; ?>"
                                <?php echo ($selectedDay == $availableItem['day_name'] . ', ' . $availableItem['date'] || ($selectedDay === '' && $index === 0)) ? 'selected' : ''; ?>>
                                <?php echo $availableItem['day_name'] . ', ' . $availableItem['date']; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <div id="time-slots">
                    <?php
                    $firstAvailableDay = $selectedDay ? $selectedDay : $availability[0]['day_name'] . ', ' . $availability[0]['date'];
                    foreach ($availability as $availableItem) {
                        if ($availableItem['day_name'] . ', ' . $availableItem['date'] === $firstAvailableDay) {
                            foreach ($availableItem['time_slot'] as $slot) {
                                $slotLabel = $slot;
                                $radioId = 'time-' . str_replace([':', '-'], '', $slotLabel);
                                $checked = ($slotLabel == $selectedTime) ? 'checked' : '';
                                echo "<label for='$radioId'><input type='radio' id='$radioId' name='time' value='$slotLabel' $checked required>$slotLabel</label><br>";
                            }
                            break;
                        }
                    }
                    ?>
                </div>
                <br>
                <label for="location">Pick a Location:</label>
                <select id="location" name="location">
                    <option value="Location 1" <?php if(isset($selectedLocation) && $selectedLocation == "Location 1") { echo "selected"; } ?>>Location 1</option>
                    <option value="Location 2" <?php if(isset($selectedLocation) && $selectedLocation == "Location 2") { echo "selected"; } ?>>Location 2</option>
                    <option value="Location 3" <?php if(isset($selectedLocation) && $selectedLocation == "Location 3") { echo "selected"; } ?>>Location 3</option>
                </select>
                <button type="submit" name="submit">Submit</button>
            </form>

            <?php if(isset($_POST['submit'])): ?>
                <form class="bottom-form" id="payment-form">
                    <h3>Select Payment Option:</h3>
                    <input type='radio' id='paypal' name='payment' value='PayPal' required checked>
                    <label for='paypal'>PayPal</label><br>
                    <button type='button' id='paypal-button'>Proceed to PayPal</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <?php include("footer.php"); ?>
    <script src="slot_time.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="without_session_navbar.js"></script>
    <script>
        function updateTimeSlots() {
            var daySelect = document.getElementById('day');
            var selectedValue = daySelect.value;
            var selectedDate = selectedValue.split(', ')[1];
            var timeSlotsContainer = document.getElementById('time-slots');
            timeSlotsContainer.innerHTML = '';

            var availability = <?php echo json_encode($availability); ?>;
            var selectedTime = "<?php echo $selectedTime; ?>";
            for (var i = 0; i < availability.length; i++) {
                if (availability[i].date === selectedDate) {
                    var timeSlots = availability[i].time_slot;
                    for (var j = 0; j < timeSlots.length; j++) {
                        var slot = timeSlots[j];
                        var slotLabel = slot;
                        var radioId = 'time-' + slot.replace(/[:\-]/g, '');
                        var checked = (slotLabel === selectedTime) ? 'checked' : '';
                        var label = document.createElement('label');
                        var radio = document.createElement('input');
                        radio.type = 'radio';
                        radio.id = radioId;
                        radio.name = 'time';
                        radio.value = slotLabel;
                        radio.checked = checked;
                        label.htmlFor = radioId;
                        label.appendChild(radio);
                        label.appendChild(document.createTextNode(slotLabel));
                        timeSlotsContainer.appendChild(label);
                        timeSlotsContainer.appendChild(document.createElement('br'));
                    }
                    break;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateTimeSlots();

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
