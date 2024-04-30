<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="trader_navbar.css">
    <link rel="stylesheet" href="trader_change_password.css">
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
    <div id="changePasswordContainer" class="change-password-container">
    <h2 class="form-heading"><i class="fas fa-lock"></i> Change Password</h2>
    <form id="changePasswordForm" class="change-password-form">
        <div class="form-row">
            <label for="currentPassword" class="form-label">Current Password:</label>
            <div class="password-input-container">
                <input type="password" id="currentPassword" name="currentPassword" class="form-input" placeholder="Enter current password">
                <span class="password-toggle" onclick="togglePasswordVisibility('currentPassword')">Show</span>
            </div>
        </div>
        <div class="form-row">
            <label for="newPassword" class="form-label">New Password:</label>
            <div class="password-input-container">
                <input type="password" id="newPassword" name="newPassword" class="form-input" placeholder="Enter new password">
                <span class="password-toggle" onclick="togglePasswordVisibility('newPassword')">Show</span>
            </div>
        </div>
        <div class="form-row">
            <label for="confirmPassword" class="form-label">Confirm New Password:</label>
            <div class="password-input-container">
                <input type="password" id="confirmPassword" name="confirmPassword" class="form-input" placeholder="Confirm new password">
                <span class="password-toggle" onclick="togglePasswordVisibility('confirmPassword')">Show</span>
            </div>
        </div>
        <div class="form-row">
            <input type="submit" id="changePasswordBtn" class="submit-btn" value="Change Password">
            <button type="button" id="cancelBtn" class="cancel-btn" onclick="window.location.href='trader_dashboard.php' ; return false;">Cancel</button>
        </div>
    </form>
</div>
<script src="trader_navbar.js"></script>
<script src="trader_change_password.js"></script>
</body>
</html>