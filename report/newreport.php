<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Project Counting Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b272402e67.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/dashboard_pro.css">
    <link rel="stylesheet" href="../css/styless.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .contain1 {
            margin-top: 81px;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            border: 1px solid #333;
        }

        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: orange;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        h1 {
            font-size: 2.5rem;
            margin-top: 58px;
            text-align: center;
        }

        tr:hover {
            background-color: #ccc;
        }
        .whole_container { margin-top: 8%; }
    </style>
</head>

<body>
    <?php
include_once '../includes/header.php'; ?>
    <div class="contain1 whole_container">
        <button><a href="newreport.php">ALL </a></button>
        <button><a href="employees_report.php">EMP INDIVIDUAL</a></button>
        

        <h1>All Project Counting Report</h1>
        <form method="post" action="">
            <label for="fromDate">From Date:</label>
            <input type="date" name="fromDate" id="fromDate">
            <label for="toDate">To Date:</label>
            <input type="date" name="toDate" id="toDate">
            <input type="submit" name="filter" value="Filter by Date Range">
        </form>

<table border="1">
    <tr>
     
        <th>Department</th>
        <th>Total Received Pages</th>
        <th>Total Completed</th>
        <th>Total Pending</th>
    </tr>
    <?php
include "../connection.php";

if (isset($_POST['filter'])) {
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
} else {
    $currentYear = date('Y');
    $fromDate = $currentYear . '-01-01'; // Start of the year
    $toDate = $currentYear . '-12-31';   // End of the year
}

$sql = "SELECT n.department, 
               SUM(p.RECEIVEDPAGES) AS totalReceivedPages, 
               SUM(n.completed) AS totalCompleted
        FROM new n
        LEFT JOIN projects p ON n.projectid = p.projectid
        WHERE DATE(n.date) BETWEEN '$fromDate' AND '$toDate'
        GROUP BY n.department";

// Step 3: Execute the query and fetch the data
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $totalReceivedPages = 0;
    $totalCompleted = 0;

    while ($row = $result->fetch_assoc()) {
        $totalReceivedPages += $row['totalReceivedPages'];
        $totalCompleted += $row['totalCompleted'];
        $department = $row['department'];
        

        echo "<tr>";
  
        echo "<td><a href='batches.php?department=$department'>$department</a></td>";
        echo "<td>" . $row['totalReceivedPages'] . "</td>";
        echo "<td>" . $row['totalCompleted'] . "</td>";
        echo "<td>" . ($row['totalReceivedPages'] - $row['totalCompleted']) . "</td>";
        echo "</tr>";
    }

   
} else {
    echo "<tr><td colspan='5'>No data found</td></tr>";
}

// Step 4: Close the database connection
$conn->close();
?>
