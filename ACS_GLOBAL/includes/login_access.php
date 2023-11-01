<?php
session_start();

include "connection.php";
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
 header("Location:../index.php");
 exit;
}

$useremail = $_SESSION['email'];
$query = "SELECT u.name, u.place, u.position, u.status, e.CODE, e.NAME, e.DEPARTMENT, e.TEAMLEADER, e.BRANCH,e.project_department
 FROM usertable AS u
 LEFT JOIN employee_data AS e ON u.name = e.CODE
 WHERE u.email = ? AND u.DEL_STATUS = 1";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $useremail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($row = mysqli_fetch_assoc($result)) {
 $user_name = $row['name'];
 $user_place = $row['place'];
 $user_position = $row['position'];
 $user_status = $row['status'];
 $employeeCode = $row['CODE'];
 $employeeName = $row['NAME'];
 $EMP_DEPARTMENT = $row['DEPARTMENT'];
 $EMP_TEAMLEADER = $row['TEAMLEADER'];
 $EMP_BRANCH = $row['BRANCH'];
 $PROJECT_DEPARTMENT = $row['project_department'];
} 



else {
 $user_name = "Unknown";
 $user_place = "Unknown";
 $user_position = "Unknown";
 $user_status = "Unknown";
 $employeeCode = "Unknown";
 $employeeName = "Unknown";
 $EMP_DEPARTMENT = "Unknown";
 $EMP_TEAMLEADER = "Unknown";
 $EMP_BRANCH = "Unknown";
  $PROJECT_DEPARTMENT = "Unknown";
}
?>
