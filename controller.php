<?php
session_start(); // Start the session at the beginning

// Include your database connection here
include "includes/connection.php";

$message = isset($_SESSION['message']) ? $_SESSION['message'] : "";
$status = isset($_SESSION['status']) ? $_SESSION['status'] : "";


if (isset($_POST['check'])) {
    // Get the OTP entered by the user
    $enteredOTP = mysqli_real_escape_string($conn, $_POST['otp']);

    // Query to check if the entered OTP matches the one in the database
    $sql = "SELECT * FROM usertable WHERE code = '$enteredOTP'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        // OTP is verified
        $user = mysqli_fetch_assoc($result);

        if ($user['status'] === 'verified') {
            $message = "OTP has already been verified.";
            $status = "Already Verified";
        } else {
            // Update the user's status to 'verified'
            $updateStatusSQL = "UPDATE usertable SET status = 'verified' WHERE code = '$enteredOTP'";

            if (mysqli_query($conn, $updateStatusSQL)) {
                // Status updated successfully
                $message = "OTP verification successful. You can now log in.";
                $status = "Verification successful";
            } else {
                // Error occurred while updating the status
                $message = "Error updating status: " . mysqli_error($conn);
                $status = "Error";
            }
        }

        $_SESSION['message'] = $message;
        $_SESSION['status'] = $status;

        // Redirect to the appropriate page
        header("Location: index.php");
        exit();
    } else {
        // Incorrect OTP
        $message = "Incorrect OTP.";
        $status = "Error";
        $_SESSION['message'] = $message;
        $_SESSION['status'] = $status;

        // Redirect back to the form page with an error message
        header("Location: otp_verify.php?error=incorrect_otp");
        exit();
    }

    
} elseif (isset($_POST['otp_confirm'])) {
    $enteredOTP = mysqli_real_escape_string($conn, $_POST['otp']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "SELECT * FROM usertable WHERE code = '$enteredOTP' AND email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if ($user['status'] === 'Verified') {
            $message = "OTP has already been Verified.";
            $status = "Already Verified";
        } else {
            $updateStatusSQL = "UPDATE usertable SET status = 'verified' WHERE code = '$enteredOTP' AND email = '$email'";

            if (mysqli_query($conn, $updateStatusSQL)) {
                // Status updated successfully
                $message = "OTP verification successful.";
                $status = "Verification successful";
                $_SESSION['message'] = $message;
                $_SESSION['status'] = $status;

                // Display an alert and then redirect after the alert is closed
                echo '<script>
                alert("OTP verification successful. You can now log in.");
                window.location.href = "index.php";
                </script>';

                exit(); // Terminate the script
            } else {
                // Error occurred while updating the status
                $message = "Error updating status: " . mysqli_error($conn);
                $status = "Error";
                $_SESSION['message'] = $message;
                $_SESSION['status'] = $status;

                // Redirect to an error page
                header("Location: error_page.php");
                exit(); // Terminate the script after redirection
            }
        }

        $_SESSION['message'] = $message;
        $_SESSION['status'] = $status;

        // Redirect to the appropriate page
        header("Location: index.php");
        exit();
    } else {
        $message = "Incorrect OTP or email. Please try again.";
        $status = "Error";
        $_SESSION['message'] = $message;
        $_SESSION['status'] = $status;

        // Redirect back to the OTP confirmation page with an error message
        header("Location: otp_verify.php?error=incorrect_otp_email");
        exit();
    }
} 
?>