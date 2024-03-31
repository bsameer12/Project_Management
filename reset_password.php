<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="reset_password.css">
    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php
        include("without_session_navbar.php");
    ?>
    <div class="password-reset-container">
        <h2>Set A New Password</h2>
        <p>Reset your password. Enter a new password. Your new password should contain:</p>
        <ul>
            <li>Minimum of 8 characters</li>
            <li>At least one capital letter</li>
            <li>At least one number</li>
            <li>At least one special character</li>
        </ul>
        <form method="post" action="reset_password.php">
            <div class="form-group">
                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new-password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Re-enter Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Reset Password">
            </div>
        </form>
    </div>
    <?php
        include("footer.php");
    ?>
    <script src="without_session_navbar.js"></script>
</body>
</html>