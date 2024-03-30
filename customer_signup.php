<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Sign Up</title>
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="customer_signup.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php
        include("without_session_navbar.php");
    ?>
    <div class="sign-up-container">
    <h2>Customer Sign Up</h2>
    <form>
        <div class="form-group">
            <label for="first-name">First Name</label>
            <input type="text" id="first-name" name="first-name" placeholder="Enter your first name" required>
        </div>
        <div class="form-group">
            <label for="last-name">Last Name</label>
            <input type="text" id="last-name" name="last-name" placeholder="Enter your last name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <div class="form-group">
            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" required>
        </div>
        <div class="form-group">
            <label>Gender</label><br>
            <label for="male" style="display: inline-block; margin-right: 10px; "> <input type="radio" id="male" name="gender" value="male"> Male</label>
            <label for="female" style="display: inline-block; margin-right: 10px; "><input type="radio" id="female" name="gender" value="female"> Female</label>
            <label for="other" style="display: inline-block; margin-right: 10px; "><input type="radio" id="other" name="gender" value="other"> Other</label>
        </div>
        <div class="form-group">
            <label for="contact">Contact Number</label>
            <input type="tel" id="contact" name="contact" placeholder="Enter your contact number" required>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" placeholder="Enter your address" required></textarea>
        </div>
        <div class="form-group">
            <label for="profile-pic">Profile Picture</label>
            <input type="file" id="profile-pic" name="profile-pic" accept="image/*">
        </div>
        <div class="form-group">
            <label for="terms"> <input type="checkbox" id="terms" name="terms" required> I agree to the Terms and Conditions</label>
        </div>
        <div class="form-group">
            <input type="submit" value="Sign Up">
        </div>
    </form>
    <div class="action-links">
        <p>Already have an account? <a href="customer_signin.php" class="sign-in-link">Sign In</a></p>
    </div>
</div>
<?php
        include("footer.php");
    ?>

<script src="without_session_navbar.js"></script>
</body>
</html>