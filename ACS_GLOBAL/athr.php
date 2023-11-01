<?php
// Include the file with your database connection
include "connection.php";
$sql = "SELECT EMPNAME, EMPCODE, CRNT_DATE, CLOCKIN, CLOCKOUT, EMP_LEAVE, DEL_STATUS FROM tl_attendance WHERE warning_message ='1' AND hr_approve='rejected'";

$result = $conn->query($sql);

echo "<table>";
echo "<tr>";
echo "<th>EMPNAME</th>";
echo "<th>EMPCODE</th>";
echo "<th>CRNT_DATE</th>";
echo "<th>CLOCKIN</th>";
echo "<th>CLOCKOUT</th>";   
echo "<th>ACTION</th>";   
echo "</tr>";
// Fetch employee data and display warning messages
$result->data_seek(0); // Reset the result pointer to the beginning

while ($row = $result->fetch_assoc()) {
    $CLOCKOUT = $row["CLOCKOUT"];

    if (empty($CLOCKOUT)) {
        $CLOCKOUT = 'BLOCKED';
    }
    echo "<tr>";
    echo "<form action ='' method= 'post'>";
    echo "<td>" . $row["EMPNAME"] . "</td>";
    echo "<td>" . $row["EMPCODE"] . "</td>";
    echo "<td>" . $row["CRNT_DATE"] . "</td>";
    echo "<td>" . $row["CLOCKIN"] . "</td>";
    echo "<td>" . $CLOCKOUT . "</td>";
    echo "<td>";
    echo "<button type='submit' name='hr_status'>Approve</button>";
    echo "</td>";
    }
    echo "</form>";
    echo "</tr>";
echo "</table>";
// Close the database connection
$conn->close();
?>
