<?php
session_start();

if (isset($_POST['resend_otp'])) {
    // Include your database connection here
    include "connection.php";

    $email = $_POST['email'];

    // Check if the email exists in your database
    $sql = "SELECT * FROM usertable WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            // Generate a new OTP
            $new_code = rand(999999, 111111);

            // Update the OTP in the database
            $update_sql = "UPDATE usertable SET code = ? WHERE email = ?";
            $update_stmt = $conn->prepare($update_sql);

            if ($update_stmt) {
                $update_stmt->bind_param("ss", $new_code, $email);
                $update_stmt->execute();
                $update_stmt->close();

                // Send the new OTP via email
                $to = $email;
                $subject = 'New Verification Code';
                $message = "Your new verification code for email $email:\nNew Verification Code is: $new_code";

                 $headers = 'From: epubeloiacsacswfo2@gmail.com' . "\r\n" .
            'Reply-To: mail.eloiacs@gmail.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                if (mail($to, $subject, $message, $headers)) {
                    // Email sent successfully
                    $message = 'New OTP sent successfully! Check your email for the OTP.';
                    $status = "success";
                } else {
                    // Email sending failed
                    $message = 'Email could not be sent due to some Unexpected Error. Please Try Again later.';
                    $status = "false";
                }
            } else {
                $message = "Error updating OTP: " . $conn->error;
                $status = "false";
            }
        } else {
            $message = "Email not found in the database.";
            $status = "false";
        }
    } else {
        $message = "Error checking email: " . $conn->error;
        $status = "false";
    }

    echo json_encode(array('message' => $message, 'status' => $status));
}
?>
