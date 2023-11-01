<?php
include "../includes/connection.php";
$successMessage = "";

        if (isset($_POST['clock_in_btn'])) {
            $employeeName = $_POST['EMPname'];
            $employeeCode = $_POST['EMPcode'];
            date_default_timezone_set('Asia/Kolkata');
            $currentTime = date('h:i a');
            $currentDate = date('Y-m-d');
            $insert_query = "INSERT INTO tl_attendance (EMPNAME, EMPCODE, CRNT_DATE, CLOCKIN) VALUES ('$employeeName', '$employeeCode', '$currentDate', '$currentTime')";
            
            if (mysqli_query($conn, $insert_query)) {
                $_SESSION['hasClockedIn'] = true;
                $_SESSION['clkintime'] = $currentTime;
                $successMessage = "Clock In Successful!";
                echo "<script>window.location.href = '../dashboard.php';</script>";
            } else {
                echo "Error: " . mysqli_error($conn);
                $successMessage = "Clock In Failed!";
                header("Location: ../error.php");
            }
            
            // Use JavaScript to display the success message in an alert
            echo "<script>alert('$successMessage');</script>";

        }

        if (isset($_POST['clock_out_btn'])) {
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
                header("Location: ../error.php");
            }
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
                header("Location: ../error.php");
            exit;
            }    

?>
