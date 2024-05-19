<?php

include("../connection/connection.php");


$user_id = $_GET["id"];


// Prepare the SQL statement to fetch data from multiple tables
$sql = "SELECT U.FIRST_NAME, U.LAST_NAME, U.USER_EMAIL, U.USER_ADDRESS, U.USER_CONTACT_NO, U.USER_PROFILE_PICTURE, 
               S.SHOP_NAME, S.SHOP_DESCRIPTION, S.SHOP_CATEGORY_ID, S.REGISTRATION_NO, 
               T.TRADER_ID
        FROM HUDDER_USER U
        JOIN TRADER T ON U.USER_ID = T.USER_ID
        JOIN SHOP S ON U.USER_ID = S.USER_ID
        WHERE U.USER_TYPE = 'trader' AND U.USER_ID = :user_id";

// Prepare the statement
$stmt = oci_parse($conn, $sql);

// Bind the parameter
oci_bind_by_name($stmt, ':user_id', $user_id);

// Execute the statement
oci_execute($stmt);

// Initialize an empty array to store the fetched data
$data = [];

// Fetch the result and store it in the array
while ($row = oci_fetch_assoc($stmt)) {
    $data[] = $row;
}

// Free the statement
oci_free_statement($stmt);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trader Profile</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="admin_navbar.css">
    <link rel="stylesheet" href="admin_profile.css">
    <!-- Add other CSS links as needed -->
</head>
<body>
    <?php
        include("admin_navbar.php");
    ?>
    <div class="container-heading">
        <h2 class="container-heading">Trader Profile Details</h2>
    </div>
    <div id="profileDetailsContainer" class="profile-details-container">
        <div class="left-div">
        <?php foreach ($data as $row): ?>
            <img src="../profile_image/<?php echo $row['USER_PROFILE_PICTURE']; ?>" alt="Profile Picture" class="profile-picture">
            <?php endforeach; ?>
        </div>
        <div class="right-div">
            <form id="profileDetailsForm" class="profile-details-form">
                <?php foreach ($data as $row): ?>
                <div class="form-row">
                    <label for="firstName" class="form-label">First Name:</label>
                    <input type="text" id="firstName" name="firstName" class="form-input" placeholder="Enter first name" value="<?php echo $row['FIRST_NAME']; ?>" readonly>
                </div>
                <div class="form-row">
                    <label for="lastName" class="form-label">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" class="form-input" placeholder="Enter last name" value="<?php echo $row['LAST_NAME']; ?>" readonly>
                </div>
                <div class="form-row">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Enter email" value="<?php echo $row['USER_EMAIL']; ?>" readonly>
                </div>
                <div class="form-row">
                    <label for="address" class="form-label">Address:</label>
                    <textarea id="address" name="address" class="form-textarea" rows="4" placeholder="Enter address" readonly><?php echo $row['USER_ADDRESS']; ?></textarea>
                </div>
                <div class="form-row">
                    <label for="contact" class="form-label">Contact:</label>
                    <input type="tel" id="contact" name="contact" class="form-input" placeholder="Enter contact number" value="<?php echo $row['USER_CONTACT_NO']; ?>" readonly>
                </div>
                <div class="form-row">
                    <label for="shopName" class="form-label">Shop Name:</label>
                    <input type="text" id="shopName" name="shopName" class="form-input" placeholder="Enter shop name" value="<?php echo $row['SHOP_NAME']; ?>" readonly>
                </div>
                <div class="form-row">
                    <label for="shopDescription" class="form-label">Shop Description:</label>
                    <textarea id="shopDescription" name="shopDescription" class="form-textarea" rows="4" placeholder="Enter shop description" readonly><?php echo $row['SHOP_DESCRIPTION']; ?></textarea>
                </div>
                <div class="form-row">
                    <label for="registrationNo" class="form-label">Registration No:</label>
                    <input type="text" id="registrationNo" name="registrationNo" class="form-input" placeholder="Enter registration number" value="<?php echo $row['REGISTRATION_NO']; ?>" readonly>
                </div>
                <div class="form-row">
                    <label for="shopCategoryId" class="form-label">Shop Category:</label>
                    <input type="text" id="shopCategoryId" name="shopCategoryId" class="form-input" placeholder="Enter shop category" value="<?php echo $row['SHOP_CATEGORY_ID']; ?>" readonly>
                </div>
                <div class="form-row">
                    <label for="traderId" class="form-label">Trader ID:</label>
                    <input type="text" id="traderId" name="traderId" class="form-input" placeholder="Enter trader ID" value="<?php echo $row['TRADER_ID']; ?>" readonly>
                </div>
                <?php endforeach; ?>
                <div class="form-row">
                    <button id="cancelBtn" class="cancel-btn" onclick="window.location.href='admin_trader.php'; return false;">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <script src="admin_navbar.js"></script>
</body>
</html>
