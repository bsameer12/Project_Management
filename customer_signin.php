<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Sign In</title>
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
    <h2>Customer Sign In</h2>
    <form>
    <div class="form-group">
        <label for="email">Email or Phone Number:</label>
        <input type="text" id="email" name="email" placeholder="Enter your email or phone number" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
    </div>
    <div class="form-group">
        <label for="remember"><input type="checkbox" id="remember" name="remember" alt="Remember Me">Remember Me</label>
    </div>
    <div class="form-group">
        <input type="submit" value="Sign In">
    </div>
    </form>
    <div class="action-links">
    <a href="#" class="forgot-password">Forgot Password?</a>
    <p>New to HudderFoods? <a href="customer_signup.php" class="sign-up-link">Sign Up</a></p>
    <p>Are You A Trader? <a href="trader_signin.php" class="sign-up-link">Merchant Sign In</a></p>
    </div>
</div>

    <?php
        include("footer.php");
    ?>

<script src="without_session_navbar.js"></script>
</body>
</html>