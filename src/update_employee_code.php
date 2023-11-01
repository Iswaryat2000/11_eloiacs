<?php
include "../connection.php";

if (isset($_POST['employeeName']) && isset($_POST['newCode'])) {
    $employeeName = $_POST['employeeName'];
    $newCode = $_POST['newCode'];

    // Start a transaction
    mysqli_begin_transaction($conn);

    // Update the employee code in the employee_data table
    $updateEmployeeSql = "UPDATE employee_data SET CODE = ? WHERE NAME = ?";
    $stmtEmployee = mysqli_prepare($conn, $updateEmployeeSql);

    if ($stmtEmployee) {
        mysqli_stmt_bind_param($stmtEmployee, "ss", $newCode, $employeeName);

        if (mysqli_stmt_execute($stmtEmployee)) {
            // Update the code in the usertable by email
            $updateUserSql = "UPDATE usertable SET NAME = ? WHERE email = (SELECT email FROM employee_data WHERE NAME = ?)";
            $stmtUser = mysqli_prepare($conn, $updateUserSql);

            if ($stmtUser) {
                mysqli_stmt_bind_param($stmtUser, "ss", $newCode, $employeeName);

                if (mysqli_stmt_execute($stmtUser)) {
                    // Commit the transaction
                    mysqli_commit($conn);

                    // Send a success message as the response
                    echo "Employee CODE updated successfully to " . $newCode;
                } else {
                    // Rollback the transaction
                    mysqli_rollback($conn);
                    echo "Error updating usertable CODE: " . mysqli_error($conn);
                }

                mysqli_stmt_close($stmtUser);
            } else {
                // Rollback the transaction
                mysqli_rollback($conn);
                echo "Error preparing usertable statement: " . mysqli_error($conn);
            }
        } else {
            // Rollback the transaction
            mysqli_rollback($conn);
            echo "Error updating employee CODE: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmtEmployee);
    } else {
        // Rollback the transaction
        mysqli_rollback($conn);
        echo "Error preparing statement: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    echo "Invalid request";
}
?>
