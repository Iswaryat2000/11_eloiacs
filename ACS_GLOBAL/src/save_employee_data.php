<?php
// Include your database connection code (connection.php)
include "../connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['save_btn_nn'])) {
        $projectID = mysqli_real_escape_string($conn, $_POST['projectID']);
        $codeArray = $_POST['code'];
        $nameArray = $_POST['name'];
        $departmentArray = $_POST['department'];
        $prodQcArray = $_POST['prod_qc'];
        $completedArray = $_POST['completed'];
        $qcTargetArray = isset($_POST['qc_target']) ? $_POST['qc_target'] : array(); // Default to an empty array
        $tlstatusarray = $_POST['tl_status'];

        // Loop through the arrays and insert or update each record in the "new" table
        for ($i = 0; $i < count($codeArray); $i++) {
            $code = mysqli_real_escape_string($conn, $codeArray[$i]);
            $name = mysqli_real_escape_string($conn, $nameArray[$i]);
            $department = mysqli_real_escape_string($conn, $departmentArray[$i]);
            $prodQcValue = mysqli_real_escape_string($conn, $prodQcArray[$i]);
            $completedValue = mysqli_real_escape_string($conn, $completedArray[$i]);
            $qcTargetValue = isset($qcTargetArray[$i]) ? mysqli_real_escape_string($conn, $qcTargetArray[$i]) : null;

            // Check if a record with the same criteria already exists
            $checkSql = "SELECT * FROM new WHERE projectid = '$projectID' AND employeename = '$name' AND department = '$department' AND prod_qc = '$prodQcValue'";
            $checkResult = mysqli_query($conn, $checkSql);

            if (mysqli_num_rows($checkResult) > 0) {
                // Update existing record
                $updateSql = "UPDATE new SET completed = '$completedValue', qc_target = '$qcTargetValue' WHERE projectid = '$projectID' AND employeename = '$name' AND department = '$department' AND prod_qc = '$prodQcValue'";

                if (mysqli_query($conn, $updateSql)) {
                    // Update successful
                    echo '<script>
                    alert("Update successful");
                    window.location.href = "tl_work.php";
                    </script>';
                } else {
                    // Update failed
                    echo '<script>
                    alert("Update failed");
                    window.location.href = "error.php";
                    </script>';
                }
            } elseif ($prodQcValue !== "SELECT PRO TYPE") {
                // Insert new record
               $current_date = date('Y-m-d');

                $insertSql = "INSERT INTO new (date, projectid, employeeid, employeename, department, completed, qc_target, prod_qc, status) VALUES ('$current_date','$projectID', '$code', '$name', '$department', '$completedValue', '$qcTargetValue', '$prodQcValue', '$tlstatusarray')";

                if (mysqli_query($conn, $insertSql)) {
                    // Insertion successful
                    echo '<script>
                    alert("Insertion successful");
                    window.location.href = "tl_work.php";
                    </script>';
                } else {
                    // Insertion failed
                    echo '<script>
                    alert("Insertion failed");
                    window.location.href = "error.php";
                    </script>';
                }
            }
        }
    } else {
        echo 'Invalid data.';
    }
} else {
    echo 'Invalid request.';
}
?>
