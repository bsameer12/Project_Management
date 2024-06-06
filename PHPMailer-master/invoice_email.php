<?php

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'src/Exception.php';
require_once 'src/PHPMailer.php';
require_once 'src/SMTP.php';

function sendInvoiceEmail($order_id, $user_id) {
    // Create an instance; passing true enables exceptions
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 0;                      // Enable verbose debug output
        $mail->isSMTP();                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';       // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                   // Enable SMTP authentication
        $mail->Username   = 'hudderfoods@gmail.com'; // SMTP username
        $mail->Password   = 'nwdn aldk rwao gxbc';  // SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Include the database connection
        include("connection/connection.php");
        $conn = oci_connect('HudderFoods', 'Root123#', '//localhost/xe'); 

        // Recipients
        $mail->setFrom('hudderfoods@gmail.com', 'HudderFoods');
        $getemail = "SELECT USER_EMAIL FROM HUDDER_USER WHERE USER_ID = :user_id";
        $emailid = oci_parse($conn, $getemail);
        oci_bind_by_name($emailid, ':user_id', $user_id);
        oci_execute($emailid);

        // Fetch email
        $email = '';
        while ($row = oci_fetch_array($emailid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $email = $row['USER_EMAIL'];
        }

        $mail->addAddress($email);   // Add a recipient
        $mail->addReplyTo("hudderfoods@gmail.com", "HudderFoods");

        $emailUser = "SELECT FIRST_NAME || ' ' || LAST_NAME AS NAME FROM HUDDER_USER WHERE USER_ID = :user_id";
        $stidEmail = oci_parse($conn, $emailUser);
        oci_bind_by_name($stidEmail, ':user_id', $user_id);
        oci_execute($stidEmail);

        $name = '';
        while ($row = oci_fetch_array($stidEmail, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $name = $row['NAME'];
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'HudderFoods INVOICE';

        $sqlEmailSelect = "
        SELECT 
        od.PRODUCT_QTY, 
        od.PRODUCT_ID, 
        od.PRODUCT_PRICE,
        p.PRODUCT_NAME, 
        p.PRODUCT_PRICE AS ACTUAL_PRICE,
        NVL(d.DISCOUNT_PERCENT, 0) AS DISCOUNT_PERCENT
        FROM 
        ORDER_DETAILS od
        JOIN 
        PRODUCT p ON od.PRODUCT_ID = p.PRODUCT_ID
        LEFT JOIN 
        DISCOUNT d ON od.PRODUCT_ID = d.PRODUCT_ID
        WHERE 
        od.ORDER_PRODUCT_ID = :order_id
        ";

        $stiEmailSelect = oci_parse($conn, $sqlEmailSelect);
        oci_bind_by_name($stiEmailSelect, ':order_id', $order_id);
        oci_execute($stiEmailSelect);

        $mail->Body = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>HudderFoods Invoice</title>
        </head>
        <body style='font-family: Arial, sans-serif; text-align: center;'>
        
            <!-- Logo -->
            <img src='https://i.ibb.co/37g1ZnN/enlarge-logo.png' alt='HudderFoods Logo' style='width: 100px; height: auto; margin-bottom: 20px;'>

            <!-- Heading -->
            <h2 style='color: #333;'>HudderFoods Invoice</h2>
        
            <h3 style='text-align: center; font-size: 20px;'>Thank you <b style='text-transform: uppercase;'>$name</b> for Choosing HudderFoods<br>Here is your Invoice Detail </h3>

            <h1 style='font: bold 100% sans-serif; padding:10px; width:100%; text-align: center; text-transform: uppercase;background-color:#7FA8D4; color:white; font-size: 18px;'>Invoice Details</h1>
            <br>
            <table border=1 style='border-collapse: collapse; width:70%; text-align:center;margin-right:auto;margin-left:auto; font-size: 15px;'>
                <thead>
                    <tr style=' color:white; background-color:#33FFF9; font-weight:bold;'>
                        <th style=' padding: 15px;'>Product Name</th>   
                        <th style=' padding: 15px;'>Quantity</th>
                        <th style=' padding: 15px;'>Price</th>
                    </tr>   
                </thead>
                <tbody>
        ";

        $totalPrice = 0;
        while ($row = oci_fetch_array($stiEmailSelect, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $discount = $row['DISCOUNT_PERCENT'];
            $price = $row['ACTUAL_PRICE'];
            $priceWithDiscount = $price - $discount;

            $totalPrice += $priceWithDiscount * $row['PRODUCT_QTY'];

            // Check if Quantity is set, otherwise set it to 0
            $quantity = isset($row['PRODUCT_QTY']) ? $row['PRODUCT_QTY'] : 0;
            $mail->Body .= "
                <tr>
                    <td style=' padding: 15px;'>" . $row['PRODUCT_NAME'] . "</td>
                    <td style=' padding: 15px;'>" . $quantity . "</td>
                    <td style=' padding: 15px;'>" . number_format($priceWithDiscount, 2) . "</td>
                </tr>
            ";
        }

        $mail->Body .= "
                <tr>
                    <td colspan='3' style='padding: 15px;'>Total price =&nbsp; £" . number_format($totalPrice, 2) . "</td>
                </tr>
            </tbody>
            </table>
            <p style='text-align: center;'><b>Hope your cart was as full as your heart. Come back soon! HudderFoods<b></p>

            <!-- Thank you message -->
            <p style='color: #666;'>If you have any questions or concerns regarding your order, feel free to contact us at <a href='mailto:contact@hudderfoods.com' style='color: #007bff;'>contact@hudderfoods.com</a>.</p>
        
            <!-- HudderFoods Logo -->
            <img src='https://i.ibb.co/37g1ZnN/enlarge-logo.png' alt='HudderFoods Logo' style='width: 100px; height: auto; margin-top: 20px;'>
        </body>
        </html>
        ";

        // Alternate body for non-HTML mail clients
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        // Send email
        $mail->send();
    } catch (Exception $e) {
        // Handle exceptions
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

