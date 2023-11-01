<!DOCTYPE html>
<html>
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Project Counting Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b272402e67.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/styless.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
<style>
    .whole_container { margin-top: 2%; }
    .backbtn{background:#fb5607; color:white;padding:4px; border:1px #fb5607 solid; border-radius:3px }
    table {
    margin-top: 0%;
}
.backbtn a{color:white;}
h1{margin-top:9%;}
   form{margin-left:8%;}
</style>
<body >
     <button class="backbtn" style="position:absolute;margin-top: 6%;margin-left:85%; margin-bottom:10%"><a href="newreport.php">Go back </button>
       <?php include_once "../includes/header.php"; ?>
       <h1>Employee Individual Report</h1>
        <form method="post" action="">
            <label for="fromDate">From Date:</label>
            <input type="date" name="fromDate" id="fromDate">
            <label for="toDate">To Date:</label>
            <input type="date" name="toDate" id="toDate">
            <input type="submit" name="filter" value="Filter by Date Range">
        </form>
        
        <div class="container whole_container ">
        <?php
        include "../connection.php";

        // Get the employeeid from the URL
        if (isset($_GET['employeename'])) {
            $employeename = $_GET['employeename'];

            if (isset($_POST['filter'])) {
                $fromDate = $_POST['fromDate'];
                $toDate = $_POST['toDate'];

                // Define the SQL query to fetch details for the selected employee with date filter
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
                    WHERE n.employeename = '$employeename'
                    AND n.`date` BETWEEN '$fromDate' AND '$toDate'";
            } else {
                // Define the SQL query to fetch all details for the selected employee without date filter
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
            }

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
                echo "<th>Percentage</th>";
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
                    if ($row['target'] == 0) {
                        echo "<td>0%</td>";
                    } else {
                        echo "<td>".($row['completed'] / $row['target'] * 100). "%</td>";
                    }
                    
                    
                   
                    

                    


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
    </div>
</div>

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
    thead, th {background:#fb5607; color:white; position: sticky; top: 0;}
</style>

</body>
</html>
