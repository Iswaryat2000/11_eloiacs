<?php
// Include your database connection script (includes/connection.php)
include "../includes/connection.php";

// Function to populate a dropdown with values from the database
function populateDropdown($sql, $columnName) {
    // Include your database connection
    include "../includes/connection.php";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $value = $row[$columnName];
            echo "<option value='$value'>$value</option>";
        }
    } else {
        echo "<option value=''>No options found</option>";
    }
    // Close the database connection
}

if (isset($_POST['save_project_assign'])) {
    // Handle the "save_project_assign" form submission
    foreach ($_POST['data'] as $id => $values) {
        $id = mysqli_real_escape_string($conn, $id);

        // Use null coalescing operator to set default values
        $status = mysqli_real_escape_string($conn, $values['STATUS'] ?? '');
        $File_target = mysqli_real_escape_string($conn, $values['File_target'] ?? '');
        $remark = mysqli_real_escape_string($conn, $values['REMARK'] ?? '');
        $qcCheck = mysqli_real_escape_string($conn, $values['QC_TARGET'] ?? '');
        $accounts = mysqli_real_escape_string($conn, $values['ACCOUNTS'] ?? '');
        $current_date = mysqli_real_escape_string($conn, $values['CURRENTDATE'] ?? '');
        $branch = mysqli_real_escape_string($conn, $values['BRANCH'] ?? '');

        // Add other fields you want to update
        $updateQuery = "UPDATE projects SET STATUS = '$status', File_target = '$File_target', REMARK = '$remark', QC_TARGET = '$qcCheck', ACCOUNTS = '$accounts', CURRENTDATE='$current_date', BRANCH='$branch' WHERE PROJECTID = '$id'";
        mysqli_query($conn, $updateQuery);
        echo "<script>alert('Updated Successfully')</script>";

        echo '<script> window.location.href = window.location.href;</script>';
    }
} else {
    echo '<script> window.location.href = error.php;</script>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle the "save_prod" form submission
    if (isset($_POST['save_prod'])) {
        // Handle "save_prod" form submission
        $target_prod = $_POST['new_target_PROD'];
        $projectids = $_POST['projectid_PROD'];
        $newCompletedValues = $_POST['new_completed_PROD'];
        $newPendingValues = $_POST['new_pending_PROD'];

        // Loop through the submitted data and update the records
        for ($i = 0; $i < count($projectids); $i++) {
            $projectid = $projectids[$i];
            $newCompleted = $newCompletedValues[$i];
            $newPending = $newPendingValues[$i];

            $update_query_prod = "UPDATE new SET completed = '$newCompleted', pending = '$newPending' WHERE projectid = '$projectid' AND employeename = '$employeeName'";

            if ($conn->query($update_query_prod) === TRUE) {
                echo "Changes saved successfully!";
                echo '<script>alert("Data saved successfully.");window.location.href = "../dashboard.php";</script>';
            } else {
                header("Location: ../error.php");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b272402e67.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  
    <link rel="stylesheet" href="../css/project_assignn.css">
    <link rel="stylesheet" href="../css/styless.css">
</head>
<style>body{width:99% !important;}


</style>
<?php

// Include the database connection
include_once "../includes/connection.php";




if (isset($_POST['update_btn'])) {
    // Get the selected values from the form
    $batchNumber = $_POST['batch'];
    $department = $_POST['dept'];
    $status = $_POST['stat'];

    // Build the SQL query to update the status
    $updateQuery = "UPDATE projects SET STATUS = '$status' WHERE OURBATCH = '$batchNumber' AND DEPARTMENT = '$department' AND TL_STATUS = 'Completed'";

    // Execute the query to update the status
    if (mysqli_query($conn, $updateQuery)) {
        echo "Status updated successfully in the projects table.";
        echo "<script>window.location.href = '../src/projectassign_demo.php';</script>";

    } else {
        echo "Error updating status: " . mysqli_error($conn);
    }
}
?>



<body>
 
   <!-- Header Section -->
   <?php require_once "../includes/header.php"; ?>
<!-- End Header Section -->
   <form method="post">
<div class="row" style="margin-top:7%">
<?php
// Include the database connection
include_once "../includes/connection.php";

// SQL query to select distinct batch numbers
$sql_batch_company = "SELECT DISTINCT OURBATCH FROM projects WHERE TL_STATUS = 'Completed'    AND STATUS != 'Completed'";
$result1 = mysqli_query($conn, $sql_batch_company);
?>

<div class="col-2">BATCH NUMBER:
    <select name="batch" id="batchNumber" class="form-control des" required>
        <option value="SELECT BATCH">Select batch</option>
        <option value="" disabled><hr></option>
        <?php
        while ($row = mysqli_fetch_assoc($result1)) {
            $batchnumber = $row['OURBATCH'];
            echo '<option value="' . $batchnumber . '">' . $batchnumber . '</option>';
        }
        ?>
    </select>
</div>


<div class="col-2">DEPARTMENT:
    <select name="dept" id="deptment" class="form-control" required>
        <option value="" disabled>Select Department</option>
        <option value="" disabled><hr></option>
    </select>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#batchNumber').on('change', function() {
        var batchNumber = $(this).val();
        if (batchNumber) {
            $.ajax({
                type: 'POST',
                url: 'get_departments.php', // Create a separate PHP file to handle the AJAX request
                data: {batchNumber: batchNumber},
                success: function(response) {
                    $('#deptment').html(response);
                }
            });
        } else {
            $('#deptment').html('<option value="" disabled>Select Department</option>');
        }
    });
});
</script>


<div class="col-2">STATUS:
    <select name="stat" id="status" class="form-control" required>
        <option value="" >Select status</option>
        <option value="" disabled><hr></option>
        <?php
        include_once "../includes/connection.php";

        $sql_stat_company = "SELECT `STATUS` FROM `department_company` WHERE `STATUS` IS NOT NULL";
        $result3 = mysqli_query($conn, $sql_stat_company);

        while ($row = mysqli_fetch_assoc($result3)) {
            $status = $row['STATUS'];
            echo '<option value="' . $status . '">' . $status . '</option>';
        }
        ?>
    </select>
</div>


<div class="col-1"><br><button name="update_btn" type="submit">Update</button>


    </div>




</div>
    </form>


<div class="container2">
        <form method="post">
            <div class="downlaod_pro_report">
                <button class="downlaod_pro_reportt" type="submit" name="save_project_assign">Save</button>
            </div>
            <div class="tableclass_pro_report">
            <div class="search-container">
        </div>   
        <div class="container2_table">            
        <table >
            <thead>
                <tr>               
                    <th>Project ID</th>
                    <th>Client Name</th>
                    <th>Our Batch</th>
                    <th style="display:none">Contact Person</th>
                    <th style="display:none">Batch Number</th>
                    <th >Work Cover Title</th> 
                    <th>ISBN Number</th>
                    <th>DEPARTMENT</th>
                    <th >Scope Of Work</th>
                    <th style="display:none">Type of Scope</th>
                    <th>Complexity</th>
                    <th>Unit</th>
                    <th style="display:none">Reference Number</th>
                    <th style="display:none">Project Type</th>
                    <th >Received Pages</th>
                    <th style="display:none">Blank Pages</th>
                    <th style="display:none">Worked Pages</th>
                    <th style="display:none">Cost / page</th>
                    <th>Received Date</th>
                    <th>Client Date</th>
                    <th style="display:none">Total Days</th>                    
                    <th >LOP Days</th>
                    <th >Due date</th>
                    <th style="display:none">Completion Date</th>
                    <th>Status</th>
                    <th>File Target</th>
                    <th>TL status</th>                    
                    <th>QC Checking</th>
                    <th>Accounts</th>
                    <th>Remark</th>  
                    <th style="display:none">Current Date</th>
                                                         
            </tr>
            </thead>
            <tbody>



<?php 
include "../connection.php";
$retrieve = "SELECT * FROM projects";
$result = mysqli_query($conn, $retrieve); 
while ($row = mysqli_fetch_assoc($result)) {
                         $id = $row['PROJECTID'];
                        $client_name = $row['CLIENTNAME'];
                        $ourbatch = $row['OURBATCH'];
                        $contact_person = $row['CONTACTPERSON'];
                        $batch_number = $row['BATCHNUMBER'];
                        $cover_title = $row['WORKTITLE'];
                        $isbn_number = $row['ISBNNUMBER'];
                        $department = $row['DEPARTMENT'];
                        $scope_of_work = $row['WORKSCOPE'];
                        $type_of_scope = $row['TYPESCOPE'];
                        $complexity = $row['COMPLEXITY'];
                        $unit = $row['UNIT'];
                        $ref_number = $row['REFERENCENUMBER'];
                        $project_type = $row['PROJECTTYPE'];
                        $received_pgs = $row['RECEIVEDPAGES'];  
                        $blank_pgs = $row['BLANKPAGES'];
                        $worked_pgs = $row['WORKEDPAGES'];
                        $cost_per_page = $row['COST'];
                        $total_cost = $row['TOTALCOST'];
                        $received_date = $row['RECEIVEDDATE'];
                        $vendor_tat = $row['DUEDATE'];
                        $total_days = $row['TOTALDAYS'];
                        $lop_days = $row['LOPDAYS'];
                        $our_tat= $row['OURTAT'];
                        $completion_data = $row['COMPLETIONDATE'];
                        $status = $row['STATUS'];
                        $File_target=$row['File_target'];
                        $remark = $row['REMARK'];
                        $tl = $row['TL_STATUS'];
                        // $qc = $row['QC'];
                        $qc_check = $row['QC_TARGET'];
                        $accounts = $row['ACCOUNTS'];
                        $current_date = date('Y-m-d');   
                       ?>                           
                        <tr class="employee-row">                         
                        <td><input type="text" name="data[<?php echo $id; ?>][PROJECTID]" value="<?php echo $id; ?>" readonly></td>
                        <td><input type="text" name="data[<?php echo $id; ?>][CLIENTNAME]" value="<?php echo $client_name; ?>"readonly></td>
                        <td><input type="text" name="data[<?php echo $id; ?>][OURBATCH]" value="<?php echo $ourbatch; ?>"readonly></td>


                        <td style="display:none"><input type="text" name="data[<?php echo $id; ?>][CONTACTPERSON]" value="<?php echo $contact_person; ?>"readonly></td>
                        <td style="display:none"><input type="text" name="data[<?php echo $id; ?>][BATCHNUMBER]" value="<?php echo $batch_number; ?>"readonly></td>
                        <td><input type="text" name="data[<?php echo $id; ?>][WORKTITLE]" value="<?php echo $cover_title; ?>"readonly></td>
                        <td><input type="text" name="data[<?php echo $id; ?>][ISBNNUMBER]" value="<?php echo $isbn_number; ?>"readonly></td>
                        <td><input type="text" name="data[<?php echo $id; ?>][DEPARTMENT]" value="<?php echo $department; ?>"readonly></td>
                        
                        
                        <td><input type="text" name="data[<?php echo $id; ?>][WORKSCOPE]" value="<?php echo $scope_of_work; ?>"readonly></td>
                        <td style="display:none"><input type="text" name="data[<?php echo $id; ?>][TYPESCOPE]" value="<?php echo $type_of_scope; ?>"readonly></td>
                        <td><input readonly type="text" name="data[<?php echo $id; ?>][COMPLEXITY]" value="<?php echo $complexity; ?>"></td>
                        <td><input readonly type="text" name="data[<?php echo $id; ?>][UNIT]" value="<?php echo $unit; ?>"></td>
                         <td style="display:none"><input type="text" name="data[<?php echo $id; ?>][REFERENCENUMBER]" value="<?php echo $ref_number; ?>"></td> 
                        <td style="display:none"><input type="text" name="data[<?php echo $id; ?>][PROJECTTYPE]" value="<?php echo $ref_number; ?>"></td>
                        <td><input type="text" class="receivedpages" name="data[<?php echo $id; ?>][RECEIVEDPAGES]" value="<?php echo $received_pgs; ?>"readonly></td>
                        <td style="display:none"><input type="text" class="blankpages" name="data[<?php echo $id; ?>][BLANKPAGES]" value="<?php echo $blank_pgs; ?>"></td>
                        <td style="display:none"><input type="text" class="workedpages" name="data[<?php echo $id; ?>][WORKEDPAGES]" value="<?php echo $worked_pgs; ?>"readonly></td>
                        <td style="display:none"><input type="text" class="cost" name="data[<?php echo $id; ?>][COST]" value="<?php echo $cost_per_page; ?>"></td>
                        <td><input type="text" class="receiveddate" name="data[<?php echo $id; ?>][RECEIVEDDATE]" value="<?php echo $received_date; ?>"readonly></td>
                        <td><input type="text" class="duedate" name="data[<?php echo $id; ?>][DUEDATE]" value="<?php echo $vendor_tat; ?>"readonly></td>
                        <td style="display:none"><input type="text" class="totaldays" name="data[<?php echo $id; ?>][TOTALDAYS]" value="<?php echo $total_days; ?>"readonly></td>
                        <td><input type="text" class="lopdays" name="data[<?php echo $id; ?>][LOPDAYS]" value="<?php echo $lop_days; ?>"readonly></td>
                        <td style="display:block"><input type="text" name="data[<?php echo $id; ?>][OURTAT]" value="<?php echo $our_tat; ?>"readonly></td>
                        <td style="display:none"><input type="text" class="completiondate" id="completionDate_<?php echo $id; ?>" name="data[<?php echo $id; ?>][COMPLETIONDATE]" value="<?php echo $completion_data; ?>"></td>    
                       
                       
                        <td> <select class="pro_report_select" name="data[<?php echo $id; ?>][STATUS]" onchange="updateCompletionDate(this)">
                        <option value=""></option>            
                        <option value="Assign" <?php if ($status === 'Assign') echo 'selected'; ?>>Assign</option>
                        <option value="Completed" <?php if ($status === 'Completed') echo 'selected'; ?>>Completed</option>
                        <option value="On Hand" <?php if ($status === 'On Hand') echo 'selected'; ?>>On Hand</option>
                        <option value="Pending" <?php if ($status === 'Pending') echo 'selected'; ?>>Pending</option>
                        </select></td>  
<!-- 
                        <td>
    <select class="pro_report_select" name="data[<?php echo $id; ?>][STATUS]" onchange="updateCompletionDate(this)">
        <option value="" name="data[<?php echo $id; ?>][STATUS]" disabled selected hidden>Select Designation</option>
        <?php
        include_once "../includes/connection.php";
        $sql_status = "SELECT `STATUS`, `DEPARTMENT_CODE` FROM `department_company`";
        $result = mysqli_query($conn, $sql_status);
        while ($row = mysqli_fetch_assoc($result)) {
            $selected = ($row['STATUS'] === $status) ? 'selected' : '';
            echo '<option data-code="' . $row['DEPARTMENT_CODE'] . '" ' . $selected . '>' . $row['STATUS'] . '</option>';
        }
        ?>
    </select>
</td> -->





                        <td><input type="text" class="cost" name="data[<?php echo $id; ?>][File_target]" value="<?php echo $File_target; ?>"></td> 
                        <td><input type="text" name="data[<?php echo $id; ?>][TL_STATUS]" value="<?php echo $tl; ?>"readonly></td>
                        <!-- <td><input type="text" name="data[<php echo $id; ?>][QC]" value="<php echo $qc; ?>"></td> -->
                        <td><input type="text" name="data[<?php echo $id; ?>][QC_TARGET]" value="<?php echo $qc_check; ?>"></td>
                        <td><input type="text" name="data[<?php echo $id; ?>][ACCOUNTS]" value="<?php echo $accounts; ?>"></td>
                        <td><input type="text" name="data[<?php echo $id; ?>][REMARK]" value="<?php echo $remark; ?>"></td>
                        <td><input type="" name="data[<?php echo $id; ?>][CURRENTDATE]" value="<?PHP echo $current_date; ?>" style="display:none"readonly></td>
</tr>                 
<?php } ?>                
</tbody> 
</table> 
</div>

</div>
</form>

</body>









</html>