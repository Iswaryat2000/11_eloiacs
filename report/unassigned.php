
<button style="position:absolute; margin-left:90%"><a href="newreport.php">Go back </button>



<?php

include "../connection.php";
include_once '../includes/header.php';

$currentDate = date("Y-m-d"); 
echo "Date: $currentDate";
$sql = "SELECT 
            n.`date`, n.`employeeid`, n.`employeename`, n.`department`, n.`projectid`, n.`target`, n.`pending`, n.`completed`, SUM(p.`receivedpages`) AS totalpages, n.`status`, n.`qc_target`, n.`prod_qc`
        FROM new n
        LEFT JOIN projects p ON n.projectid = p.projectid
        WHERE n.projectid IS NOT NULL AND n.projectid <> ''
        AND (p.status IS NULL OR p.status <> 'completed')
        AND n.`date` = '$currentDate' 
        GROUP BY n.`date`, n.`employeeid`, n.`employeename`, n.`department`, n.`projectid`, n.`target`, n.`pending`, n.`completed`, n.`status`, n.`qc_target`, n.`prod_qc`
        ORDER BY n.department";

$result = $conn->query($sql);

if (!$result) {
    echo "Error: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        $recordsByDepartment = array();

        while ($row = $result->fetch_assoc()) {
            $department = $row['department'];
            $recordsByDepartment[$department][] = $row;
        }

        foreach ($recordsByDepartment as $department => $records) {
            echo "<h1>$department</h1>";

            echo '<div>';
            echo "<table>";
            echo "<tr>";

            // Include additional column headers
            echo "<th>Date</th>";
            echo "<th>Employee ID</th>";
            echo "<th>Employee Name</th>";
echo "<th>Project ID</th>";
         
            echo "<th>QC Target</th>";
            echo "<th>Prod QC</th>";
            echo "<th>Status</th>";
            echo "<th>Total Pages</th>";
            echo "<th>Target</th>";
            echo "<th>Completed</th>";
            echo "<th>Pending</th>";

         
      
          

            echo "</tr>";

            $totalPages = 0; // Initialize total pages for the department
            $totalCompleted = 0; // Initialize total completed pages
            $totalPending = 0; // Initialize total pending pages

            foreach ($records as $record) {
                echo "<tr>";
                echo "<td>{$record['date']}</td>";
                echo "<td>{$record['employeeid']}</td>";
                echo "<td>{$record['employeename']}</td>";
                echo "<td>{$record['projectid']}</td>";
              
                echo "<td>{$record['qc_target']}</td>";
                echo "<td>{$record['prod_qc']}</td>";
                echo "<td>{$record['status']}</td>";
                echo "<td>{$record['totalpages']}</td>";
                echo "<td>{$record['target']}</td>";
                echo "<td>{$record['completed']}</td>";
                echo "<td>{$record['pending']}</td>";


        
                echo "</tr>";

                $totalPages += $record['totalpages'];
                $totalCompleted += $record['completed'];
                $totalPending =   $totalPages - $totalCompleted;
            }

            echo "<tr style='background-color: grey; color: white;'>";
            echo "<td colspan='7' style='text-align: left;'>Total</td>";
            echo "<td colspan='2'>$totalPages</td>";
            echo "<td>$totalCompleted</td>";
            echo "<td>$totalPending</td>";
            echo "</tr>";
            
            
            

            echo "</table>";
            echo '</div>';
        }
    } else {
        echo "No records found in the 'new' table for the current date ($currentDate).";
    }
}

$conn->close();
?>
<style>
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
