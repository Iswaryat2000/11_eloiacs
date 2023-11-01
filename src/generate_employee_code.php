<?php
include "../includes/connection.php";

$year = date('Y');

function generateEmpID($conn)
{
    $query = "SELECT MAX(CAST(SUBSTRING(CODE, 4) AS UNSIGNED)) AS max_code FROM employee_data WHERE CODE LIKE 'E . '$year'%'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $maxCode = $row['max_code'];

    if (empty($maxCode)) {
        $nextCodeNumber = 1;
    } else {
        $nextCodeNumber = $maxCode + 1;
    }

    $newCode = "E" . sprintf('%04d', $nextCodeNumber);

    return $newCode;
}

$newEmpCode = generateEmpID($conn);
echo $newEmpCode;
?>
