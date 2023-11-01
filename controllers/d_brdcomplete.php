<?php
session_start(); // Start a PHP session
include "../includes/connection.php"; // Include your database connection file
// require '../INCLUDES/vendor/autoload.php';
include_once "../includes/login_access.php";

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
?>