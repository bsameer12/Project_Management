<?php
 include("trader_session.php");
 include("../connection/connection.php");
 $user_id = $_SESSION["userid"];
 $input_validation_passed = true;
 $current_password_error ="";
if(isset($_POST["changePasswordBtn"])){
    // Input Sanizatization 
    require("../input_validation/input_sanitization.php");
    $password = sanitizePassword($_POST["currentPassword"]);
    $new_password = sanitizePassword($_POST["newPassword"]);
    $confirm_password  = sanitizePassword($_POST["confirmPassword"]);
    // Input Validation
    require("../input_validation/input_validation.php");
    // Validate password
    $password_error = "";
    if (validatePassword($_POST["newPassword"]) === "false") {
        $password_error = "Password must contain at least six characters including one lowercase letter, one uppercase letter, and one digit.";
        $input_validation_passed = false;
    }

    // Validate confirm password
    $reenter_password_error = "";
    if (validateConfirmPassword($_POST["newPassword"], $_POST["confirmPassword"]) === "false") {
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
                            header("Location:trader_profile.php");
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
    <form id="changePasswordForm" class="change-password-form"  method="post" action="" name="changePasswordForm"  enctype="multipart/form-data">
        <div class="form-row">
            <label for="currentPassword" class="form-label">Current Password:</label>
            <div class="password-input-container">
                <input type="password" id="currentPassword" name="currentPassword" class="form-input" placeholder="Enter current password" required pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}" title="Password must be at least 6 characters long and contain at least one lowercase letter, one uppercase letter, and one number">
                <span class="password-toggle" onclick="togglePasswordVisibility('currentPassword')">Show</span>
                <?php
                if (!empty($current_password_error)) {
                    echo "<p style='color: red;'>$current_password_error</p>";
                }
                ?>
            </div>
        </div>
        <div class="form-row">
            <label for="newPassword" class="form-label">New Password:</label>
            <div class="password-input-container">
                <input type="password" id="newPassword" name="newPassword" class="form-input" placeholder="Enter new password" required pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}" title="Password must be at least 6 characters long and contain at least one lowercase letter, one uppercase letter, and one number">
                <span class="password-toggle" onclick="togglePasswordVisibility('newPassword')">Show</span>
                <?php
            if (!empty($password_error)) {
                    echo "<p style='color: red;'>$password_error</p>";
                }
                ?>
            </div>
        </div>
        <div class="form-row">
            <label for="confirmPassword" class="form-label">Confirm New Password:</label>
            <div class="password-input-container">
                <input type="password" id="confirmPassword" name="confirmPassword" class="form-input" placeholder="Confirm new password" required pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}" title="Password must be at least 6 characters long and contain at least one lowercase letter, one uppercase letter, and one number">
                <span class="password-toggle" onclick="togglePasswordVisibility('confirmPassword')">Show</span>
                <?php
                if (!empty($reenter_password_error)) {
                    echo "<p style='color: red;'>$reenter_password_error</p>";
                }
                ?>
            </div>
        </div>
        <div class="form-row">
            <input type="submit" id="changePasswordBtn" name="changePasswordBtn" class="submit-btn" value="Change Password">
            <button type="button" id="cancelBtn" class="cancel-btn" onclick="window.location.href='trader_dashboard.php' ; return false;">Cancel</button>
        </div>
    </form>
</div>
<script src="trader_navbar.js"></script>
<script src="trader_change_password.js"></script>
</body>
</html>