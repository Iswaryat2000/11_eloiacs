<!DOCTYPE html>
<html>
<head>
    <title>Employee Search</title>
</head>
<body>
<button style="position:absolute; margin-left:90%"><a href="newreport.php">Go back </a></button>
<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">

    <label for="projectID">Search by Project ID:</label>
    <input type="text" id="projectID" name="projectID" placeholder="Enter Project ID">
    <input type="submit" value="Search">
</form>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../connection.php";

$ourbatch = isset($_GET['ourbatch']) ? $_GET['ourbatch'] : '';
$projectID = isset($_GET['projectID']) ? $_GET['projectID'] : '';
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
    n.`prod_qc`,
    p.`OURBATCH`
FROM new n
LEFT JOIN projects p ON n.projectid = p.projectid
WHERE n.projectid IS NOT NULL AND n.projectid <> ''
    AND (p.status IS NULL OR p.status <> 'completed')";

if (!empty($ourbatch)) {
    $sql .= " AND p.OURBATCH = '$ourbatch'";
}

if (!empty($projectID)) {
    $sql .= " AND n.projectid = '$projectID'";
}

$sql .= " GROUP BY n.`projectid`, n.`employeename`, n.`employeeid`, n.`target`, n.`pending`, n.`completed`, n.`status`, n.`qc_target`, n.`prod_qc`, p.`OURBATCH`";

$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
} else {
    if ($result->num_rows > 0) {
        // Initialize variables to keep track of the current department and project
        $currentDepartment = '';
        $currentProject = '';
        $firstDepartment = true;
        $firstProject = true;

        // Initialize total variables
        $totalPages = 0;
        $totalCompleted = 0;

        // Loop through the results
        while ($row = $result->fetch_assoc()) {
            // Check if the department or project has changed
            if ($row['department'] !== $currentDepartment || $row['projectid'] !== $currentProject) {
                // If not the first department or project, close the previous department's or project's table
                if (!$firstDepartment) {
                    // Calculate totalPending
                    $totalPending = $totalPages - $totalCompleted;

                    echo "<tr style='background-color: #c3b8b8; color: white;'>";
                    echo "<td colspan='7' style='text-align: left;'>Total</td>";
                    echo "<td>$totalPages</td>";
                    echo "<td>$totalCompleted</td>";
                    echo "<td>$totalPending</td>";
                    echo "</tr>";
                    echo "</table>";
                }
                if (!$firstProject) {
                    echo "</table>";
                }
                // Update the current department and project and set firstDepartment and firstProject to false
                $currentDepartment = $row['department'];
                $currentProject = $row['projectid'];
                $firstDepartment = false;
                $firstProject = false;

                // Output department header
                echo "<h2>Department: " . $currentDepartment . " |(OURBATCH: " . $row['OURBATCH'] . ")</h2>";

                // Output table headers for the current department
                echo "<table>";
                echo "<tr>";
                echo "<th>Date</th>";
           
                echo "<th>Project ID</th>";
                echo "<th>Project Title</th>";
                echo "<th>QC Target</th>";
                echo "<th>Prod QC</th>";
                echo "<th>Status</th>";
    
                echo "<th>Target</th>";
                echo "<th>Total Pages</th>";
                echo "<th>Completed</th>";
                echo "<th>Pending</th>";
                echo "</tr>";

                // Reset total variables for the new department
                $totalPages = 0;
                $totalCompleted = 0;
            }

            // Output data rows for the current department and project
            echo "<tr>";
            echo "<td>{$row['date']}</td>";
         
            echo "<td>{$row['projectid']}</td>";
            echo "<td>{$row['project_title']}</td>";
            echo "<td>{$row['qc_target']}</td>";
            echo "<td>{$row['prod_qc']}</td>";
            echo "<td>{$row['status']}</td>";
            echo "<td>{$row['target']}</td>";
            echo "<td>{$row['totalpages']}</td>";
            echo "<td>{$row['completed']}</td>";
            echo "<td>{$row['pending']}</td>";

            $totalPages += $row['totalpages'];
            $totalCompleted += $row['completed'];
        }

        $totalPending = $totalPages - $totalCompleted;
        echo "<tr style='background-color: #c3b8b8; color: white;'>";
        echo "<td colspan='7' style='text-align: left;'>Total</td>";

        echo "<td>$totalPages</td>";
        echo "<td>$totalCompleted</td>";
        echo "<td>$totalPending</td>";
        echo "</tr>";
        
        echo "</table>";
        
    } else {
        echo "No records found.";
    }
}

$conn->close();
?>
<link rel="stylesheet" type="text/css" href="styles.css">
<style>
    a {
        text-decoration: none;
    }

    h2 {
        color: BLACK;
        padding: 5px;
        margin-top: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 8px;
        text-align: left;
        border: 1px solid #ccc;
    }

    th {
        background-color: orange;
        color: white;
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
