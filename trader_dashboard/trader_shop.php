<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="trader_navbar.css">
    <link rel="stylesheet" href="trader_shop.css">
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
        <div class="container-heading">
        <h2 class="container-heading">Shop Details</h2>
        </div>
        <div id="shopDetailsContainer" class="shop-details-container">
        <div class="left-div">
            <img src="../chese_image.jpg" alt="Shop Picture" class="shop-picture">
        </div>
        <div class="right-div">
            <form id="shopDetailsForm" class="shop-details-form">
                <div class="form-row">
                    <label for="shopId" class="form-label">Shop ID:</label>
                    <input type="text" id="shopId" name="shopId" class="form-input" placeholder="Enter shop ID">
                </div>
                <div class="form-row">
                    <label for="registrationNo" class="form-label">Registration No:</label>
                    <input type="text" id="registrationNo" name="registrationNo" class="form-input" placeholder="Enter registration number">
                </div>
                <div class="form-row">
                    <label for="shopCategory" class="form-label">Shop Category:</label>
                    <input type="text" id="shopCategory" name="shopCategory" class="form-input" placeholder="Enter shop category">
                </div>
                <div class="form-row">
                    <label for="shopOwner" class="form-label">Shop Owner:</label>
                    <input type="text" id="shopOwner" name="shopOwner" class="form-input" placeholder="Enter shop owner">
                </div>
                <div class="form-row">
                    <label for="shopDescription" class="form-label">Shop Description:</label>
                    <textarea id="shopDescription" name="shopDescription" class="form-textarea" rows="4" placeholder="Enter shop description"></textarea>
                </div>
                <div class="form-row">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Enter email">
                </div>
                <div class="form-row">
                    <label for="contactNumber" class="form-label">Contact Number:</label>
                    <input type="text" id="contactNumber" name="contactNumber" class="form-input" placeholder="Enter contact number">
                </div>
                <div class="form-row">
                    <input type="submit" id="saveChangesBtn" class="submit-btn" value="Save Changes">
                    <button id="cancelBtn" class="cancel-btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>
<script src="trader_navbar.js"></script>
</body>
</html>