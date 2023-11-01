<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b272402e67.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/dashboard_pro.css">
    <link rel="stylesheet" href="../css/styless.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>


<?php

include "../connection.php";
include_once '../includes/header.php';
$currentdate = date('Y-m-d');

// Check if a search term was provided
if (isset($_GET['employeename'])) {
    $searchTerm = $_GET['employeename'];
    // Modify the SQL query to filter by employee name
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
        p.`receivedpages` AS totalpages, 
        n.`status`, 
        n.`qc_target`, 
        n.`prod_qc`
        FROM new n
        LEFT JOIN projects p ON n.projectid = p.projectid
        WHERE n.date = '$currentdate' AND n.employeename LIKE '%$searchTerm%'";
} else {
    // If no search term provided, use the original query to fetch all employees for the current date
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
        p.`receivedpages` AS totalpages, 
        n.`status`, 
        n.`qc_target`, 
        n.`prod_qc`
        FROM new n
        LEFT JOIN projects p ON n.projectid = p.projectid
        WHERE n.date = '$currentdate'";
}

$result = $conn->query($sql);
?>

    
<div class="whole_container">
<form method="GET" action="">
    <label for="employeeName">Search by Employee Name:</label>
    <input type="text" id="employeeName" name="employeename" placeholder="Enter employee name">
    <button type="submit">Search</button> <!-- Add a search button -->
</form>
<?php


if (!$result) {
    echo "Error: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        echo '<div class="table_contain">';
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
           
            echo "<td><a href='emp_individual_report.php?employeename={$row['employeename']}'>{$row['employeename']}</a></td>";      
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

            if (isset($row['totalpages'])) {
                $totalPages += $row['totalpages'];
            }
            if (isset($row['completed'])) {
                $totalCompleted += $row['completed'];
            }
        }

        $totalPending = $totalPages - $totalCompleted;

        echo "</tr>";

        echo "</table>";
        echo '</div>';
    } else {
        echo "No records found.";
    }
}

$conn->close();

?>
</div>

<style>
        .whole_container { margin-top: 10%; }
        .table_contain { height: 300px; overflow: scroll; }
        form { margin-top: 2%; margin-bottom: 5%; }
        thead, th { background: #fb5607; color: white; }
        a { text-decoration: none; }
        h1 { background-color: #d1991b; color: white; padding: 5px; text-align: center; }
        thead { 
            position: -webkit-sticky;
            position: sticky;
            top: 0; 
            background-color: #fff;
            z-index: 1; /* Add z-index property */
        }
        table { width: 100%; border-collapse: collapse; border: 1px solid #ccc; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ccc; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        tr:hover { background-color: #ddd; }
    </style>

</body>
</html>
