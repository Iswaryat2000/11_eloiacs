<?php
include_once "../includes/login_access.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['save_prod'])) {
        // Extract data for PROD
        $target_prod = $_POST['new_target_PROD'];
        $projectids = $_POST['projectid_PROD'];
        $newCompletedValues = $_POST['new_completed_PROD'];
        $newPendingValues = $_POST['new_pending_PROD'];
        $emp_statuses = $_POST['prod_empstatus'];

        for ($i = 0; $i < count($projectids); $i++) {
            $projectid = $projectids[$i];
            
            // Check if the keys exist in the arrays before accessing them
            if (isset($newCompletedValues[$i]) && isset($newPendingValues[$i]) && isset($emp_statuses[$i])) {
                $newCompleted = $newCompletedValues[$i];
                $newPending = $newPendingValues[$i];
                $emp_status = $emp_statuses[$i];
                
                $update_query_prod = "UPDATE new SET completed = '$newCompleted', pending = '$newPending', status = '$emp_status' WHERE projectid = '$projectid' AND employeename = '$employeeName' AND prod_qc = 'PROD'";
    
                if ($conn->query($update_query_prod) === TRUE) {
                    echo "Changes saved successfully!";
                    echo '<script>alert("Data saved successfully.");
                    window.location.href = "../src/dashboard.php";</script>';
                } 
                else {
                    header("Location: ../src/error.php");
                    exit;
                }
            } else {
                echo "Error: Required keys not found in new_completed_PROD, new_pending_PROD, or prod_empstatus.";
            }
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["save_qc"])) {
        // Handle "save_qc" form submission
        foreach ($_POST["save_qc"] as $projectid => $value) {
            $new_target_QC = $_POST["new_target_QC"][$projectid];
            $new_completed_QC = $_POST["new_completed_QC"][$projectid];
            $new_pending_QC = $_POST["new_pending_QC"][$projectid];
            $emp_status_qc = $_POST['qc_empstatus'];
    
            $update_query_QC = "UPDATE new SET qc_target = '$new_target_QC', completed = '$new_completed_QC', pending = '$new_pending_QC', status = '$emp_status_qc' WHERE projectid = '$projectid' AND employeename = '$employeeName' AND prod_qc = 'QC'";
    
    
            if ($conn->query($update_query_QC) === TRUE) {
                echo '<script>alert("Data saved successfully.");window.location.href = "../src/dashboard.php";</script>';
            } else {
                header("Location: ../src/error.php");
                exit;
            }
        }
    }

    else {
    header("location:../src/dashboard.php");
    exit;
    }

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
    
            $button = '<a href="https://acsteck.online/src/time_off.php"><button>Click here to go to our admin page</button></a>';
            
            $emailContent = $greeting . '<br><br>' . $button;
    
            // Send the email using the mail() function
            if (mail($sentmail, $subject, $emailContent, $headers)) {
                echo "<script>
                    alert('Leave request approved successfully! Email sent to $sentmail.');
                    window.location.href = '../src/time_off.php';
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
                    window.location.href = '../src/time_off.php';
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
}
    ?>