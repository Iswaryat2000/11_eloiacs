<?php
session_start();
// Include your database connection here
include "connection.php";

if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $department = $_POST['department'];
    $position = $_POST['position'];

    // Perform server-side validation
    if (empty($name) || empty($email) || empty($password) || empty($cpassword) || empty($department) || empty($position)) {
        echo "Please fill in all fields.";
        exit();
    }

    if ($password !== $cpassword) {
        echo "Passwords do not match.";
        exit();
    }

    // Check if the email already exists in the database
    $checkEmailSql = "SELECT email FROM usertable WHERE email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailSql);
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();

    if ($checkEmailResult->num_rows > 0) {
        // Email already exists, display an error message
        $message = 'Already Registered kindly check it ';
        echo json_encode(array('message' => $message));
        exit();
    }

    // Proceed with user registration
    $code = rand(999999, 111111);
    $status = "Notverified";

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO usertable (name, email, password, code, status, place, position) VALUES ('$name', '$email', '$hashedPassword', '$code', '$status', '$department', '$position')";
    
    if ($conn->query($sql) === TRUE) {
        // Store user email and name in the session
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;

        // Send verification email here, after inserting data into the database
        $to = $email;
        $subject = 'Verification Code';
        $message = "We appreciate your presence\n\nHere is the verification code for the registered email $email:\nVerification Code is: $code";

        $headers = 'From: epubeloiacsacswfo2@gmail.com' . "\r\n" .
            'Reply-To: mail.eloiacs@gmail.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        if (mail($to, $subject, $message, $headers)) {
            // Email sent successfully
            $message = 'User registered successfully! Check your email for the OTP.';
            $status = "success";
            echo json_encode(array('message' => $message, 'status' => $status));
        } else {
            // Email sending failed
            $message = 'Email could not be sent due to some Unexpected Error. Please Try Again later.';
            $status = "false";
            echo json_encode(array('message' => $message, 'status' => $status));
        }
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
}
?>