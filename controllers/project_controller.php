<?php
session_start(); // Start a PHP session
include "../includes/connection.php"; // Include your database connection file
require '../includes/vendor/autoload.php';
include_once "../includes/login_access.php";



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
        echo '<script> window.location.href = error.php;</script>';
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
        echo '<script> window.location.href = error.php;</script>';
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
            echo '<script> window.location.href = error.php;</script>';
        }

        $stmt->close();
    }
} else {
    echo 'Error: Data not saved.';
}


if (isset($_POST['client_pro_entryform']))
{
        $ourbatchnumber = $_POST['ourbatchnumber'];
        $new_project_id = $_POST['new_project_id'];
        $department = $_POST['department'];
        $clientname = $_POST['clientname'];
        $contactperson = $_POST['contactperson'];
        $batchnumber = $_POST['batchnumber'];
        $worktitle = $_POST['worktitle'];
        $isbnnumber = $_POST['isbnnumber'];
        $complexity = $_POST['complexity'];
        $typescope = $_POST['typescope'];
        $refrencenumber = $_POST['refrencenumber'];
        $receivedpages = $_POST['receivedpages'];
        $receiveddate = $_POST['receiveddate'];
        $duedate = $_POST['duedate'];
        $ourtat = $_POST['ourtat'];
        $unit = $_POST['unit'];
        $lopdays = $_POST['lopdays'];
        
        $sql = "INSERT INTO projects (OURBATCH, PROJECTID, DEPARTMENT, CLIENTNAME, CONTACTPERSON, BATCHNUMBER, WORKTITLE, ISBNNUMBER, COMPLEXITY, TYPESCOPE, REFERENCENUMBER	, RECEIVEDPAGES, RECEIVEDDATE, DUEDATE, OURTAT, UNIT, LOPDAYS)
                VALUES ('$ourbatchnumber', '$new_project_id', '$department', '$clientname', '$contactperson', '$batchnumber', '$worktitle', '$isbnnumber', '$complexity', '$typescope', '$refrencenumber', '$receivedpages', '$receiveddate', '$duedate', '$ourtat', '$unit', '$lopdays')";
    
        if ($conn->query($sql) === TRUE) {
          echo '<script>alert("Data saved successfully.");
           window.location.href = "../src/project.php";</script>';
  
        } else {
            echo '<script> window.location.href = error.php;</script>';
        }
    }


?>