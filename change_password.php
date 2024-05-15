<?php
 include("session/session.php");
 include("connection/connection.php");
 $user_id = $_SESSION["userid"];
 $input_validation_passed = true;
 $current_password_error ="";
if(isset($_POST["change"])){
    // Input Sanizatization 
    require("input_validation\input_sanitization.php");
    $password = sanitizePassword($_POST["current-password"]);
    $new_password = sanitizePassword($_POST["new-password"]);
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
        $reenter_password_error = "New Password and Confirm new password Didn't matched";
        $input_validation_passed = false;
    }
    
    if($input_validation_passed){
        // Prepare the SQL statement
        $sql_select_password = "SELECT USER_PASSWORD FROM HUDDER_USER WHERE USER_ID = :user_id";

        // Prepare the OCI statement
        $stmt_select_password = oci_parse($conn, $sql_select_password);

        // Bind the user_id parameter
        oci_bind_by_name($stmt_select_password, ':user_id', $user_id);

        // Execute the statement
        if (oci_execute($stmt_select_password)) {
            // Fetch the result
            if ($row = oci_fetch_assoc($stmt_select_password)) {
                $user_password = $row['USER_PASSWORD'];
               if($user_password === $password){
                     // Prepare the SQL statement for updating the password
                        $sql_update_password = "UPDATE HUDDER_USER 
                        SET user_password = :user_password 
                        WHERE user_id = :user_id";

                        // Prepare the OCI statement
                        $stmt_update_password = oci_parse($conn, $sql_update_password);

                        // Bind parameters
                        oci_bind_by_name($stmt_update_password, ':user_password', $new_password);
                        oci_bind_by_name($stmt_update_password, ':user_id', $user_id);

                        // Execute the SQL statement
                        if (!oci_execute($stmt_update_password)) {
                        die("Error updating password: " . oci_error()['message']);
                        } else {
                            // Prepare the SQL statement for updating the DATE_UPDATED column
                            $sql_update_date = "UPDATE CUSTOMER 
                            SET DATE_UPDATED = CURRENT_DATE
                            WHERE USER_ID = :user_id";

                            // Prepare the OCI statement
                            $stmt_update_date = oci_parse($conn, $sql_update_date);

                            // Bind the user_id parameter
                            oci_bind_by_name($stmt_update_date, ':user_id', $user_id);

                            // Execute the SQL statement
                            if (oci_execute($stmt_update_date)) {
                            // Reload the page
                            header("Location: customer.php");
                            exit(); // Ensure script stops execution after redirection
                            } else {
                            $error = oci_error($stmt_update_date);
                            echo "Error updating DATE_UPDATED column: " . $error['message'];
                            }
                        
                        }
                        // Free the statement and close the connection
                        oci_free_statement($stmt);
                        }
                        else{
                            $current_password_error = "Your Current Password is incorrect!!";
                        }
            } 
        } else {
            $error = oci_error($stmt_select_password);
            echo "Error executing SQL statement: " . $error['message'];
        }

        // Free the statement
        oci_free_statement($stmt_select_password);
        oci_close($conn);

         }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password Password</title>
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
        include("session_navbar.php");
    ?>
    <div class="password-reset-container">
        <h2>Change Your Password</h2>
        <p>Change Your Password. Enter a new password. Your new password should contain:</p>
        <ul>
            <li>Minimum of 8 characters</li>
            <li>At least one capital letter</li>
            <li>At least one number</li>
            <li>At least one special character</li>
        </ul>
        <form method="post" action="" name="reset-password" id="reset-password" enctype="multipart/form-data">
        <div class="form-group">
                <label for="current-password">Current Password</label>
                <input type="password" id="current-password" name="current-password" required>
                <?php
                if (!empty($current_password_error)) {
                    echo "<p style='color: red;'>$current_password_error</p>";
                }
                ?>
            </div>
            <div class="form-group">
                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new-password" required>
                <?php
            if (!empty($password_error)) {
                    echo "<p style='color: red;'>$password_error</p>";
                }
                ?>
            </div>
            <div class="form-group">
                <label for="confirm-password">Re-enter Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
                <?php
                if (!empty($reenter_password_error)) {
                    echo "<p style='color: red;'>$reenter_password_error</p>";
                }
                ?>
            </div>
            <div class="form-group">
                <input type="submit" value="Change Password" name="change" id="change">
            </div>
        </form>
    </div>
    <?php
        include("footer.php");
    ?>
    <script src="without_session_navbar.js"></script>
</body>
</html>