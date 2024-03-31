<?php
if(isset($_POST["submit"]))
{
    header("Location:reset_password.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="email_verfiy.css">
    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php
        include("without_session_navbar.php");
    ?>
    <div class="email-container">
        <h2>Verify Your Email</h2>
        <p>A verification code has been sent to your email <strong>youremail@example.com</strong>.</p>
        <form method="post">
            <label for="verification_code">Verification Code</label><br>
            <input type="text" id="verification_code" name="verification_code" required><br>
            <input type="submit" value="Verify" name="submit" id="submit">
        </form>
    </div>
    <?php
        include("footer.php");
    ?>
    <script src="without_session_navbar.js"></script>
</body>
</html>