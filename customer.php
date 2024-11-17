<?php
include("session/session.php");
include("connection/connection.php");

        // Variable for Input_validation 
        $input_validation_passed = true;
        $user_id = $_SESSION["userid"];

        // Prepare the SQL statement
        $sql = "SELECT FIRST_NAME, LAST_NAME, USER_ADDRESS, USER_EMAIL, USER_AGE, USER_GENDER, USER_PASSWORD, USER_PROFILE_PICTURE, USER_TYPE, USER_CONTACT_NO, USER_DOB
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
                $user_age = $row['USER_AGE'];
                $user_gender = $row['USER_GENDER'];
                $user_password = $row['USER_PASSWORD'];
                $user_profile_picture = $row['USER_PROFILE_PICTURE'];
                $user_type = $row['USER_TYPE'];
                $user_contact_no = $row['USER_CONTACT_NO'];
                $dob = $row["USER_DOB"];
                // Convert Oracle date format to PHP DateTime object
                $dob_date = new DateTime($dob);

                // Format the date as required (YYYY-MM-DD)
                $formatted_dob = $dob_date->format('Y-m-d');

                if(isset($_POST["save"]))
                {
                        // Input Sanizatization 
                        require("input_validation\input_sanitization.php");
                        // Check if $_POST["first-name"] exists before sanitizing
                        $first_name = isset($_POST["fname"]) ? sanitizeFirstName($_POST["fname"]) : "";

                        // Check if $_POST["last-name"] exists before sanitizing
                        $last_name = isset($_POST["lname"]) ? sanitizeLastName($_POST["lname"]) : "";

                        // Check if $_POST["dob"] exists before sanitizing
                        $dob = isset($_POST["dob"]) ? sanitizeDOB($_POST["dob"]) : "";

                        // Check if $_POST["gender"] exists before sanitizing
                        $gender = isset($_POST["gender"]) ? sanitizeGender($_POST["gender"]) : "";

                        // Check if $_POST["address"] exists before sanitizing
                        $address = isset($_POST["address"]) ? sanitizeAddress($_POST["address"]) : "";

                        // Check if $_POST["contact"] exists before sanitizing
                        $contact_number = isset($_POST["contact"]) ? sanitizeContactNumber($_POST["contact"]) : "";

                        // Input Validation
                            require("input_validation\input_validation.php");
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
                            if(isset($_FILES["profile-pic"]) && $_FILES["profile-pic"]["error"] == 0){
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
                            }else{
                                $newFileName = $_SESSION["picture"];
                            }

                                if ($input_validation_passed) {
                                    // Prepare the SQL statement for updating user information
                                    $sql_update_user = "UPDATE HUDDER_USER SET 
                                    FIRST_NAME = :first_name, 
                                    LAST_NAME = :last_name, 
                                    USER_ADDRESS = :user_address, 
                                    USER_GENDER = :user_gender,  
                                    USER_PROFILE_PICTURE = :user_profile_picture, 
                                    USER_CONTACT_NO = :user_contact_no,
                                    USER_DOB = TO_DATE(:user_dob, 'YYYY-MM-DD')
                                    WHERE USER_ID = :user_id";

                                    // Prepare the OCI statement
                                    $stmt_update_user = oci_parse($conn, $sql_update_user);

                                    // Bind parameters
                                    oci_bind_by_name($stmt_update_user, ':first_name', $first_name);
                                    oci_bind_by_name($stmt_update_user, ':last_name', $last_name);
                                    oci_bind_by_name($stmt_update_user, ':user_address', $address);
                                    oci_bind_by_name($stmt_update_user, ':user_gender', $gender);
                                    oci_bind_by_name($stmt_update_user, ':user_profile_picture', $newFileName);
                                    oci_bind_by_name($stmt_update_user, ':user_contact_no', $contact_number);
                                    oci_bind_by_name($stmt_update_user, ':user_dob', $dob);
                                    oci_bind_by_name($stmt_update_user, ':user_id', $user_id);

                                    // Execute the SQL statement
                                    if (oci_execute($stmt_update_user)) {
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
                                        header("Location: ".$_SERVER['PHP_SELF']);
                                        exit();
                                        } else {
                                        $error = oci_error($stmt_update_date);
                                        echo "Error updating DATE_UPDATED column: " . $error['message'];
                                        }
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
        // Prepare the SQL query
$sql = "
SELECT op.ORDER_PRODUCT_ID, op.ORDER_DATE, op.TOTAL_PRICE, op.DISCOUNT_AMOUNT, op.ORDER_STATUS
FROM ORDER_PRODUCT op
JOIN CUSTOMER c ON op.CUSTOMER_ID = c.CUSTOMER_ID
WHERE c.USER_ID = :user_id
";

// Parse the SQL query
$stid = oci_parse($conn, $sql);
if (!$stid) {
    $e = oci_error($conn);
    echo "Failed to prepare statement: " . $e['message'];
    exit;
}

// Bind the user_id parameter to the SQL query
oci_bind_by_name($stid, ':user_id', $user_id);

// Execute the SQL query
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    echo "Failed to execute statement: " . $e['message'];
    exit;
}

// Initialize an array to store the results
$results = array();

// Fetch the results
while (($row = oci_fetch_assoc($stid)) != false) {
    $results[] = $row;
}

// Free the statement identifier and close the connection
oci_free_statement($stid);

// Function to get the status text based on the status value
function getOrderStatusText($status) {
    switch ($status) {
        case 0:
            return "Order Incompleted";
        case 1:
            return "Payment Complete";
        case 2:
            return "Order Prepared";
        case 3:
            return "Order Ready to Pick Up";
        case 4:
            return "Order Delivered";
        default:
            return "Unknown Status";
    }
}

// Prepare the SQL query
$sql = "
SELECT r.REVIEW_SCORE, r.FEEDBACK, p.PRODUCT_NAME
FROM REVIEW r
JOIN PRODUCT p ON r.PRODUCT_ID = p.PRODUCT_ID
WHERE r.USER_ID = :user_id AND r.REVIEW_PROCIDED = 1
";

// Parse the SQL query
$stid = oci_parse($conn, $sql);
if (!$stid) {
    $e = oci_error($conn);
    echo "Failed to prepare statement: " . $e['message'];
    exit;
}

// Bind the user_id parameter to the SQL query
oci_bind_by_name($stid, ':user_id', $user_id);

// Execute the SQL query
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    echo "Failed to execute statement: " . $e['message'];
    exit;
}

// Initialize an array to store the results
$results_r = array();

// Fetch the results
while (($row = oci_fetch_assoc($stid)) != false) {
    $results_r[] = $row;
}

// Free the statement identifier and close the connection
oci_free_statement($stid);

        oci_close($conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="customer.css">
    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css">
</head>
<body>
    <?php
        include("session_navbar.php");
    ?>
    <div class="profile-container">
    <!-- Left side -->
    <div class="left-side">
        <div class="profile-picture">
            <!-- Placeholder for profile picture -->
            <img src="profile_image/<?php echo  $user_profile_picture ; ?>" alt="Profile Picture">
        </div>
        <div class="navigation">
            <button class="nav-btn active">Profile</button>
            <button class="nav-btn">My Orders</button>
            <button class="nav-btn">My Reviews</button>
        </div>
    </div>
    
    <!-- Right side -->
    <div class="right-side">
    <div class="personal-info">
    <h2>Personal Information</h2>
    <form id="personal-info-form" action="" enctype="multipart/form-data" name="personal-info-form" method="post">
        <div class="form-row">
            <div class="input-group">
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="fname" required value="<?php echo $first_name ; ?>" pattern="[A-Za-z]+" title="Please enter only alphabetic characters">
                <?php
            if (!empty($first_name_error)) {
                    echo "<p style='color: red;'>$first_name_error</p>";
                }
                ?>
            </div>
            <div class="input-group">
                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" required value="<?php echo $last_name ; ?>" pattern="[A-Za-z]+" title="Please enter only alphabetic characters">
                <?php
            if (!empty($last_name_error)) {
                    echo "<p style='color: red;'>$last_name_error</p>";
                }
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" readonly value="<?php echo $user_email ; ?>">

            </div>
            <div class="input-group">
                <label for="contact">Contact Number</label>
                <input type="text" id="contact" name="contact" required value="<?php echo $user_contact_no ; ?>" pattern="[0-9]+" title="Please enter only numeric characters">
                <?php
            if (!empty($contact_no_error)) {
                    echo "<p style='color: red;'>$contact_no_error</p>";
                }
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="input-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" required value="<?php echo $user_address ; ?>" pattern="[A-Za-z0-9,-]" title="Please enter alphanumeric characters, comma, or hyphen only">
                <?php
            if (!empty($address_error)) {
                    echo "<p style='color: red;'>$address_error</p>";
                }
                ?>
            </div>
            <div class="input-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required value="<?php echo $formatted_dob ; ?>" >
                <?php
            if (!empty($dob_error)) {
                    echo "<p style='color: red;'>$dob_error</p>";
                }
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="input-group">
                <label for="gender">Gender</label>
                <select name="gender" id="gender" name="gender" required style="border: none !important; outline: none !important;">
                <option value="male" <?php echo (trim(strtolower($user_gender)) == 'male') ? 'selected' : ''; ?>>Male</option>
    <option value="female" <?php echo (trim(strtolower($user_gender)) == 'female') ? 'selected' : ''; ?>>Female</option>
    <option value="other" <?php echo (trim(strtolower($user_gender)) == 'other') ? 'selected' : ''; ?>>Other</option>
                </select>
                <?php
            if (!empty($gender_error)) {
                    echo "<p style='color: red;'>$gender_error</p>";
                }
                ?>
            </div>
            <div class="input-group">
            <label for="profile-pic">Update Profile Picture</label>
            <input type="file" id="profile-pic" name="profile-pic" accept="image/*">
            <?php
            if (!empty($profile_upload_error)) {
                    echo "<p style='color: red;'>$profile_upload_error</p>";
                }
                ?>
    </div>
        </div>
        <div class="form-row">
            <input type="submit" class="save-btn" name="save" id="save" value="save">
            <button type="button" class="delete-account-btn">Delete Account</button>
        </div>
    </form>
</div>
        <!-- Dummy table for My Orders -->

    <div class="my-orders-table hidden">
    <h2>My Orders</h2>
        <table id="order_table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Total Price</th>
                    <th>Total Discount</th>
                    <th>Order Status</th>
                    <th> Action </th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($results as $order): ?>
            <tr>
                <td><?php echo htmlspecialchars($order['ORDER_PRODUCT_ID']); ?></td>
                <td><?php echo htmlspecialchars($order['ORDER_DATE']); ?></td>
                <td><?php echo htmlspecialchars($order['TOTAL_PRICE']); ?></td>
                <td><?php echo htmlspecialchars($order['DISCOUNT_AMOUNT']); ?></td>
                <td><?php echo htmlspecialchars(getOrderStatusText($order['ORDER_STATUS'])); ?></td>
                <td><a href="view_order.php?id=<?php echo urlencode($order['ORDER_PRODUCT_ID']); ?>&action=edit">View</a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Dummy table for My Reviews -->
    <div class="my-reviews-table hidden">
        <h2>My Reviews</h2>
        <table id="review_table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Rating</th>
                    <th>Review</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results_r as $review): ?>
            <tr>
                <td><?php echo htmlspecialchars($review['PRODUCT_NAME']); ?></td>
                <td><?php echo htmlspecialchars($review['REVIEW_SCORE']) . ' stars'; ?></td>
                <td><?php echo htmlspecialchars($review['FEEDBACK']); ?></td>
            </tr>
            <?php endforeach; ?>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
</div>
</div>
        <?php
        include("footer.php");
    ?>

    <script src="without_session_navbar.js"></script>
    <script src="customer.js"></script>

    <!-- linking external js file -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#order_table, #review_table').DataTable({
                responsive: true
            });
        });
    </script>
</body>
</html>
