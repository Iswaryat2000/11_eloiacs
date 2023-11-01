<?php
include "../connection.php";

if (isset($_GET['id'])) {
    $empId = $_GET['id'];
    
    // Fetch data for email notification
    $sql_approve_email = "SELECT `EMPLOYEE_NAME`, `EMAIL_EMP` FROM `time_off_tracking` WHERE ID = ?";
    $stmt = $conn->prepare($sql_approve_email);
    $stmt->bind_param("i", $empId);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $employeeName = $row['EMPLOYEE_NAME'];
            $sentmail = $row['EMAIL_EMP'];

            // Create the email headers
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: mail.eloiacs@gmail.com' . "\r\n";

            // Create the email content
            $subject = 'Leave Request Approved';
            $message = 'Hi ' . $employeeName . ',<br>Great news! Your leave request has been authorized by HR. Enjoy your well-deserved break!';

            // Send the email using the mail() function
            if (mail($sentmail, $subject, $message, $headers)) {
                // Update the status to "Approved"
                $newStatus = "Approved";
                $sql_update_status = "UPDATE time_off_tracking SET STATUS_LEAVE = ? WHERE ID = ?";
                $stmt_update_status = $conn->prepare($sql_update_status);
                $stmt_update_status->bind_param("si", $newStatus, $empId);
                
                if ($stmt_update_status->execute()) {
                    // Redirect back to the previous page
                    header("Location: {$_SERVER['HTTP_REFERER']}");
                    exit();
                } else {
                    echo "Error: Leave request approval failed.";
                }

                $stmt_update_status->close();
            } else {
                echo "Error: Email could not be sent.";
            }
        } else {
            echo "Error: No data found for email notification.";
        }
    } else {
        echo "Error: SQL query execution failed.";
    }

    $stmt->close();
} else {
    echo "Error: Invalid request.";
}
?>
