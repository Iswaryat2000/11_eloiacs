<!DOCTYPE html>
<html>
<head>
    <title>Employee Search</title>
</head>
<body>
<button style="position:absolute; margin-left:90%"><a href="newreport.php">Go back </a></button>
<form method="GET" action="">
    <label for="employeeName">Search by Employee Name:</label>
    <input type="text" id="employeeName" name="employeeName" placeholder="Enter employee name">
    <button type="submit">Search</button> <!-- Add a search button -->
</form>

  



<?php
include "../connection.php";      

if (isset($_GET['employeeName'])) {
    $employeeName = $_GET['employeeName'];

    $sql = "SELECT 
        n.`date`,   
        n.`employeeid`, 
        n.`employeename`, 
        n.`department`, 
        n.`projectid`,     
        p.`WORKTITLE` AS project_title,  
        n.`target`, 
        n.`pending`, 
        n.`completed`, 
        SUM(p.`receivedpages`) AS totalpages, 
        n.`status`, 
        n.`qc_target`, 
        n.`prod_qc`
    FROM new n
    LEFT JOIN projects p ON n.projectid = p.projectid
    WHERE n.projectid IS NOT NULL AND n.projectid <> ''
        AND (p.status IS NULL OR p.status <> 'completed')
        AND n.`employeename` LIKE '%$employeeName%'
    GROUP BY n.`employeeid`, n.`employeename`, n.`department`, n.`projectid`, n.`target`, n.`pending`, n.`completed`, n.`status`, n.`qc_target`, n.`prod_qc`
    ORDER BY n.department, n.`employeename`, n.`projectid`";
} else {
    $sql = "SELECT 
        n.`date`,   
        n.`employeeid`, 
        n.`employeename`, 
        n.`department`, 
        n.`projectid`,     
        p.`WORKTITLE` AS project_title,  
        n.`target`, 
        n.`pending`, 
        n.`completed`, 
        SUM(p.`receivedpages`) AS totalpages, 
        n.`status`, 
        n.`qc_target`, 
        n.`prod_qc`
    FROM new n
    LEFT JOIN projects p ON n.projectid = p.projectid
    WHERE n.projectid IS NOT NULL AND n.projectid <> ''
        AND (p.status IS NULL OR p.status <> 'completed')
    GROUP BY n.`employeeid`, n.`employeename`, n.`department`, n.`projectid`, n.`target`, n.`pending`, n.`completed`, n.`status`, n.`qc_target`, n.`prod_qc`
    ORDER BY n.department, n.`employeename`, n.`projectid`";
}

$result = $conn->query($sql);

if (!$result) {
    echo "Error: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        $recordsByEmployee = array();

        while ($row = $result->fetch_assoc()) {
            $employeeId = $row['employeeid'];
            $recordsByEmployee[$employeeId][] = $row;
        }

        foreach ($recordsByEmployee as $employeeId => $records) {
            $employeeName = $records[0]['employeename'];
            $department = $records[0]['department'];

            echo "<h1>Employee: $employeeName (Department: $department)</h1>";

            echo '<div>';
            echo "<table>";
            echo "<tr>";
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

            foreach ($records as $record) {
                echo "<tr>";
                if (isset($record['date'])) {
                    echo "<td>{$record['date']}</td>";
                } else {
                    echo "<td></td>";
                }
                echo "<td>{$record['projectid']}</td>";
                echo "<td>{$record['project_title']}</td>";
                echo "<td>{$record['qc_target']}</td>";
                echo "<td>{$record['prod_qc']}</td>";
                echo "<td>{$record['status']}</td>";
                echo "<td>{$record['target']}</td>";
                echo "<td>{$record['totalpages']}</td>";
                echo "<td>{$record['completed']}</td>";
                echo "<td>{$record['pending']}</td>";
                echo "</tr>";

                if (isset($record['totalpages'])) {
                    $totalPages += $record['totalpages'];
                }
                if (isset($record['completed'])) {
                    $totalCompleted += $record['completed'];
                }
            }

            $totalPending = $totalPages - $totalCompleted;

            echo "<tr style='background-color: grey; color: white;'>";
            echo "<td colspan='7' style='text-align: left;'>Total</td>";
            echo "<td>$totalPages</td>";
            echo "<td>$totalCompleted</td>";
            echo "<td>$totalPending</td>";
            echo "</tr>";

            echo "</table>";
            echo '</div>';
        }
    } else {
        echo "No records found.";
    }
}

$conn->close();
?>

<style>
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
