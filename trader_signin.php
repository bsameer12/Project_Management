<?php
if(isset($_POST["submit"]))
{
    header("Location:email_verify.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trader Sign In</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="customer_signin.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php
        include("without_session_navbar.php");
    ?>
    <div class="sign-in-container">
    <h2>Trader Sign In</h2>
    <form method="POST">
    <div class="form-group">
        <label for="email">Email or Username</label>
        <input type="text" id="email" name="email" placeholder="Enter your username" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
    </div>
    <div class="form-group">
        <label for="remember"><input type="checkbox" id="remember" name="remember" alt="Remember Me">Remember Me</label>
    </div>
    <div class="form-group">
        <input type="submit" value="Sign In" name="submit" id="submit">
    </div>
    </form>
    <div class="action-links">
    <a href="trader_forgot_password.php" class="forgot-password">Forgot Password?</a>
    <p>Wanna Be A Trader At HudderFoods? <a href="trader_signup.php" class="sign-up-link">Sign Up</a></p>
    <p>Are You A Customer? <a href="customer_signin.php" class="sign-up-link">Customer Sign In</a></p>
    </div>
</div>

    <?php
        include("footer.php");
    ?>

<script src="without_session_navbar.js"></script>
</body>
</html>