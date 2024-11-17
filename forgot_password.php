<?php
if(isset($_POST["forgot"]))
{
    // Input Sanizatization 
    require("input_validation\input_sanitization.php");
    $email = sanitizeEmail($_POST["verification_email"]);

    // Input Validation
    require("input_validation\input_validation.php");
    $email_error = "";
    // Check if email exists
        if (emailExists($email) === "true") {
            include("connection/connection.php");
            // Prepare the SQL statement
            $sql = "SELECT user_id, first_name, last_name FROM HUDDER_USER WHERE user_email = :email";

            // Prepare the OCI statement
            $stmt = oci_parse($conn, $sql);

            // Bind the email parameter
            oci_bind_by_name($stmt, ':email', $email);

            // Execute the statement
            oci_execute($stmt);

            // Fetch the result
            if ($row = oci_fetch_assoc($stmt)) {
                $name = $row["FIRST_NAME"] . " " . $row["LAST_NAME"];
                $user_id = $row['USER_ID'];
                require("otp\otp_genearator.php");
                $verification_code = generateRandomCode();
                // Prepare the SQL statement
                    $sql = "UPDATE CUSTOMER 
                    SET VERIFICATION_CODE = :verification_code,
                        DATE_UPDATED = CURRENT_DATE
                    WHERE USER_ID = :userid";

                    // Prepare the OCI statement
                    $stmt = oci_parse($conn, $sql);
                    // Bind the parameters
                    oci_bind_by_name($stmt, ':verification_code', $verification_code);
                    oci_bind_by_name($stmt, ':userid', $user_id);

                    // Execute the statement
                    if (oci_execute($stmt)) {
                        require("PHPMailer-master/forgot_password_email.php");
                        sendForgotPasswordVerificationEmail($email, $verification_code, $name);
                        header("Location:password_reset_email.php?email=$email&userid=$user_id");
                        exit;
                    } else {
                    $error = oci_error($stmt);
                    echo "Error updating row: " . $error['message'];
                    }
                     // Free the statement and close the connection
                        oci_free_statement($stmt);
                        oci_close($conn);
                                } 
        } else {
            $email_error = "This Email is not regristered in our platform.";
        }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Your Password</title>
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
        <h2>Forgot Your Password?</h2>
        <p>Please enter the email associated with your account. We'll send you a verification code to reset your password</p>
        <form action="" method="post" name="email_verify" id="email_verify" enctype="multipart/form-data">
            <label for="verification_email">Email</label><br>
            <input type="email" id="verification_email" name="verification_email" required><br>
            <?php
            if (!empty($email_error)) {
                    echo "<p style='color: red;'>$email_error</p>";
                }
                ?>
            <input type="submit" value="Verify" name="forgot" id="forgot">
        </form>
    </div>
    <?php
        include("footer.php");
    ?>
    <script src="without_session_navbar.js"></script>
</body>
</html>