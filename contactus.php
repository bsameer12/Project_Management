<?php
// Error Reporting If any error ocuurs
error_reporting(E_ALL);
ini_set('display_errors',1);
// Variable for Input_validation 
$input_validation_passed = true;
if(isset($_POST["query"])){
     // Input Sanizatization 
     require("input_validation/input_sanitization.php");
     // Check if $_POST["first-name"] exists before sanitizing
     $first_name = isset($_POST["first-name"]) ? sanitizeFirstName($_POST["first-name"]) : "";
 
     // Check if $_POST["last-name"] exists before sanitizing
     $last_name = isset($_POST["last-name"]) ? sanitizeLastName($_POST["last-name"]) : "";
 
     // Check if $_POST["email"] exists before sanitizing
     $email = isset($_POST["email"]) ? sanitizeEmail($_POST["email"]) : "";

      // Check if $_POST["contact"] exists before sanitizing
    $contact_number = isset($_POST["phone"]) ? sanitizeContactNumber($_POST["phone"]) : "";

     // Check if $_POST["shop-name"] Exists before sanitizing 
    $subject = isset($_POST["subject"]) ? sanitizeShopName($_POST["subject"]) : "";

    // Check if $_POST["shop-description"] Exists before sanitizing 
    $message = isset($_POST["message"]) ? sanitizeShopDescription($_POST["message"]) : "";

    // Input Validation
    require("input_validation/input_validation.php");
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

     // Validate contact number
     $contact_no_error = "";
     if (validateContactNumber($contact_number) === "false") {
         $contact_no_error = "Please Provide a Contact number";
         $input_validation_passed = false;
     }

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
                
                    require_once("PHPMailer-master/query_email.php");
                    $name = $first_name . " " . $last_name;
                    sendQueryReceivedEmail($email, $name, $query_id);
            } else {
            $error = oci_error($stmt_insert_contactus);
            echo "Error inserting data: " . $error['message'];
            }

            // Free the statement and close the connection
            oci_free_statement($stmt_insert_contactus);
            oci_close($conn);
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
        include("without_session_navbar.php");
    ?>
    <div id="contact-container" class="container">
    <h2>Contact Us</h2>
    <?php
            if (!empty($general_error)) {
                echo "<p style='color: red;'>$general_error</p>";
            }
            ?>
    <form action="" method="POST" id="contactus" name="contactus">
            <div class='form-group'>
            <label for='first-name'>First Name</label>
            <input type='text' id='first-name' name='first-name' placeholder='Enter your first name' required>
            <?php
            if (!empty($first_name_error)) {
                    echo "<p style='color: red;'>$first_name_error</p>";
                }
                ?>
            </div>
            <div class='form-group'>
            <label for='last-name'>Lasst Name</label>
            <input type='text' id='last-name' name='last-name' placeholder='Enter your last name' required>
            <?php
            if (!empty($last_name_error)) {
                echo "<p style='color: red;'>$last_name_error</p>";
            }
            ?>
            </div>
        <div class='form-group'>
            <label for='email'>Email</label>
            <input type='email' id='email' name='email' placeholder='Enter your email' required>
        </div>
        <div class='form-group'>
            <label for='phone'>Contact No.</label>
            <input type='tel' id='phone' name='phone' placeholder='Enter your phone number' required>
            <?php
            if (!empty($contact_no_error)) {
                echo "<p style='color: red;'>$contact_no_error</p>";
            }
            ?>
        </div>

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