<?php
// Include your database connection script
include "connection.php";

if (isset($_POST['request_leave'])) {
    $empName = $_POST['from_name'];
    $emp_id = $_POST['from_id'];
    $emp_mail = $_POST['from_email'];
    $fromDate = $_POST['from_date'];
    $toDate = $_POST['to_date'];
    $leaveType = $_POST['form_leave_option'];
    $full_half_Day = isset($_POST['full_halfday']) ? $_POST['full_halfday'] : '0';
    $message = $_POST['text_area'];
    $department = $_POST['form_department'];
    $status_leave = "Pending";

    // Calculate the difference between the two dates
    $date1 = new DateTime($fromDate);
    $date2 = new DateTime($toDate);
    $interval = $date1->diff($date2);

    // Get the total number of days from the interval
    $totaldays = $interval->days;

    // Set FISRT_SECOND_OFF based on FULL_HALF_DAY
    if ($full_half_Day === 'Full Day') {
        $first_secondHalf = '0';
    } else {
        // You can set $first_secondHalf to the appropriate value here
        // For now, I'll assume it's coming from a form field named 'first_secondary_half'
        $first_secondHalf = isset($_POST['first_secondary_half']) ? $_POST['first_secondary_half'] : '';
    }

    // Prepare and execute an SQL query to insert the data into the database
    $sql = "INSERT INTO time_off_tracking (EMP_ID, EMPLOYEE_NAME, EMAIL_EMP, FROM_DATE, TO_DATE, FULL_HALF_DAY, FISRT_SECOND_OFF, MESSAGE, STATUS_LEAVE, DEPARTMENT)
            VALUES ('$emp_id', '$empName', '$emp_mail', '$fromDate', '$toDate', '$full_half_Day', '$first_secondHalf', '$message', '$status_leave', '$department')"; // Added single quotes around $department

    // Execute the SQL query here
    if (mysqli_query($conn, $sql)) {
        $sentmail = "epubeloiacsacswfo2@gmail.com";

        // Create the email headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: mail.eloiacs@gmail.com' . "\r\n";

        // Create the email content
        $subject = 'Leave Request Submitted';
        $greeting = 'Hi,<br>' . $empName . ' has requested a leave (' . $leaveType . ') ';
        $leaveInfo = 'From: ' . $fromDate . '- ' . $toDate . '';
        $userMessage = 'because of ' . $message;

        // Add "Approve" and "Reject" buttons with links
        $approveLink = 'https://acsteck.online/src/approve_leave.php?id=' . mysqli_insert_id($conn); // Replace with your actual approve script URL
        $rejectLink = 'https://acsteck.online/src/reject_leave.php?id=' . mysqli_insert_id($conn); // Replace with your actual reject script URL
        $approveButton = '<a href="' . $approveLink . '"><button style="background-color: #4CAF50; color: white;">Approve</button></a>';
        $rejectButton = '<a href="' . $rejectLink . '"><button style="background-color: #f44336; color: white;">Reject</button></a>';
        
        $button = 'Click the buttons below to approve or reject the leave request:<br>' . $approveButton . ' ' . $rejectButton;

        $emailContent = $greeting . $leaveInfo . '<b> (' . $totaldays . ' )</b> <br>' . $userMessage . '<br><br>' . $button;

        // Send the email using the mail() function
        if (mail($sentmail, $subject, $emailContent, $headers)) {
            echo "<script>
                alert('Leave request submitted successfully! Email sent to $sentmail.');
                window.location.href = '../src/timetracking.php';
                </script>";
        } else {
            echo 'Email could not be sent.';
        }
    } else {
        // Error occurred while inserting data
        echo "Error: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
