<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="admin_navbar.css">
    <link rel="stylesheet" href="admin_profile.css">
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
        include("admin_navbar.php");
    ?>
    <div class="container-heading">
        <h2 class="container-heading">Trader Profile  Details</h2>
        </div>
        <div id="profileDetailsContainer" class="profile-details-container">
    <div class="left-div">
        <img src="../profile.jpg" alt="Profile Picture" class="profile-picture">
    </div>
    <div class="right-div">
        <form id="profileDetailsForm" class="profile-details-form">
            <div class="form-row">
                <label for="firstName" class="form-label">First Name:</label>
                <input type="text" id="firstName" name="firstName" class="form-input" placeholder="Enter first name">
            </div>
            <div class="form-row">
                <label for="lastName" class="form-label">Last Name:</label>
                <input type="text" id="lastName" name="lastName" class="form-input" placeholder="Enter last name">
            </div>
            <div class="form-row">
                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" name="username" class="form-input" placeholder="Enter username">
            </div>
            <div class="form-row">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="Enter email">
            </div>
            <div class="form-row">
                <label for="address" class="form-label">Address:</label>
                <textarea id="address" name="address" class="form-textarea" rows="4" placeholder="Enter address"></textarea>
            </div>
            <div class="form-row">
                <label for="contact" class="form-label">Contact:</label>
                <input type="tel" id="contact" name="contact" class="form-input" placeholder="Enter contact number">
            </div>
            <div class="form-row">
                <label for="profilePicture" class="form-label">Change Profile Picture:</label>
                <input type="file" id="profilePicture" name="profilePicture" class="form-input" accept="image/*">
            </div>
            <div class="form-row">
                <input type="submit" id="saveChangesBtn" class="submit-btn" value="Save Changes">
                <button id="cancelBtn" class="cancel-btn"  onclick="window.location.href='admin_dashboard.php' ; return false;">Cancel</button>
            </div>
        </form>
    </div>
</div>
<script src="admin_navbar.js"></script>
</body>
</html>
