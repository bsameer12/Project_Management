<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'src/Exception.php';
require_once 'src/PHPMailer.php';
require_once 'src/SMTP.php';


function sendOrderConfirmationEmail($to_email, $order_no, $total_amount, $no_of_products, $pickup_date, $pickup_time, $pickup_location) {
    // Gmail SMTP configuration
    $smtp_username = "hudderfoods@gmail.com"; // Your Gmail address
    $smtp_password = "nwdn aldk rwao gxbc"; // Your Gmail password

    // Create a PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_username;
        $mail->Password   = $smtp_password;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom($smtp_username, "HudderFoods");
        $mail->addAddress($to_email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Order Confirmation";
        $mail->Body    = "
                            <!DOCTYPE html>
                            <html lang='en'>
                            <head>
                                <meta charset='UTF-8'>
                                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                                <title>Order Confirmation</title>
                            </head>
                            <body style='font-family: Arial, sans-serif; text-align: center;'>
                            
                                <!-- Logo -->
                                <img src='https://i.ibb.co/37g1ZnN/enlarge-logo.png' alt='HudderFoods Logo' style='width: 100px; height: auto; margin-bottom: 20px;'>
                            
                                <!-- Heading -->
                                <h2 style='color: #333;'>Your Order Has Been Confirmed</h2>
                            
                                <!-- Order Details -->
                                <p style='color: #666; margin-bottom: 20px;'>Thank you for your order! Your order details are as follows:</p>
                                <p style='color: #666; margin-bottom: 20px;'>Order Number: <strong>$order_no</strong></p>
                                <p style='color: #666; margin-bottom: 20px;'>Total Amount: <strong>$total_amount</strong></p>
                                <p style='color: #666; margin-bottom: 20px;'>Number of Products: <strong>$no_of_products</strong></p>
                            
                                <!-- Payment Details -->
                                <p style='color: #666; margin-bottom: 20px;'>Payment Mode: PayPal</p>
                                <p style='color: #666; margin-bottom: 20px;'>Payment Status: Complete</p>
                            
                                <!-- Pick Up Information -->
                                <h3 style='color: #333; margin-bottom: 10px;'>Pick Up Information</h3>
                                <p style='color: #666; margin-bottom: 10px;'>Pick Up Date: <strong>$pickup_date</strong></p>
                                <p style='color: #666; margin-bottom: 10px;'>Pick Up Time: <strong>$pickup_time</strong></p>
                                <p style='color: #666; margin-bottom: 20px;'>Pick Up Location: <strong>$pickup_location</strong></p>
                            
                                <!-- Thank you message -->
                                <p style='color: #666;'>If you have any questions or concerns regarding your order, feel free to contact us at <a href='mailto:contact@hudderfoods.com' style='color: #007bff;'>contact@hudderfoods.com</a>.</p>
                            
                                <!-- HudderFoods Logo -->
                                <img src='https://i.ibb.co/37g1ZnN/enlarge-logo.png' alt='HudderFoods Logo' style='width: 100px; height: auto; margin-top: 20px;'>
                            
                            </body>
                            </html>
        ";

        // Send email
        $mail->send();
    } catch (Exception $e) {
        echo "Email sending failed: {$mail->ErrorInfo}";
    }
}
?>