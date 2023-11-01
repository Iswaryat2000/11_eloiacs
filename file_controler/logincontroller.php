<?php
include "../connection.php";

if (isset($_POST['update_Password'])) {
    $email_frgtpassword = $_POST['email'];
    $employee_id_frgtpassword = $_POST['employee_id'];
    $code = $_POST['code']; // Assuming you receive the code from the form
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmpassword'];

    // Check if the user exists with the provided email and employee ID
    $sql_frgt = "SELECT code, status FROM usertable WHERE name = '$employee_id_frgtpassword' AND email = '$email_frgtpassword'";
    $result = mysqli_query($conn, $sql_frgt);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $verificationCodeInDb = $row['code'];
        $status = $row['status'];

        if ($verificationCodeInDb === $code && $status === "Notverified") {
            // Verify that the password and confirm password match
            if ($password === $confirmPassword) {
                $status_verified = "verified";
                // Update the user's password and status in the database
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $updateQuery = "UPDATE `usertable` SET `password`='$hashedPassword', `status`='$status_verified', `code`=$code WHERE `email`='$email_frgtpassword' AND `name`='$employee_id_frgtpassword'";

                if (mysqli_query($conn, $updateQuery)) {
                    // Password updated successfully
                    $message = "Password updated successfully.";
                    // Redirect to index.php after successful update
                    echo "<script>alert('$message'); window.location.href = '../index.php';</script>";
                    exit(); // Exit to prevent further execution
                } else {
                    // Handle database error
                    $message = "Database error: " . mysqli_error($conn);
                }
            } else {
                // Passwords do not match
                $message = "Password and confirm password do not match.";
            }
        } else {
            // Invalid verification code or status
            $message = "Invalid verification code or status.";
        }
    } else {
        // Handle database error
        $message = "Database error: " . mysqli_error($conn);
    }

    // Redirect to error.php on failure
    echo "<script>alert('$message'); window.location.href = '../error.php';</script>";
    exit(); // Exit to prevent further execution
}
?>