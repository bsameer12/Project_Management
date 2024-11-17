<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';


function sendApprovalEmail($to_email, $name, $shop_id, $trader_id, $SHOP_NAME, $TRADER_TYPE) {
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
        $mail->Subject = "Approval Notification";
        $mail->Body    = "
                            <!DOCTYPE html>
                            <html lang='en'>
                            <head>
                                <meta charset='UTF-8'>
                                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                                <title>Approval Notification</title>
                            </head>
                            <body style='font-family: Arial, sans-serif; text-align: center;'>
                            
                                <!-- Logo -->
                                <img src='https://i.ibb.co/37g1ZnN/enlarge-logo.png' alt='HudderFoods Logo' style='width: 100px; height: auto; margin-bottom: 20px;'>
                            
                                <!-- Heading -->
                                <h2 style='color: #333;'>Approval Notification</h2>
                            
                                <!-- Content -->
                                <p style='color: #666; margin-bottom: 20px;'>Dear $name,</p>
                                <p style='color: #666; margin-bottom: 20px;'>Congratulations! Your $TRADER_TYPE account for $SHOP_NAME has been approved.</p>
                                <p style='color: #666; margin-bottom: 20px;'>Your Shop ID: <strong>$shop_id</strong></p>
                                <p style='color: #666; margin-bottom: 20px;'>Your Trader ID: <strong>$trader_id</strong></p>
                            
                                <!-- Thank you message -->
                                <p style='color: #666;'>If you have any questions or concerns, feel free to contact us at <a href='mailto:contact@hudderfoods.com' style='color: #007bff;'>contact@hudderfoods.com</a>.</p>
                            
                                <!-- HudderFoods Logo -->
                                <img src='https://i.ibb.co/37g1ZnN/enlarge-logo.png' alt='HudderFoods Logo' style='width: 100px; height: auto; margin-top: 20px;'>
                            
                            </body>
                            </html>
        ";

        // Send email and update VERIFICATION_SEND
        if ($mail->send()) {
            // Connect to the database
            $conn = oci_connect('HudderFoods', 'Root123#', '//localhost/xe'); 

            // Update VERIFICATION_SEND to 1 where TRADER_ID matches trader_id
            $sql_update_status = "UPDATE TRADER SET VERIFICATION_SEND = 1 WHERE TRADER_ID = :trader_id";
            $stmt_update_status = oci_parse($conn, $sql_update_status);
            oci_bind_by_name($stmt_update_status, ':trader_id', $trader_id);
            oci_execute($stmt_update_status);
            oci_free_statement($stmt_update_status);
        } else {
            echo "Email sending failed: {$mail->ErrorInfo}";
        }
    } catch (Exception $e) {
        echo "Email sending failed: {$mail->ErrorInfo}";
    }
}

?>