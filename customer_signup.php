<?php
// Error Reporting If any error ocuurs
error_reporting(E_ALL);
ini_set('display_errors',1);

// Variable for Input_validation 
$input_validation_passed = true;

include("connection/connection.php");

function calculateAge($dob) {
    // Create a DateTime object from the date of birth
    $dob = new DateTime($dob);
    // Get the current date
    $now = new DateTime();
    // Calculate the difference between the current date and the date of birth
    $age = $now->diff($dob);
    // Return the difference in years as the age
    return $age->y;
}


if(isset($_POST["submit_sign_up"]) && isset($_POST["terms"]))
{
    // Input Sanizatization 
    require("input_validation\input_sanitization.php");
    // Check if $_POST["first-name"] exists before sanitizing
    $first_name = isset($_POST["first-name"]) ? sanitizeFirstName($_POST["first-name"]) : "";

    // Check if $_POST["last-name"] exists before sanitizing
    $last_name = isset($_POST["last-name"]) ? sanitizeLastName($_POST["last-name"]) : "";

    // Check if $_POST["email"] exists before sanitizing
    $email = isset($_POST["email"]) ? sanitizeEmail($_POST["email"]) : "";

    // Check if $_POST["password"] exists before sanitizing
    $password = isset($_POST["password"]) ? sanitizePassword($_POST["password"]) : "";

    // Check if $_POST["confirm-password"] exists before sanitizing
    $confirm_password = isset($_POST["confirm-password"]) ? sanitizePassword($_POST["confirm-password"]) : "";

    // Check if $_POST["dob"] exists before sanitizing
    $dob = isset($_POST["dob"]) ? sanitizeDOB($_POST["dob"]) : "";

    // Check if $_POST["gender"] exists before sanitizing
    $gender = isset($_POST["gender"]) ? sanitizeGender($_POST["gender"]) : "";

    // Check if $_POST["address"] exists before sanitizing
    $address = isset($_POST["address"]) ? sanitizeAddress($_POST["address"]) : "";

    // Check if $_POST["contact"] exists before sanitizing
    $contact_number = isset($_POST["contact"]) ? sanitizeContactNumber($_POST["contact"]) : "";

    $age = calculateAge($dob);




    // Input Validation
    require("input_validation\input_validation.php");
    $email_error = "";
        // Check if email exists
        if (emailExists($email) === "true") {
            $email_error = "Email Already Exists!!!";
            $input_validation_passed = false;
        }

        // Validate first name
        $first_name_error = "";
        if (validateFirstName($first_name) === "false") {
            $first_name_error = "Please Enter a Correct First Name";
            $input_validation_passed = false;
        }

        // Validate last name
        $last_name_error = "";
        if (validateLastName($last_name) === "false") {
            $last_name_error = "Please Enter a Correct Last Name";
            $input_validation_passed = false;
        }

        // Validate address
        $address_error = "";
        if (validateAddress($address) === "false") {
            $address_error = "Please Enter Your Address";
            $input_validation_passed = false;
        }

        // Validate contact number
        $contact_no_error = "";
        if (validateContactNumber($contact_number) === "false") {
            $contact_no_error = "Please Provide a Contact number";
            $input_validation_passed = false;
        }

        // Validate password
        $password_error = "";
        if (validatePassword($_POST["password"]) === "false") {
            $password_error = "Password must contain at least six characters including one lowercase letter, one uppercase letter, and one digit.";
            $input_validation_passed = false;
        }

        // Validate confirm password
        $reenter_password_error = "";
        if (validateConfirmPassword($_POST["password"], $_POST["confirm-password"]) === "false") {
            $reenter_password_error = "Password Didn't matched";
            $input_validation_passed = false;
        }

        // Validate date of birth
        $dob_error = "";
        if (validateDateOfBirth($dob) === "false") {
            $dob_error = "Please Enter Your Date Of Birth.";
            $input_validation_passed = false;
        }

        // Validate gender
        $gender_error = "";
        if (validateGender($gender) === "false") {
            $gender_error = "Please Select Your Gender.";
            $input_validation_passed = false;
        }


    $profile_upload_error="";
    require("input_validation\image_upload.php");
    $result = uploadImage("profile_image/", "profile-pic");
        // Check the result
        if ($result["success"] === 1) {
            // If upload was successful, store the new file name in a unique variable
            $newFileName = $result["fileName"];
        } else {
            // If upload failed, display the error message
            $input_validation_passed = false;
            $profile_upload_error = $result["message"];
        }

        $user_role = "customer";
        $todayDate = date('Y-m-d'); // Format: YYYY-MM-DD
        $update_date = date('Y-m-d'); // Format: YYYY-MM-DD
        require("otp\otp_genearator.php");
        $verification_code = generateRandomCode();
        if ($input_validation_passed) {
            // Prepare the SQL statement for user insertion
            $sql_insert_user = "INSERT INTO HUDDER_USER (first_name, last_name, user_address, user_email, user_gender, user_password, USER_PROFILE_PICTURE, user_type, user_contact_no, USER_AGE)
                                VALUES (:first_name, :last_name, :user_address, :user_email, :user_gender, :user_password, :USER_PROFILE_PICTURE, 'customer', :user_contact_no, :user_age)";
            $stmt_insert_user = oci_parse($conn, $sql_insert_user);
        
            // Bind parameters
            oci_bind_by_name($stmt_insert_user, ':first_name', $first_name, -1, SQLT_CHR);
            oci_bind_by_name($stmt_insert_user, ':last_name', $last_name, -1, SQLT_CHR);
            oci_bind_by_name($stmt_insert_user, ':user_address', $address, -1, SQLT_CHR);
            oci_bind_by_name($stmt_insert_user, ':user_email', $email, -1, SQLT_CHR);
            oci_bind_by_name($stmt_insert_user, ':user_gender', $gender, -1, SQLT_CHR);            
            oci_bind_by_name($stmt_insert_user, ':user_password', $password);
            oci_bind_by_name($stmt_insert_user, ':USER_PROFILE_PICTURE', $newFileName);
            oci_bind_by_name($stmt_insert_user, ':user_contact_no', $contact_number);
            oci_bind_by_name($stmt_insert_user, ':user_age', $age);
        
            // Execute the SQL statement
            if (!oci_execute($stmt_insert_user)) {
                die("Error inserting user: " . oci_error()['message']);
            }
        
            // Prepare the SQL statement
            $sql = "SELECT user_id FROM HUDDER_USER WHERE user_email = :email";

            // Prepare the OCI statement
            $stmt = oci_parse($conn, $sql);

            // Bind the email parameter
            oci_bind_by_name($stmt, ':email', $email);

            // Execute the statement
            oci_execute($stmt);

            // Fetch the result
            if ($row = oci_fetch_assoc($stmt)) {
                $user_id = $row['USER_ID'];
            } 


            // Prepare the SQL statement
            $sql = "INSERT INTO CUSTOMER 
                    (CUSTOMER_DATE_JOINED, VERIFICATION_CODE, DATE_UPDATED, VERIFIED_CUSTOMER, USER_ID, PROFILE_PICTURE) 
                    VALUES 
                    (TO_DATE(:customer_date_joined, 'YYYY-MM-DD'), :verification_code, TO_DATE(:date_updated, 'YYYY-MM-DD'), :verified_customer, :user_id, :profile_picture)";

            // Prepare the OCI statement
            $stmt = oci_parse($conn, $sql);

            $verified_customer = 0;


            // Bind the parameters
            oci_bind_by_name($stmt, ':customer_date_joined', $todayDate);
            oci_bind_by_name($stmt, ':verification_code', $verification_code);
            oci_bind_by_name($stmt, ':date_updated', $update_date);
            oci_bind_by_name($stmt, ':verified_customer', $verified_customer);
            oci_bind_by_name($stmt, ':user_id', $user_id);
            oci_bind_by_name($stmt, ':profile_picture', $newFileName);

            // Execute the statement
            if (oci_execute($stmt)) {
                require("PHPMailer-master/email.php");
                $full_name = $first_name . " " . $last_name;
                sendVerificationEmail($email, $verification_code,$full_name );
                header("Location:email_verify.php?user_id=$user_id&email=$email");
            } else {
                $error = oci_error($stmt);
                echo "Error inserting row: " . $error['message'];
            }
            // Free the statement and close the connection
            oci_free_statement($stmt);
            oci_close($conn);
        } else {
            // Validation failed, set a general error message or handle the failure as needed
            $general_error_message = "Validation failed. Please check the form for errors.";
        }
}
else{
    $checkbox_error = "Please Agree to Our Terms and conditions?";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Sign Up</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
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
    <form method = "POST" id="customer_signup" name="customer_signup" action="" enctype="multipart/form-data" >
        <div class="form-group">
            <label for="first-name">First Name</label>
            <input type="text" id="first-name" name="first-name" placeholder="Enter your first name" required pattern="[A-Za-z]+" title="Please enter only alphabetic characters">
            <?php
            if (!empty($first_name_error)) {
                    echo "<p style='color: red;'>$first_name_error</p>";
                }
                ?>
        </div>
        <div class="form-group">
            <label for="last-name">Last Name</label>
            <input type="text" id="last-name" name="last-name" placeholder="Enter your last name" required pattern="[A-Za-z]+" title="Please enter only alphabetic characters">
            <?php
            if (!empty($last_name_error)) {
                    echo "<p style='color: red;'>$last_name_error</p>";
                }
                ?>
            
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <?php
            if (!empty($email_error)) {
                    echo "<p style='color: red;'>$email_error</p>";
                }
                ?>
            
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}" title="Password must be at least 6 characters long and contain at least one lowercase letter, one uppercase letter, and one number">
            <?php
            if (!empty($password_error)) {
                    echo "<p style='color: red;'>$password_error</p>";
                }
                ?>
        </div>
        <div class="form-group">
            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}" title="Password must be at least 6 characters long and contain at least one lowercase letter, one uppercase letter, and one number" required>
            <?php
            if (!empty($reenter_password_error)) {
                    echo "<p style='color: red;'>$reenter_password_error</p>";
                }
                ?>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" required>
            <?php
            if (!empty($dob_error)) {
                    echo "<p style='color: red;'>$dob_error</p>";
                }
                ?>
        </div>
        <div class="form-group">
            <label>Gender</label><br>
            <label for="male" style="display: inline-block; margin-right: 10px; "> <input type="radio" id="male" name="gender" value="male" required> Male</label>
            <label for="female" style="display: inline-block; margin-right: 10px; "><input type="radio" id="female" name="gender" value="female"> Female</label>
            <label for="other" style="display: inline-block; margin-right: 10px; "><input type="radio" id="other" name="gender" value="other"> Other</label>
            <?php
            if (!empty($gender_error)) {
                    echo "<p style='color: red;'>$gender_error</p>";
                }
                ?>
        </div>
        <div class="form-group">
            <label for="contact">Contact Number</label>
            <input type="tel" id="contact" name="contact" placeholder="Enter your contact number" required pattern="[0-9]+" title="Please enter only numeric characters">
            <?php
            if (!empty($contact_no_error)) {
                    echo "<p style='color: red;'>$contact_no_error</p>";
                }
                ?>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" placeholder="Enter your address" required  pattern="[A-Za-z0-9,-]" title="Please enter alphanumeric characters, comma, or hyphen only"></textarea>
            <?php
            if (!empty($address_error)) {
                    echo "<p style='color: red;'>$address_error</p>";
                }
                ?>
        </div>
        <div class="form-group">
            <label for="profile-pic">Profile Picture</label>
            <input type="file" id="profile-pic" name="profile-pic" accept="image/*" required>
            <?php
            if (!empty($profile_upload_error)) {
                    echo "<p style='color: red;'>$profile_upload_error</p>";
                }
                ?>
        </div>
        <div class="form-group">
            <label for="terms"> <input type="checkbox" id="terms" name="terms" required> I agree to the Terms and Conditions</label>
            <?php
            if (!empty($general_error_message)) {
                    echo "<p style='color: red;'>$general_error_message</p>";
                }
                ?>
        </div>
        <div class="form-group">
            <input type="submit" value="Sign Up" name="submit_sign_up" id="submit_sign_up">
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