<?php
session_start(); // Start a PHP session
include "../includes/connection.php"; // Include your database connection file
require '../includes/vendor/autoload.php';
include_once "../includes/login_access.php";
use PhpOffice\PhpSpreadsheet\IOFactory;

$msg = ""; // Initialize the message variable

// Handle Excel file import
if (isset($_FILES['excel-file'])) {
    $file = $_FILES['excel-file']['tmp_name'];
    $extension = pathinfo($_FILES['excel-file']['name'], PATHINFO_EXTENSION);

    if ($extension == 'xlsx' || $extension == 'xls' || $extension == 'csv') {
        $obj = IOFactory::load($file);
        $data = $obj->getActiveSheet()->toArray(null, true, true, true);

        // Remove the first row (headers) from the data
        array_shift($data);

        foreach ($data as $row) {
            $PROJECTID = $row['A'];
            $BATCHNUMBER = $row['B'];
            $WORKTITLE = $row['C'];
            $ISBNNUMBER = $row['D'];
            $TYPESCOPE = $row['E'];
            $COMPLEXITY = $row['F'];
            $UNIT = $row['G'];
            $RECEIVEDPAGES = $row['H'];
            $RECEIVEDDATE = $row['I'];
            $DEPARTMENT = $row['J'];

            // Insert data into the projects table
            $insert_query = mysqli_query($conn, "INSERT INTO projects (PROJECTID, OURBATCH, WORKTITLE, ISBNNUMBER, TYPESCOPE, COMPLEXITY, UNIT, RECEIVEDPAGES, RECEIVEDDATE, DEPARTMENT) VALUES ('$PROJECTID', '$BATCHNUMBER', '$WORKTITLE', '$ISBNNUMBER', '$TYPESCOPE', '$COMPLEXITY','$UNIT', '$RECEIVEDPAGES', '$RECEIVEDDATE', '$DEPARTMENT')");

            if ($insert_query) {
                $msg = "File Imported Successfully!";
            } else {
                $msg = "Not Imported! Error: " . mysqli_error($conn);
            }
        }

        // Redirect to project.php after processing
        echo '<script>alert("File Imported Successfully!");window.location.href = "../src/project.php";</script>';
        exit();
    } else {
        $msg = "Invalid File!";
    }
}

// Handle client data submission (Form 1)
if (isset($_POST['submit_form_client'])) {
    $date = $_POST['date'];
    $clientname = $_POST['clientname'];
    $contactperson = $_POST['contactperson'];
    $department = $_POST['department'];
    $BatchNumber = $_POST['batchnumber'];

    $stmt = $conn->prepare("INSERT INTO client (batchnumber, date, clientname, contactperson, department) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $BatchNumber, $date, $clientname, $contactperson, $department);

    if ($stmt->execute()) {
        echo '<script>alert("Data saved successfully.");</script>';
        echo '<script>window.location.href = "../src/project.php";</script>';
    } else {
        echo 'Error: ' . $stmt->error; // Add error handling
    }

    $stmt->close();
    exit();
}

// Handle client data submission (Form 2)
if (isset($_POST['secondFormSubmit'])) {
    $clientname = $_POST['clientname'];
    $contactperson = $_POST['contactperson'];
    $department = $_POST['department'];

    $date = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO client (date, clientname, contactperson, department) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $date, $clientname, $contactperson, $department);

    if ($stmt->execute()) {
        echo '<script>alert("Client detail added successfully");</script>';
        echo '<script>window.location.href = "../src/project.php";</script>';
    } else {
        echo 'Error: ' . $stmt->error;
    }
}
if (isset($_POST['save_project_assign'])) {
    foreach ($_POST['data'] as $id => $values) {
        $id = mysqli_real_escape_string($conn, $id);

        // Use prepared statements to safely insert data
        $status = mysqli_real_escape_string($conn, $values['STATUS'] ?? '');
        $File_target = mysqli_real_escape_string($conn, $values['File_target'] ?? '');
        $remark = mysqli_real_escape_string($conn, $values['REMARK'] ?? '');
        $qcCheck = mysqli_real_escape_string($conn, $values['QC_TARGET'] ?? '');
        $accounts = mysqli_real_escape_string($conn, $values['ACCOUNTS'] ?? '');        
        $current_date = mysqli_real_escape_string($conn, $values['CURRENTDATE'] ?? '');
        $branch = mysqli_real_escape_string($conn, $values['BRANCH'] ?? '');

        // Add other fields you want to update
        $updateQuery = "UPDATE projects SET STATUS = ?, File_target = ?, REMARK = ?, QC_TARGET = ?, ACCOUNTS = ?, CURRENTDATE = ?, BRANCH = ? WHERE PROJECTID = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssssssss", $status, $File_target, $remark, $qcCheck, $accounts, $current_date, $branch, $id);

        if ($stmt->execute()) {
            echo '<script>alert("Data saved successfully.");window.location.href = "../src/project.php";</script>';
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
} else {
    echo 'Error: Data not saved.';
}

// Handle clock-in and clock-out
if (isset($_POST['clock_in_btn'])) {
    // Handle clock-in
    $employeeName = $_POST['EMPname'];
    $employeeCode = $_POST['EMPcode'];
    date_default_timezone_set('Asia/Kolkata');
    $currentTime = date('h:i a');
    $currentDate = date('Y-m-d');

    // Insert data into tl_attendance table
    $insert_query = "INSERT INTO tl_attendance (EMPNAME, EMPCODE, CRNT_DATE, CLOCKIN, DEL_STATUS) VALUES ('$employeeName', '$employeeCode', '$currentDate', '$currentTime', '0')";

    if (mysqli_query($conn, $insert_query)) {
        // After successful clock-in, update the session variables
        $_SESSION['hasClockedIn'] = true;
        $_SESSION['clkintime'] = $currentTime;
        echo "<script>window.location.href = '../dashboard.php';</script>";
        $successMessage = "Clock In Successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} elseif (isset($_POST['clock_out_btn'])) {
    // Handle clock-out
    $employeeCode = $_POST['EMPcode'];
    date_default_timezone_set('Asia/Kolkata');
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i a'); // Save the time in 12-hour format with AM/PM

    // Update the corresponding row for the employee in tl_attendance table
    $update_query = "UPDATE tl_attendance SET CLOCKOUT = '$currentTime' WHERE EMPCODE = '$employeeCode' AND CRNT_DATE = '$currentDate'";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['hasClockedIn'] = false;
        echo "<script>window.location.href = '../dashboard.php';</script>";
        $successMessage = "Clock Out Successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}


//---------------------------  project assign ----------------------------------

if (isset($_POST['save_project_assign'])) {
    foreach ($_POST['data'] as $id => $values) {
        $id = mysqli_real_escape_string($conn, $id);

        // Use null coalescing operator to set default values
        $status = mysqli_real_escape_string($conn, $values['STATUS'] ?? '');
        $File_target = mysqli_real_escape_string($conn, $values['File_target'] ?? '');
        $remark = mysqli_real_escape_string($conn, $values['REMARK'] ?? '');
        // $qc = mysqli_real_escape_string($conn, $values['QC'] ?? '');
        $qcCheck = mysqli_real_escape_string($conn, $values['QC_TARGET'] ?? '');
        $accounts = mysqli_real_escape_string($conn, $values['ACCOUNTS'] ?? '');        
        $current_date = mysqli_real_escape_string($conn, $values['CURRENTDATE'] ?? '');
        $branch = mysqli_real_escape_string($conn, $values['BRANCH'] ?? '');

        // Add other fields you want to update
        $updateQuery = "UPDATE projects SET STATUS = '$status', File_target = '$File_target', REMARK = '$remark', QC_TARGET = '$qcCheck', ACCOUNTS = '$accounts', CURRENTDATE='$current_date', BRANCH='$branch' WHERE PROJECTID = '$id'";
           mysqli_query($conn, $updateQuery);        
    
    echo '<script> window.location.href = window.location.href;</script>';
}} else {
    echo '<script> window.location.href = error.php;</script>';
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
// the employee project's for the each
if (isset($_POST['save_prod'])) {
    // Handle "save_prod" form submission
    $target_prod = $_POST['new_target_PROD'];
    $projectids = $_POST['projectid_PROD'];
    $newCompletedValues = $_POST['new_completed_PROD'];
    $newPendingValues = $_POST['new_pending_PROD'];
    $emp_status = $_POST['prod_empstatus'];

    // Loop through the submitted data and update the records
    for ($i = 0; $i < count($projectids); $i++) {
        $projectid = $projectids[$i];
        $newCompleted = $newCompletedValues[$i];
        $newPending = $newPendingValues[$i];

        $update_query_prod = "UPDATE new SET completed = '$newCompleted', pending = '$newPending', status = '$emp_status' WHERE projectid = '$projectid' AND employeename = '$employeeName' AND prod_qc = 'PROD'";

        if ($conn->query($update_query_prod) === TRUE) {
            echo "Changes saved successfully!";
            echo '<script>alert("Data saved successfully.");window.location.href = "../dashboard.php";</script>';
        } else {
            header("Location: ../error.php");
            exit;
        }
    }
}


// the employee qa's for the each

if (isset($_POST["save_qc"])) {
    // Handle "save_qc" form submission
    foreach ($_POST["save_qc"] as $projectid => $value) {
        $new_target_QC = $_POST["new_target_QC"][$projectid];
        $new_completed_QC = $_POST["new_completed_QC"][$projectid];
        $new_pending_QC = $_POST["new_pending_QC"][$projectid];
        $emp_status_qc = $_POST['qc_empstatus'];

        $update_query_QC = "UPDATE new SET qc_target = '$new_target_QC', completed = '$new_completed_QC', pending = '$new_pending_QC', status = '$emp_status_qc' WHERE projectid = '$projectid' AND employeename = '$employeeName' AND prod_qc = 'QC'";


        if ($conn->query($update_query_QC) === TRUE) {
            echo '<script>alert("Data saved successfully.");window.location.href = "../dashboard.php";</script>';
        } else {
            header("Location: ../error.php");
            exit;
        }
    }}
} else {
header("location:../error.php");
exit;
}



if (isset($_POST['save_emp_update'])) {
    $employee_codes = $_POST['employee_codes'];
  

    // Loop through the submitted employee codes
    foreach ($employee_codes as $key => $code) {
       
        $pro_department = $_POST['Project_department'][$code];
        $Teamleader_update = $_POST['teamleader'][$code];
        $position_update = $_POST['position_update'][$code];

        // Update user table
        $sql_user = "UPDATE `usertable` SET `position`='$position_update' WHERE name = '$code'";
        mysqli_query($conn, $sql_user);

        // Update employee data table
        $sql_employee = "UPDATE `employee_data` SET  `Project_department`='$pro_department', `TEAMLEADER`='$Teamleader_update' WHERE CODE = '$code'";
        mysqli_query($conn, $sql_employee);
    }

    // Redirect back to the employee list page with a success message
    echo "<script>alert('Data saved successfully');</script>";
    echo "<script>window.location.href = '../employee list.php';</script>";
    
    exit();
} else {
    echo "Data didn't save";
}


      // update_Password controlls

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
                    echo "<script>alert('$message'); window.location.href = 'index.php';</script>";
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
    echo "<script>alert('$message'); window.location.href = 'error.php';</script>";
    exit(); // Exit to prevent further execution
}




if (isset($_POST['check_icon_approve'])) {
    // Handle the "Approve" button click
    $newStatus = "Approved";
    $empId = $_POST['check_icon_approve'];

    // Fetch data for email notification
    $sql_approve_email = "SELECT `EMP_ID`, `EMPLOYEE_NAME`, `EMAIL_EMP`, `FROM_DATE`, `TO_DATE`, `FULL_HALF_DAY`, `FISRT_SECOND_OFF`, `MESSAGE`, `STATUS_LEAVE` FROM `time_off_tracking` WHERE ID = '$empId'";
    $result = $conn->query($sql_approve_email);

    if ($row = $result->fetch_assoc()) {
        $employeeName = $row['EMPLOYEE_NAME'];        
        $message = $row['MESSAGE'];
        $sentmail = $row['EMAIL_EMP'];

        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'mail.eloiacs@gmail.com'; // Your SMTP username
            $mail->Password = 'rwaitltasvwiopeq'; // Your SMTP password
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->SetFrom('mail.eloiacs@gmail.com', 'Eloiacs');
            $mail->addAddress($sentmail); // User's email

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Leave Request Approved';

            // Create variables for email content
            $greeting = 'Hi, ' . $employeeName . ' your leave requested was approved by the Admin';
           
            $button = '<a href="https://eloiacs.com/"><button>Click here to go to the our admin page</button></a>';

            // Concatenate the email content
            $emailContent = $greeting . '<br><br>' . $button;

            // Set the email body
            $mail->Body = $emailContent;

            $mail->send();
            echo "<script>
                alert('Leave request approved successfully! Email sent to $sentmail.');
                window.location.href = '../time_off.php';
                </script>";
        } catch (Exception $e) {
            echo 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
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
    $sql_reject_email = "SELECT `EMP_ID`, `EMPLOYEE_NAME`, `EMAIL_EMP`, `FROM_DATE`, `TO_DATE`, `FULL_HALF_DAY`, `FISRT_SECOND_OFF`, `MESSAGE`, `STATUS_LEAVE` FROM `time_off_tracking` WHERE ID = '$empId'";
    $result = $conn->query($sql_reject_email);

    if ($row = $result->fetch_assoc()) {
        $employeeName = $row['EMPLOYEE_NAME'];       
        $message = $row['MESSAGE'];
        $sentmail = $row['EMAIL_EMP'];

        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'mail.eloiacs@gmail.com'; // Your SMTP username
            $mail->Password = 'rwaitltasvwiopeq'; // Your SMTP password
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->SetFrom('mail.eloiacs@gmail.com', 'Eloiacs');
            $mail->addAddress($sentmail); // User's email

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Leave Request Rejected';

            // Create variables for email content
            $greeting = 'Hi, ' . $employeeName . ' your leave requested was rejected by the Admin';
           
            $button = '<a href="https://eloiacs.com/"><button>Click here to go to the our admin page</button></a>';

            // Concatenate the email content
            $emailContent = $greeting . '<br><br>' . $button;

            // Concatenate the email content
            $emailContent = $greeting . $leaveInfo . '<br>' . $userMessage . '<br><br>' . $button;

            // Set the email body
            $mail->Body = $emailContent;

            $mail->send();
            echo "<script>
                alert('Leave request rejected successfully! Email sent to $sentmail.');
                window.location.href = '../time_off.php';
                </script>";
        } catch (Exception $e) {
            echo 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
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
  
