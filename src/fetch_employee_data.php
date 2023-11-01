<?php
// Include your database connection code (connection.php)
include "../includes/connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['projectID']) && isset($_POST['department'])) {
        $projectID = $_POST['projectID'];
        $department = $_POST['department'];
        $tlStatus = $_POST['tlStatus'];

        // Create an SQL query to fetch data from employee_data
        $sql_employee_data = "SELECT `ID`, `CODE`, `NAME`, `DEPARTMENT`, `WORKNATURE`, `JOININGDATE`, `BRANCH`, `BASIC`, `BANKNAME`, `ACCOUNTNO`, `IFSCCODE`, `SALARYACCOUNT`, `ESI_EPF`, `ESINO`, `EPFNO`, `STATUS`, `EXITDATE`, `MOBILE`, `DELSTATUS`, `TEAMLEADER`, `Livechange_date`, `userdeleted`, `Project_department`, `EMAIL`, `edited_by`
            FROM `employee_data`
            WHERE Project_department = '$department' AND STATUS != 'Exit'";

        // Execute the query for employee_data
        $result_employee_data = mysqli_query($conn, $sql_employee_data);

        // Create an SQL query to fetch data from acs_employee_data
        $sql_acs_employee_data = "SELECT `ID`, `CODE`, `NAME`, `DEPARTMENT`, `WORKNATURE`, `JOININGDATE`, `BRANCH`, `BASIC`, `BANKNAME`, `ACCOUNTNO`, `IFSCCODE`, `SALARYACCOUNT`, `ESI_EPF`, `ESINO`, `EPFNO`, `STATUS`, `EXITDATE`, `MOBILE`, `DELSTATUS`, `TEAMLEADER`, `Livechange_date`, `userdeleted`, `Project_department`, `EMAIL`, `edited_by`
            FROM `acs_employee_data`
            WHERE Project_department = '$department' AND STATUS != 'Exit'";

        // Execute the query for acs_employee_data
        $result_acs_employee_data = mysqli_query($conn, $sql_acs_employee_data);

        if (($result_employee_data && mysqli_num_rows($result_employee_data) > 0) || ($result_acs_employee_data && mysqli_num_rows($result_acs_employee_data) > 0)) {
            echo '<form action="save_employee_data.php" method="POST">';
            echo '<input type="hidden" name="projectID" value="' . $projectID . '">'; // Add projectID as a hidden input
            echo '<table class="table1" border="1px">';
            echo '<thead>';
            echo '<tr>';
            echo '<th hidden>Code</th>';
            echo '<th hidden>STATUS</th>';
            echo '<th>Employee Name</th>';
            echo '<th>Project Department</th>';
            // Add headers for additional fields you want to display
            echo '<th>File Target</th>';
            echo '<th>QC Target</th>';
            echo '<th>PROD / QC</th>';
            echo '<th>PROD COMPLETED</th>';
            echo '<th>QC COMPLETED</th>';
            echo '<th>ACTION</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $selectId = 0; // Initialize selectId variable


            while ($row = mysqli_fetch_assoc($result_employee_data)) {
                $selectId++; // Increment selectId for each row
                echo '<tr>';
                
                echo '<td hidden><input class="tl_work-input type="text" name="code[]" value="' . $row['CODE'] . '" readonly></td>';
                echo '<td hidden><input class="tl_work-input type="text" name="tl_status[]" value="' . $tlStatus . '" readonly></td>';
                echo '<td><input class="tl_work-input type="text" name="name[]" value="' . $row['NAME'] . '" readonly></td>';

                echo '<td><input class="tl_work-input type="text" name="department[]" value="' . $row['Project_department'] . '" readonly></td>';
                $sql_projects = "SELECT File_target, STATUS, QC_TARGET FROM projects WHERE PROJECTID = '$projectID'";
                $result_projects = mysqli_query($conn, $sql_projects);

                if ($result_projects && mysqli_num_rows($result_projects) > 0) {
                    while ($row_projects = mysqli_fetch_assoc($result_projects)) {
                        echo '<td>' . $row_projects['File_target'] . '</td>';
                        echo '<td>' . $row_projects['QC_TARGET'] . '</td>';
                    }
                }

                // Check if there is a corresponding entry in the "new" table for this project and employee
                $employeeName = $row['NAME'];
                $newSql = "SELECT prod_qc, Completed, qc_target FROM new WHERE projectid = '$projectID' AND employeename = '$employeeName'";
                $newResult = mysqli_query($conn, $newSql);
                $employeeData = mysqli_fetch_assoc($newResult);
                echo '<td>';
                ?>
                <select class="tl_work-select" name="prod_qc[]" readonly>
                    <?php
                    // Check if there's a value in the "new" table
                    if ($employeeData) {
                        $prodQcValue = $employeeData['prod_qc'];
                        $completedValue = $employeeData['Completed'];
                        $qcTargetValue = $employeeData['qc_target'];
                        echo '<option selected value="' . $prodQcValue . '">' . $prodQcValue . '</option>';
                        echo '<option disabled>------------</option>';
                        echo '<option value="SELECT PRO TYPE">SELECT PRO TYPE</option>';
                        echo '<option value="PROD">PRODUCTION</option>';
                        echo '<option value="QC">QC</option>';
                    } else {
                        // If there's no value, show the "SELECT PRO TYPE" option
                        echo '<option selected value="SELECT PRO TYPE">SELECT PRO TYPE</option>';
                        echo '<option value="PROD">PRODUCTION</option>';
                        echo '<option value="QC">QC</option>';
                    }
                    ?>
                </select>
                <?php
                echo '</td>';

                // Display the employee's completed and QC completed values as input fields
                echo '<td><input class="tl_work-input type="text" name="completed[]" value="' . ($employeeData ? $completedValue : '') . '" readonly></td>';
                echo '<td><input class="tl_work-input type="text" name="qc_target[]" value="' . ($employeeData ? $qcTargetValue : '') . '" readonly></td>';

                // Add trash icon for deleting employees and update select option on icon click
                echo '<td class="action-buttons">';
                ?>
                <span class="delete-employee delete-employee-btn" data-employee-id="<?= $row['ID'] ?>" data-select-id="<?= $selectId ?>">
                    <i class="tl_work_trash fa-solid fa-trash"></i>
                </span>
                <?php
                echo '</td>';
                echo '</tr>';
            }

            while ($row = mysqli_fetch_assoc($result_acs_employee_data)) {
          
                $selectId++; // Increment selectId for each row
                echo '<tr>';
                
                echo '<td hidden><input class="tl_work-input type="text" name="code[]" value="' . $row['CODE'] . '" readonly></td>';
                echo '<td hidden><input class="tl_work-input type="text" name="tl_status[]" value="' . $tlStatus . '" readonly></td>';
                echo '<td><input class="tl_work-input type="text" name="name[]" value="' . $row['NAME'] . '" readonly></td>';

                echo '<td><input class="tl_work-input type="text" name="department[]" value="' . $row['Project_department'] . '" readonly></td>';
                $sql_projects = "SELECT File_target, STATUS, QC_TARGET FROM projects WHERE PROJECTID = '$projectID'";
                $result_projects = mysqli_query($conn, $sql_projects);

                if ($result_projects && mysqli_num_rows($result_projects) > 0) {
                    while ($row_projects = mysqli_fetch_assoc($result_projects)) {
                        echo '<td>' . $row_projects['File_target'] . '</td>';
                        echo '<td>' . $row_projects['QC_TARGET'] . '</td>';
                    }
                }

                // Check if there is a corresponding entry in the "new" table for this project and employee
                $employeeName = $row['NAME'];
                $newSql = "SELECT prod_qc, Completed, qc_target FROM new WHERE projectid = '$projectID' AND employeename = '$employeeName'";
                $newResult = mysqli_query($conn, $newSql);
                $employeeData = mysqli_fetch_assoc($newResult);
                echo '<td>';
                ?>
                <select class="tl_work-select" name="prod_qc[]" readonly>
                    <?php
                    // Check if there's a value in the "new" table
                    if ($employeeData) {
                        $prodQcValue = $employeeData['prod_qc'];
                        $completedValue = $employeeData['Completed'];
                        $qcTargetValue = $employeeData['qc_target'];
                        echo '<option selected value="' . $prodQcValue . '">' . $prodQcValue . '</option>';
                        echo '<option disabled>------------</option>';
                        echo '<option value="SELECT PRO TYPE">SELECT PRO TYPE</option>';
                        echo '<option value="PROD">PRODUCTION</option>';
                        echo '<option value="QC">QC</option>';
                    } else {
                        // If there's no value, show the "SELECT PRO TYPE" option
                        echo '<option selected value="SELECT PRO TYPE">SELECT PRO TYPE</option>';
                        echo '<option value="PROD">PRODUCTION</option>';
                        echo '<option value="QC">QC</option>';
                    }
                    ?>
                </select>
                <?php
                echo '</td>';

                // Display the employee's completed and QC completed values as input fields
                echo '<td><input class="tl_work-input type="text" name="completed[]" value="' . ($employeeData ? $completedValue : '') . '" readonly></td>';
                echo '<td><input class="tl_work-input type="text" name="qc_target[]" value="' . ($employeeData ? $qcTargetValue : '') . '" readonly></td>';

                // Add trash icon for deleting employees and update select option on icon click
                echo '<td class="action-buttons">';
                ?>
                <span class="delete-employee delete-employee-btn" data-employee-id="<?= $row['ID'] ?>" data-select-id="<?= $selectId ?>">
                    <i class="tl_work_trash fa-solid fa-trash"></i>
                </span>
                <?php
                echo '</td>';
                echo '</tr>';
            }





            echo '</tbody>';
            echo '</table>';
            echo '<button type="submit" class="save_tl_btn btn btn_primary" id="save-button" name="save_btn_nn">Save Changes</button>';
            echo '</form>';
        } else {
            echo 'No employees found.';
        }
    } else {
        echo 'Missing projectID or department.';
    }
} else {
    echo 'Invalid request.';
}
?>

<script>
$(document).on('click', '.delete-employee-btn', function() {
    var employeeId = $(this).data('employee-id');
    var selectId = $(this).data('select-id');
    var employeeRow = $(this).closest('tr'); 
    var selectElement = $('select[name="prod_qc[]"][readonly]').eq(selectId - 1); // Find the corresponding select element
    if (confirm("Are you sure you want to hide this employee?")) {
        employeeRow.hide();
        selectElement.val('SELECT PRO TYPE'); // Reset the select option to "SELECT PRO TYPE"
    }
});
</script>
