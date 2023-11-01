<!DOCTYPE html>
<html>
<head>
    <title>Employee Details</title>
</head>

<style>
    .whole_container { margin-top: 8%; }
</style>
<body >
<a href="newreport.php">Go back</a>x

<?php
include "../connection.php";

// Get the employeeid from the URL
if (isset($_GET['employeename'])) {
    $employeename = $_GET['employeename'];

    // Define the SQL query to fetch details for the selected employee
    $sql = "SELECT 
        n.`employeeName`,
        n.`date`,   
        n.`employeeid`, 
        n.`employeename`, 
        n.`department`, 
        n.`projectid`,     
        p.`WORKTITLE` AS project_title,  
        n.`target`, 
        n.`pending`, 
        n.`completed`, 
        p.`receivedpages` AS totalpages, 
        n.`status`, 
        n.`qc_target`, 
        n.`prod_qc`
        FROM new n
        LEFT JOIN projects p ON n.projectid = p.projectid
        WHERE n.employeename = '$employeename'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<div>';
        echo "<table>";
        echo "<tr>";
        echo "<th>Emp Name</th>";
        echo "<th>Date</th>";
        echo "<th>Project ID</th>";
        echo "<th>Title</th>";
        echo "<th>QC Target</th>";
        echo "<th>Prod QC</th>";
        echo "<th>Status</th>";
        echo "<th>Target</th>";
        echo "<th>Total Pages</th>";
        echo "<th>Completed</th>";
        echo "<th>Pending</th>";
        echo "</tr>";

        $totalPages = 0;
        $totalCompleted = 0;
        $totalPending = 0;

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['employeename']}</td>";
            echo "<td>{$row['date']}</td>";
            echo "<td>{$row['projectid']}</td>";
            echo "<td>{$row['project_title']}</td>";
            echo "<td>{$row['qc_target']}</td>";
            echo "<td>{$row['prod_qc']}</td>";
            echo "<td>{$row['status']}</td>";
            echo "<td>{$row['target']}</td>";
            echo "<td>{$row['totalpages']}</td>";
            echo "<td>{$row['completed']}</td>";
            echo "<td>".($row['totalpages'] - $row['completed']). "</td>";
            echo "</tr>";

            $totalPages += $row['totalpages'];
            $totalCompleted += $row['completed'];
        }

        $totalPending = $totalPages - $totalCompleted;

        echo "</table>";
        echo '</div>';
    } else {
        echo "No records found for the selected employee.";
    }
} else {
    echo "Employee ID not provided in the URL.";
}

$conn->close();
?>

<style>
    form {
    margin-top: 2%;
    margin-bottom: 5%;
}
thead,th{background:#fb5607;color:white;}
    a {
        text-decoration: none;
    }

    h1 {
        background-color: #d1991b;
        color: white;
        padding: 5px;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #ccc;
    }

    th, td {
        padding: 8px;
        text-align: left;
        border: 1px solid #ccc;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #ddd;
    }

    /* Add scrolling to the table */
    div {
        max-height: 300px;
        overflow: auto;
    }
</style>

</body>
</html>
