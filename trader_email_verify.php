<?php

if(isset($_GET["user_id"]) && isset($_GET["email"])){
    $email_id = $_GET["email"];
    $user_id = $_GET["user_id"];
    if(isset($_POST["verify"])){
        $code = $_POST["verification_code"];
        include("connection/connection.php");
        // Prepare the SQL statement
            $sql = "SELECT VERIFICATION_CODE
            FROM TRADER
            WHERE USER_ID = :userid";

            // Prepare the OCI statement
            $stmt = oci_parse($conn, $sql);

            // Bind the userid parameter
            oci_bind_by_name($stmt, ':userid', $user_id);

            // Execute the statement
            oci_execute($stmt);

            // Fetch the result (assuming only one verification code per user_id)
            if ($row = oci_fetch_assoc($stmt)) {
            $verification_code = $row['VERIFICATION_CODE'];
            if($verification_code == $code){
                // Prepare the SQL statement
                            $sql = "UPDATE TRADER 
                            SET VERIFICATION_STATUS = :verified_customer
                            WHERE USER_ID = :userid";

                            // Prepare the OCI statement
                            $stmt = oci_parse($conn, $sql);

                            $verified_customer = 1; // Assuming you want to set VERIFIED_CUSTOMER to 1

                            // Bind the parameters
                            oci_bind_by_name($stmt, ':verified_customer', $verified_customer); // Here is the first issue
                            oci_bind_by_name($stmt, ':userid', $user_id); // Here is the second issue

                            // Execute the statement
                            if (oci_execute($stmt)) {
                            header("Location: index.php");
                            exit; // Ensure script stops execution after redirection
                            } else {
                            $error = oci_error($stmt);
                            echo "Error updating row: " . $error['message'];
                            }
            }   
            else{
                $verification_error = "Your verfication Code is not matching. Please try again!!!";
            } 
            // Free the statement and close the connection
            oci_free_statement($stmt);
            oci_close($conn);
                }
            }
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
        <p>A verification code has been sent to your email <strong><?php echo  $email_id; ?> </strong>.</p>
        <form action="" method="post" name="email_verify" id="email_verify" enctype="multipart/form-data">
            <label for="verification_code">Verification Code</label><br>
            <?php
            if (!empty($verification_error)) {
                    echo "<p style='color: red;'>$verification_error</p>";
                }
                ?>
            <input type="text" id="verification_code" name="verification_code" required pattern="[0-9]+" title="Please enter only numeric characters"><br>
            <input type="submit" value="Verify" id="verify" name="verify">
        </form>
    </div>
    <?php
        include("footer.php");
    ?>
    <script src="without_session_navbar.js"></script>
</body>
</html>