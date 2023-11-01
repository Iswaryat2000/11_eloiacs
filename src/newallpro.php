<!DOCTYPE html>
<html>
<head>
    <title>Employee Search</title>
</head>
<body>
<button style="position:absolute; margin-left:90%"><a href="newreport.php">Go back</a></button>
    <form method="GET" action="">
        <label for="employeeName">Search by Employee Name:</label>
        <input type="text" id="employeeName" name="employeeName" placeholder="Enter employee name">
        <label for="projectID">Search by Project ID:</label>
        <input type="text" id="projectID" name="projectID" placeholder="Enter project ID">
        <input type="submit" value="Search">
    </form>

    <?php
    include "../connection.php";

    // Initialize search conditions
    $employeeName = isset($_GET['employeeName']) ? $_GET['employeeName'] : '';
    $projectID = isset($_GET['projectID']) ? $_GET['projectID'] : '';

    // Construct the SQL query based on search conditions
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
        AND (p.status IS NULL OR p.status <> 'completed')";

    // Append search conditions to the SQL query
    if (!empty($employeeName)) {
        $sql .= " AND n.employeename LIKE '%$employeeName%'";
    }

    if (!empty($projectID)) {
        $sql .= " AND n.projectid = '$projectID'";
    }

    $sql .= " GROUP BY n.`projectid`, n.`employeename`, n.`employeeid`, n.`target`, n.`pending`, n.`completed`, n.`status`, n.`qc_target`, n.`prod_qc`";

    // Execute the SQL query
    $result = $conn->query($sql);

    if (!$result) {
        echo "Error: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            $recordsByProject = array();

            while ($row = $result->fetch_assoc()) {
                $projectId = $row['projectid'];
                $recordsByProject[$projectId][] = $row;
            }

            foreach ($recordsByProject as $projectId => $records) {
                $projectTitle = $records[0]['project_title'];

                echo "<h1>Project: $projectTitle (Project ID: $projectId)</h1>";

                echo '<div>';
                echo "<table>";
                echo "<tr>";
                echo "<th>Date</th>";
                echo "<th>Employee ID</th>";
                echo "<th>Employee Name</th>";
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
                    echo "<td>{$record['date']}</td>";
                    echo "<td>{$record['employeeid']}</td>";
                    echo "<td>{$record['employeename']}</td>";
                    echo "<td>{$record['qc_target']}</td>";
                    echo "<td>{$record['prod_qc']}</td>";
                    echo "<td>{$record['status']}</td>";
                    echo "<td>{$record['target']}</td>";
                    echo "<td>{$record['totalpages']}</td>"; // Fix here
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
