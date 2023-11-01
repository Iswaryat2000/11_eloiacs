<?php
include_once "../includes/connection.php";

if(isset($_POST['batchNumber'])){
    $batchNumber = $_POST['batchNumber'];

    $sql_depart_company = "SELECT DISTINCT DEPARTMENT FROM projects WHERE OURBATCH = '$batchNumber'";
    $result2 = mysqli_query($conn, $sql_depart_company);

    $options = '<option value="" disabled>Select Department</option>';

    while ($row = mysqli_fetch_assoc($result2)) {
        $department = $row['DEPARTMENT'];
        $options .= '<option value="' . $department . '">' . $department . '</option>';
    }

    echo $options;
}
