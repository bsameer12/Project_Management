<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'src/Exception.php';
require_once 'src/PHPMailer.php';
require_once 'src/SMTP.php';

function sendQueryReceivedEmail($to_email, $name, $query_id) {
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
        $mail->addAddress($to_email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Query Received";
        $mail->Body    = "
                            <!DOCTYPE html>
                            <html lang='en'>
                            <head>
                                <meta charset='UTF-8'>
                                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                                <title>Query Received</title>
                            </head>
                            <body style='font-family: Arial, sans-serif; text-align: center;'>
                            
                                <!-- Logo -->
                                <img src='https://i.ibb.co/37g1ZnN/enlarge-logo.png' alt='HudderFoods Logo' style='width: 100px; height: auto; margin-bottom: 20px;'>
                            
                                <!-- Heading -->
                                <h2 style='color: #333;'>Query Received</h2>
                            
                                <!-- Text -->
                                <p style='color: #666; margin-bottom: 20px;'>Hello $name,<br> We have received your query. Our customer support team will contact you shortly.</p>
                            
                                <!-- Thank you message -->
                                <p style='color: #666;'>If you have any further questions, feel free to contact us at <a href='mailto:contact@hudderfoods.com' style='color: #007bff;'>contact@hudderfoods.com</a>.</p>
                            
                                <!-- HudderFoods Logo -->
                                <img src='https://i.ibb.co/37g1ZnN/enlarge-logo.png' alt='HudderFoods Logo' style='width: 100px; height: auto; margin-top: 20px;'>
                            
                            </body>
                            </html>
        ";

        // Send email
        if ($mail->send()) {
            $conn = oci_connect('HudderFoods', 'Root123#', '//localhost/xe'); 
            // Update verified_status to 1
            $sql_update_status = "UPDATE contactus SET verified_status = 1 WHERE query_id = :query_id";
            $stmt_update_status = oci_parse($conn, $sql_update_status);
            oci_bind_by_name($stmt_update_status, ':query_id', $query_id);
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
