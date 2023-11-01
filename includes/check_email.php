<?php
session_start(); // Start the session

include "connection.php";

if (isset($_POST['email']) && isset($_POST['employee_id'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $employee_id = mysqli_real_escape_string($conn, $_POST['employee_id']);

    // Check if the email and employee_id exist in the database
    $query = "SELECT `name` FROM `usertable` WHERE `email` = '$email' AND `name` = '$employee_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Generate a unique six-digit verification code
        $verification_code = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $status_notverified = "Notverified";
        
        // Update the user's record in the database to store the verification code
        $update_query = "UPDATE `usertable` SET `code`='$verification_code', `status`='$status_notverified' WHERE `email`='$email' AND `name`='$employee_id'";
        mysqli_query($conn, $update_query);

        // Send an email with the verification code
        $subject = 'Verification Code';
        $message = 'Your verification code is: ' . $verification_code;
        $headers = 'From: mail.eloiacs@gmail.com' . "\r\n" .
            'Reply-To: mail.eloiacs@gmail.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        if (mail($email, $subject, $message, $headers)) {
            // Save email and employee_id in the session
            $row = mysqli_fetch_assoc($result);
            $_SESSION['email'] = $email;
            $_SESSION['employee_id'] = $employee_id;

            echo "success";
        } else {
            echo "Message could not be sent.";
        }
    } else {
        echo "not_exists";
    }
}
?>
