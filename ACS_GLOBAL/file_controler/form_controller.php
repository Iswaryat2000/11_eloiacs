<?php include_once "../includes/login_access.php"; 

date_default_timezone_set('Asia/Kolkata');
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s'); // Define $currentTime here

if (isset($_SESSION['hasClockedIn'])) {
    $hasClockedIn = $_SESSION['hasClockedIn'];
    $clkintime = $_SESSION['clkintime'];
} else {
    $hasClockedIn = false;
    if (isset($_COOKIE['hasClockedIn']) && $_COOKIE['hasClockedIn'] === 'true') {
        $hasClockedIn = true;
    }
    // Check if the employee has already clocked in for the day
    $select_query = "SELECT CLOCKIN FROM tl_attendance WHERE EMPCODE = '$employeeCode' AND CRNT_DATE = '$currentDate'";
    $result = mysqli_query($conn, $select_query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $clkintime = $row['CLOCKIN'];
        $hasClockedIn = true;
    } else {
        $hasClockedIn = false;
        $clkintime = "9.00 am";
    }

    $_SESSION['hasClockedIn'] = $hasClockedIn;
    $_SESSION['clkintime'] = $clkintime;
}



if (isset($_POST['clock_in_btn'])) {
    // Initialize variables
    $clockInBlocked = false;
    $hrApprove = 'approved'; // Default value for hr_approve
    $employeeName = $_POST['EMPname'];
    $employeeCode = $_POST['EMPcode'];
    date_default_timezone_set('Asia/Kolkata');
    $currentTime = date('h:i a');
    $currentDate = date('Y-m-d');
    // Check the previous record for the employee
    $sql = "SELECT `ID`, `warning_message`, `hr_approve` FROM `tl_attendance` WHERE `EMPNAME` = '$employeeName' ORDER BY `CRNT_DATE` DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $prevWarningMessage = $row['warning_message'];
        $prevHrApprove = $row['hr_approve'];
        $prevHrreject = 'rejected';
        $recordID = $row['ID'];

        if ($prevWarningMessage === '1' && (empty($prevHrApprove)|| ($prevHrreject ))) {
            // If warning_message is '1' and hr_approve is empty for the previous record,
            // set hr_approve to 'rejected' for the current record and update the previous record.
            $hrApprove = 'rejected';
            
            $update_previous_query = "UPDATE tl_attendance SET hr_approve = 'rejected' WHERE ID = $recordID";
            
            if (mysqli_query($conn, $update_previous_query)) {
                
            } else {
                echo "Error updating previous record: " . mysqli_error($conn);
            }

            $clockInBlocked = true;
        } elseif ($prevWarningMessage === '2' && $prevHrApprove === 'approved') {
            // If warning_message is '2' and hr_approve is 'approved' for the previous record,
            // insert the current record with hr_approve 'approved'.
            $hrApprove = 'approved';
        }
    }

    if (!$clockInBlocked) {
        // Insert data into tl_attendance table
        $insert_query = "INSERT INTO tl_attendance (EMPNAME, EMPCODE, CRNT_DATE, CLOCKIN, DEL_STATUS, warning_message, hr_approve) VALUES ('$employeeName', '$employeeCode', '$currentDate', '$currentTime', '0', '1', '$hrApprove')";

        if (mysqli_query($conn, $insert_query)) {
            // After successful clock-in, update the session variables
            $_SESSION['hasClockedIn'] = true;
            $_SESSION['clkintime'] = $currentTime;
            echo "<script>window.location.href = '../src/dashboard.php';</script>";
            $successMessage = "Clock In Successful!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}


if (isset($_POST['clock_out_btn'])) {
    // Handle clock-out
    $employeeCode = $_POST['EMPcode'];
    date_default_timezone_set('Asia/Kolkata');
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i a'); // Save the time in 12-hour format with AM/PM

    // Update the corresponding row for the employee in tl_attendance table
    $update_query = "UPDATE tl_attendance SET CLOCKOUT = '$currentTime', warning_message = '2' WHERE EMPCODE = '$employeeCode' AND CRNT_DATE = '$currentDate'";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['hasClockedIn'] = false;
        echo "<script>window.location.href = '../src/dashboard.php';</script>";
        $successMessage = "Clock Out Successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}


?>