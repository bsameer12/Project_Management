<?php
include("connection/connection.php");
$input_validation_passed = true;
$user_id = $_GET["userid"];
if(isset($_POST["reset"])){
    // Input Sanizatization 
    require("input_validation\input_sanitization.php");
    $password = sanitizePassword($_POST["new-password"]);
    $confirm_password  = sanitizePassword($_POST["confirm-password"]);
    // Input Validation
    require("input_validation\input_validation.php");
    // Validate password
    $password_error = "";
    if (validatePassword($_POST["new-password"]) === "false") {
        $password_error = "Password must contain at least six characters including one lowercase letter, one uppercase letter, and one digit.";
        $input_validation_passed = false;
    }

    // Validate confirm password
    $reenter_password_error = "";
    if (validateConfirmPassword($_POST["new-password"], $_POST["confirm-password"]) === "false") {
        $reenter_password_error = "Password Didn't matched";
        $input_validation_passed = false;
    }
    if($input_validation_passed){
        // Prepare the SQL statement for updating the password
            $sql_update_password = "UPDATE HUDDER_USER 
            SET user_password = :user_password 
            WHERE user_id = :user_id";

            // Prepare the OCI statement
            $stmt_update_password = oci_parse($conn, $sql_update_password);

            // Bind parameters
            oci_bind_by_name($stmt_update_password, ':user_password', $password);
            oci_bind_by_name($stmt_update_password, ':user_id', $user_id);

            // Execute the SQL statement
            if (!oci_execute($stmt_update_password)) {
            die("Error updating password: " . oci_error()['message']);
            } else {
                header("Location: index.php");
                            exit; // Ensure script stops execution after redirection
            
            }
            // Free the statement and close the connection
            oci_free_statement($stmt);
            oci_close($conn);

                }
}
?>
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
        <form method="post" action="" method="post" name="reset-password" id="reset-password" enctype="multipart/form-data">
            <div class="form-group">
                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new-password" required pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}" title="Password must be at least 6 characters long and contain at least one lowercase letter, one uppercase letter, and one number">
                <?php
            if (!empty($password_error)) {
                    echo "<p style='color: red;'>$password_error</p>";
                }
                ?>
            </div>
            <div class="form-group">
                <label for="confirm-password">Re-enter Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}" title="Password must be at least 6 characters long and contain at least one lowercase letter, one uppercase letter, and one number">
                <?php
                if (!empty($reenter_password_error)) {
                    echo "<p style='color: red;'>$reenter_password_error</p>";
                }
                ?>
            </div>
            <div class="form-group">
                <input type="submit" value="Reset Password" name="reset" id="reset">
            </div>
        </form>
    </div>
    <?php
        include("footer.php");
    ?>
    <script src="without_session_navbar.js"></script>
</body>
</html>