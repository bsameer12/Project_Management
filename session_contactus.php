<?php
session_start();
// Error Reporting If any error ocuurs
error_reporting(E_ALL);
ini_set('display_errors',1);
// Variable for Input_validation 
$input_validation_passed = true;
if(isset($_POST["query"])){
     // Input Sanizatization 
     require("input_validation/input_sanitization.php");

     // Check if $_POST["shop-name"] Exists before sanitizing 
    $subject = isset($_POST["subject"]) ? sanitizeShopName($_POST["subject"]) : "";

    // Check if $_POST["shop-description"] Exists before sanitizing 
    $message = isset($_POST["message"]) ? sanitizeShopDescription($_POST["message"]) : "";

    // Input Validation
    require("input_validation/input_validation.php");
     // Validate shop name
     $shop_name_error = "";
     if (validateShopName($subject) === "false") {
         $shop_name_error = "Please Enter Your Subject Of Query.";
         $input_validation_passed = false;
     }

      // Validate Shop Descripyion
      $shop_description_error = "";
      if (validateShopDescription($message) === "false") {
          $validateShopDescription = "Please Enter Your Message.";
          $input_validation_passed = false;
      }

      if ($input_validation_passed) {

        include("connection/connection.php");
        $user_id = $_SESSION["userid"];
        // Prepare the SQL statement
            $sql_select_user = "SELECT FIRST_NAME, LAST_NAME, USER_EMAIL, USER_CONTACT_NO 
            FROM hudder_user 
            WHERE USER_ID = :user_id";

            // Prepare the OCI statement
            $stmt_select_user = oci_parse($conn, $sql_select_user);

            // Bind parameters
            oci_bind_by_name($stmt_select_user, ':user_id', $user_id);

            // Execute the OCI statement
            if (oci_execute($stmt_select_user)) {
            // Fetch the result
            $row = oci_fetch_assoc($stmt_select_user);

            // Check if a row was returned
            if ($row) {
            // Access the values
            $first_name = $row['FIRST_NAME'];
            $last_name = $row['LAST_NAME'];
            $email = $row['USER_EMAIL'];
            $contact_number = $row['USER_CONTACT_NO'];

        // Prepare the SQL statement for inserting data into the contactus table
            $sql_insert_contactus = "INSERT INTO contactus (FIRST_NAME, LAST_NAME, EMAIL, CONTACT_NO, SUBJECT, MESSAGE) 
            VALUES (:first_name, :last_name, :email, :contact_no, :subject, :message) RETURNING QUERY_ID INTO :query_id";

            // Prepare the OCI statement
            $stmt_insert_contactus = oci_parse($conn, $sql_insert_contactus);
            oci_bind_by_name($stmt_insert_contactus, ':first_name', $first_name);
            oci_bind_by_name($stmt_insert_contactus, ':last_name', $last_name);
            oci_bind_by_name($stmt_insert_contactus, ':email', $email);
            oci_bind_by_name($stmt_insert_contactus, ':contact_no', $contact_number);
            oci_bind_by_name($stmt_insert_contactus, ':subject', $subject);
            oci_bind_by_name($stmt_insert_contactus, ':message', $message);
            oci_bind_by_name($stmt_insert_contactus, ':query_id', $query_id, 10);

            // Execute the SQL statement
            if (oci_execute($stmt_insert_contactus)) {
                
                    require("PHPMailer-master/query_email.php");
                    $name = $first_name . " " . $last_name;
                    sendQueryReceivedEmail($email, $name, $query_id);
            } else {
            $error = oci_error($stmt_insert_contactus);
            echo "Error inserting data: " . $error['message'];
            }

            // Free the statement and close the connection
            oci_free_statement($stmt_insert_contactus);
        }else {
            $error = oci_error($stmt_select_user);
            echo "Error executing query: " . $error['message'];
        }
        
        // Free the statement
        oci_free_statement($stmt_select_user);
        oci_close($conn);
      }
    }
      else{
        $general_error = "Query Submisiion Unsuccessful!";
      }






}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="icon" href="logo_ico.png" type="image/png">
    <link rel="stylesheet" href="without_session_navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="contactus.css">
    <!-- swiper css file web -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font link -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php
        include("session_navbar.php");
    ?>
    <div id="contact-container" class="container">
    <h2>Contact Us</h2>
    <?php
            if (!empty($general_error)) {
                echo "<p style='color: red;'>$general_error</p>";
            }
            ?>
    <form action="" method="POST" id="contactus" name="contactus">
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="Enter the subject" required>
            <?php
            if (!empty($shop_name_error)) {
                    echo "<p style='color: red;'>$shop_name_error</p>";
                }
                ?>
        </div>
        <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" placeholder="Enter your message" required></textarea>
            <?php
            if (!empty($shop_description_error)) {
                    echo "<p style='color: red;'>$shop_description_error</p>";
                }
                ?>
        </div>
        <input type="submit" value="Submit Your Query" name="query" id="query">
    </form>
</div>
    <?php
        include("footer.php");
    ?>

    <script src="without_session_navbar.js"></script>
    <script src="index.js"></script>
    <!-- linking external js file -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</body>
</html>