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
    ?>
    <div class="pickup-slot-container">
    <h2>Select a Pickup Slot</h2>
    <div class="pickup-slot-buttons">
        <button class="pickup-slot-button active" id="slot-button-1" data-date="2024-03-03">Wed 03-03-2024</button>
        <button class="pickup-slot-button" id="slot-button-2" data-date="2024-03-04">Thu 03-04-2024</button>
        <button class="pickup-slot-button" id="slot-button-3" data-date="2024-03-06">Sun 03-06-2024</button>
    </div>
    <div id="slot-content-2024-03-03" class="slot-content active">
        <label><input type="radio" name="slot-option-2024-03-03" value="10-1"> 10:00 PM - 1:00 PM</label>
        <label><input type="radio" name="slot-option-2024-03-03" value="1-4"> 1:00 PM - 4:00 PM</label>
        <label><input type="radio" name="slot-option-2024-03-03" value="4-8"> 4:00 PM - 8:00 PM</label>
    </div>
    <div id="slot-content-2024-03-04" class="slot-content">
        <label><input type="radio"  name="slot-option-2024-03-04" value="10-1"> 1:00 AM - 1:00 PM</label>
        <label><input type="radio" name="slot-option-2024-03-04" value="1-4"> 2:00 PM - 2:00 PM</label>
        <label><input type="radio" name="slot-option-2024-03-04" value="4-8"> 3:00 PM - 3:00 PM</label>
    </div>
    <div id="slot-content-2024-03-06" class="slot-content">
        <label><input type="radio" name="slot-option-2024-03-06" value="10-1"> 10:00 AM - 1:00 AM</label>
        <label><input type="radio" name="slot-option-2024-03-06"  value="1-4"> 1:00 AM - 4:00 AM</label>
        <label><input type="radio" name="slot-option-2024-03-06"value="4-8"> 4:00 AM - 8:00 AM</label>
    </div>
    <div class="confirm-purchase">
    <button class="confirm-button">Confirm Purchase</button>
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

