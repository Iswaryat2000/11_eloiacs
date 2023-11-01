<?php
include_once '../includes/header.php';
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
    <link rel="stylesheet" href="../css/dashboard_pro.css">
    <link rel="stylesheet" href="../css/styless.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<style>
    .hr_access { margin-top: 10%; text-align: center; padding: 0 9%; }
    table { width: 100%; }
    th, tr, td { border: 1px solid; }
    tbody {
  height: 100px; /* Adjust the height as needed */
  overflow-y: scroll;
}

/* Make the header row fixed */

th, td {
  padding: 8px;
  text-align: left;
  white-space: nowrap;
}

/* Add a border to the table */
table {
  border-collapse: collapse;
}

/* Add a border to the table cells */
th, td {
  border: 1px solid #ddd; /* Adjust the border color as needed */
}
</style>
<body>
    <div class="hr_access">
        <h5 class="head">Hr access page</h5>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['emp_id'])) {
                $emp_id = $_POST['emp_id'];
                // Update the database to set warning_message to "2" and hr_approve to "approved"
                $update_query = "UPDATE tl_attendance SET warning_message = '2', hr_approve = 'approved' WHERE EMPCODE = '$emp_id'";
                if ($conn->query($update_query) === TRUE) {
                    echo "Record updated successfully";
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            }
        }
        $fetch_value = "SELECT * FROM tl_attendance WHERE warning_message = '1' AND hr_approve = 'rejected'";
        $result = $conn->query($fetch_value);
        if ($result) {
            echo '<form method="post">';
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>EMP ID</th>';
            echo '<th>EMP NAME</th>';
            echo '<th>CURRENT Date</th>';
            echo '<th>Approve</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row["EMPCODE"] . '</td>';
                echo '<td>' . $row["EMPNAME"] . '</td>';
                echo '<td>' . $row["CRNT_DATE"] . '</td>';
                echo '<td>
                    <input type="hidden" name="emp_id" value="' . $row["EMPCODE"] . '">
                    <button type="submit">Approved</button>
                </td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</form>';
        } else {
            echo 'Error executing query: ' . $conn->error;
        }
        ?>
    </div>
</body>
