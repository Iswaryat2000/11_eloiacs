<?php
// Include your database connection script
include "../connection.php";

if (isset($_POST['check_icon_approve'])) {
    // Handle the "Approve" button click
    $newStatus = "Approved";
    $empId = $_POST['check_icon_approve'];

    // Fetch data for email notification
 $sql_approve_email = "SELECT `EMP_ID`, `EMPLOYEE_NAME`, `DEPARTMENT`, `EMAIL_EMP`, `FROM_DATE`, `TO_DATE`, `FULL_HALF_DAY`, `FISRT_SECOND_OFF`, `MESSAGE`, `STATUS_LEAVE` FROM `time_off_tracking` WHERE ID = '$empId'";
$result = $conn->query($sql_approve_email);


    if ($row = $result->fetch_assoc()) {
        $employeeName = $row['EMPLOYEE_NAME'];
        $message = $row['MESSAGE'];
        $sentmail = $row['EMAIL_EMP'];

        // Create the email headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: mail.eloiacs@gmail.com' . "\r\n";

        // Create the email content
        $subject = 'Leave Request Approved';
        $greeting = 'Hi, ' . $employeeName . ' your leave requested was approved by the Admin';

        $button = '<a href="https://acsteck.online/time_off.php"><button>Click here to go to our admin page</button></a>';
        
        $emailContent = $greeting . '<br><br>' . $button;

        // Send the email using the mail() function
        if (mail($sentmail, $subject, $emailContent, $headers)) {
            echo "<script>
                alert('Leave request approved successfully! Email sent to $sentmail.');
                window.location.href = '../time_off.php';
                </script>";
        } else {
            echo 'Email could not be sent.';
        }
    } else {
        echo "Error: Could not fetch data for email notification.";
    }

    // Update the status to "Approved"
    $sql = "UPDATE time_off_tracking SET STATUS_LEAVE = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newStatus, $empId);

    if ($stmt->execute()) {
        echo "success"; // Return success to the frontend
    } else {
        echo "error";
    }


    $stmt->close();
}



if (isset($_POST['mark_icon_reject'])) {
    // Handle the "Reject" button click
    $newStatus = "Rejected";
    $empId = $_POST['mark_icon_reject'];

    // Fetch data for email notification
    $sql_reject_email = "SELECT `EMP_ID`, `EMPLOYEE_NAME`, 'DEPARTMENT', `EMAIL_EMP`, `FROM_DATE`, `TO_DATE`, `FULL_HALF_DAY`, `FISRT_SECOND_OFF`, `MESSAGE`, `STATUS_LEAVE` FROM `time_off_tracking` WHERE ID = '$empId'";
    $result = $conn->query($sql_reject_email);

    if ($row = $result->fetch_assoc()) {
        $employeeName = $row['EMPLOYEE_NAME'];
        $message = $row['MESSAGE'];
        $sentmail = $row['EMAIL_EMP'];

        // Create the email headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: mail.eloiacs@gmail.com' . "\r\n";

        // Create the email content
        $subject = 'Leave Request Rejected';
        $greeting = 'Hi, ' . $employeeName . ' your leave requested was rejected by the Admin';

        $button = '<a href="https://eloiacs.com/"><button>Click here to go to our admin page</button></a>';

        $emailContent = $greeting . '<br><br>' . $button;

        // Send the email using the mail() function
        if (mail($sentmail, $subject, $emailContent, $headers)) {
            echo "<script>
                alert('Leave request rejected successfully! Email sent to $sentmail.');
                window.location.href = '../time_off.php';
                </script>";
        } else {
            echo 'Email could not be sent.';
        }
    } else {
        echo "Error: Could not fetch data for email notification.";
    }

    // Update the status to "Rejected"
    $sql = "UPDATE time_off_tracking SET STATUS_LEAVE = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newStatus, $empId);

    if ($stmt->execute()) {
        echo "success"; // Return success to the frontend
    } else {
        echo "error";
    }

    $stmt->close();
}
?>
