<?php
 include("admin_session.php");
include("../connection/connection.php");

        // Variable for Input_validation 
        $input_validation_passed = true;
        $user_id = $_GET["id"];

        // Prepare the SQL statement
        $sql = "SELECT FIRST_NAME, LAST_NAME, USER_ADDRESS, USER_EMAIL, USER_PROFILE_PICTURE, USER_CONTACT_NO
                FROM HUDDER_USER
                WHERE USER_ID = :user_id";

        // Prepare the OCI statement
        $stmt = oci_parse($conn, $sql);

        // Bind the user_id parameter
        oci_bind_by_name($stmt, ':user_id', $user_id);

        // Execute the statement
        if (oci_execute($stmt)) {
            // Fetch the result
            if ($row = oci_fetch_assoc($stmt)) {
                // Store the values in variables
                $first_name = $row['FIRST_NAME'];
                $last_name = $row['LAST_NAME'];
                $user_address = $row['USER_ADDRESS'];
                $user_email = $row['USER_EMAIL'];
                $user_profile_picture = $row['USER_PROFILE_PICTURE'];
                $user_contact_no = $row['USER_CONTACT_NO'];
                if(isset($_POST["saveChangesBtn"]))
                {
                        // Input Sanizatization 
                        require("../input_validation/input_sanitization.php");
                        // Check if $_POST["first-name"] exists before sanitizing
                        $first_name = isset($_POST["firstName"]) ? sanitizeFirstName($_POST["firstName"]) : "";

                        // Check if $_POST["last-name"] exists before sanitizing
                        $last_name = isset($_POST["lastName"]) ? sanitizeLastName($_POST["lastName"]) : "";

                        // Check if $_POST["address"] exists before sanitizing
                        $address = isset($_POST["address"]) ? sanitizeAddress($_POST["address"]) : "";

                        // Check if $_POST["contact"] exists before sanitizing
                        $contact_number = isset($_POST["contact"]) ? sanitizeContactNumber($_POST["contact"]) : "";

                        // Input Validation
                            require("../input_validation/input_validation.php");
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

                            $profile_upload_error="";
                            if (isset($_FILES["profilePicture"]) && $_FILES["profilePicture"]["error"] == 0) {
                            require("../input_validation/image_upload.php");
                            $result = uploadImage("../profile_image/", "profilePicture");
                                // Check the result
                                if ($result["success"] === 1) {
                                    // If upload was successful, store the new file name in a unique variable
                                    $newFileName = $result["fileName"];
                                } else {
                                    // If upload failed, display the error message
                                    $input_validation_passed = false;
                                    $profile_upload_error = $result["message"];
                                }
                            }else{
                                $newFileName = $_SESSION["picture"];
                            }

                                if ($input_validation_passed) {
                                    // Prepare the SQL statement for updating user information
                                    $sql_update_user = "UPDATE HUDDER_USER SET 
                                    FIRST_NAME = :first_name, 
                                    LAST_NAME = :last_name, 
                                    USER_ADDRESS = :user_address,   
                                    USER_PROFILE_PICTURE = :user_profile_picture, 
                                    USER_CONTACT_NO = :user_contact_no
                                    WHERE USER_ID = :user_id";

                                    // Prepare the OCI statement
                                    $stmt_update_user = oci_parse($conn, $sql_update_user);

                                    // Bind parameters
                                    oci_bind_by_name($stmt_update_user, ':first_name', $first_name);
                                    oci_bind_by_name($stmt_update_user, ':last_name', $last_name);
                                    oci_bind_by_name($stmt_update_user, ':user_address', $address);
                                    oci_bind_by_name($stmt_update_user, ':user_profile_picture', $newFileName);
                                    oci_bind_by_name($stmt_update_user, ':user_contact_no', $contact_number);
                                    oci_bind_by_name($stmt_update_user, ':user_id', $user_id);

                                    // Execute the SQL statement
                                    if (oci_execute($stmt_update_user)) {
                                        // Reload the page
                                        header("Location: ".$_SERVER['PHP_SELF'] . "?id=" . $user_id);
                                        exit();
                                    } else {
                                    $error = oci_error($stmt_update_user);
                                    echo "Error updating user information: " . $error['message'];
                                    }
                }

                } 
        } else {
            // Handle SQL execution error
            $error = oci_error($stmt);
            echo "Error executing SQL statement: " . $error['message'];
        }
    }

        // Free the statement and close the connection
        oci_free_statement($stmt);
        oci_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <link rel="stylesheet" href="admin_navbar.css">
    <link rel="stylesheet" href="admin_profile.css">
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
        include("admin_navbar.php");
    ?>
   <div class="container-heading">
        <h2 class="container-heading">Customer Profile  Details</h2>
        </div>
        <div id="profileDetailsContainer" class="profile-details-container">
    <div class="left-div">
        <img src="../profile_image/<?php echo $user_profile_picture ; ?>" alt="Profile Picture" class="profile-picture">
    </div>
    <div class="right-div">
        <form id="profileDetailsForm" class="profile-details-form" name="profileDetailsForm" method="POST" action="" enctype="multipart/form-data">
            <div class="form-row">
                <label for="firstName" class="form-label">First Name:</label>
                <input type="text" id="firstName" name="firstName" class="form-input" placeholder="Enter first name" required value="<?php echo $first_name ; ?>" pattern="[A-Za-z]+" title="Please enter only alphabetic characters">
                <?php
            if (!empty($first_name_error)) {
                    echo "<p style='color: red;'>$first_name_error</p>";
                }
                ?>
            </div>
            <div class="form-row">
                <label for="lastName" class="form-label">Last Name:</label>
                <input type="text" id="lastName" name="lastName" class="form-input" placeholder="Enter last name" required value="<?php echo $last_name ; ?>" pattern="[A-Za-z]+" title="Please enter only alphabetic characters">
                <?php
            if (!empty($last_name_error)) {
                    echo "<p style='color: red;'>$last_name_error</p>";
                }
                ?>
            </div>
            <div class="form-row">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="Enter email" readonly value="<?php echo $user_email ; ?>">
            </div>
            <div class="form-row">
                <label for="address" class="form-label">Address:</label>
                <textarea id="address" name="address" class="form-textarea" rows="4" placeholder="Enter address" required  pattern="[A-Za-z0-9,-]" title="Please enter alphanumeric characters, comma, or hyphen only"><?php echo $user_address; ?></textarea>
                <?php
            if (!empty($address_error)) {
                    echo "<p style='color: red;'>$address_error</p>";
                }
                ?>
            </div>
            <div class="form-row">
                <label for="contact" class="form-label">Contact:</label>
                <input type="tel" id="contact" name="contact" class="form-input" placeholder="Enter contact number" required value="<?php echo $user_contact_no ; ?>" pattern="[0-9]+" title="Please enter only numeric characters">
                <?php
            if (!empty($contact_no_error)) {
                    echo "<p style='color: red;'>$contact_no_error</p>";
                }
                ?>
            </div>
            <div class="form-row">
                <label for="profilePicture" class="form-label">Change Profile Picture:</label>
                <input type="file" id="profilePicture" name="profilePicture" class="form-input" accept="image/*">
                <?php
            if (!empty($profile_upload_error)) {
                    echo "<p style='color: red;'>$profile_upload_error</p>";
                }
                ?>
            </div>
            <div class="form-row">
                <input type="submit" id="saveChangesBtn" class="submit-btn" value="Save Changes" name="saveChangesBtn">
                <button id="cancelBtn" class="cancel-btn"  onclick="window.location.href='admin_dashboard.php' ; return false;">Cancel</button>
            </div>
        </form>
    </div>
</div>
<script src="admin_navbar.js"></script>
</body>
</html>