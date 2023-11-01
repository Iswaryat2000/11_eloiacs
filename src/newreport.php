<?php
include "../includes/login_access.php";?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b272402e67.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/styless.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  
    <link rel="stylesheet" href="../css/time_offf.css"/>  
</head>  
    <style>
        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: orange;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ccc;
        }
    </style>
<body>
    <?php include_once "../includes/header.php" ?>
    <div class="button_pack" style="margin-top:6%">
<button><a href="newreport.php">ALL </a></button>
    <button><a href="newall.php">DEPARTMENT</a></button>
    <button><a href="newallemp.php">EMP INDIVIDUAL</a></button>
    <button><a href="newallourbarch.php">BATCH</a></button>
    <button><a href="newallpro.php">PROJECT</a></button>
    </div>
    <h1>Department Data</h1>
    <span>Current Year: <?php echo date("Y"); ?></span>
    <select id="selectMonth">
        <option value="all">All Months</option>
        <option value="January">January</option>
        <option value="February">February</option>
        <option value="March">March</option>
        <option value="April">April</option>
        <option value="May">May</option>
        <option value="June">June</option>
        <option value="July">July</option>
        <option value="August">August</option>
        <option value="September">September</option>
        <option value="October">October</option>
        <option value="November">November</option>
        <option value="December">December</option>
    </select>

    <button onclick="filterData()">Filter</button>
    <span id="selectedMonth">Selected Month: All Months</span>

    <?php
    include "../connection.php";

    // Use the correct column names from the 'NEW' and 'PROJECTS' tables
    $sql = "SELECT n.Department, n.date, p.RECEIVEDPAGES, n.completed 
            FROM new n
            INNER JOIN projects p ON n.Department = p.Department
            ORDER BY n.date"; // Order by date for better grouping

    $result = $conn->query($sql);

    // Initialize an array to store data grouped by month and department
    $dataByMonth = array();

    // Step 3: Fetch and group data by month
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $month = date('F Y', strtotime($row['date']));
            $department = $row['Department'];
            if (!isset($dataByMonth[$month])) {
                $dataByMonth[$month] = array();
            }
            if (!isset($dataByMonth[$month][$department])) {
                $dataByMonth[$month][$department] = array(
                    'ReceivedPages' => 0,
                    'Completed' => 0,
                    'PendingValue' => 0,
                );
            }
            $dataByMonth[$month][$department]['ReceivedPages'] += $row['RECEIVEDPAGES'];
            $dataByMonth[$month][$department]['Completed'] += $row['completed'];
        }

        // Step 4: Display data for each month and department in separate tables
        foreach ($dataByMonth as $month => $departmentData) {
            echo "<div class='month-data hidden' data-month='" . date('F', strtotime($month)) . "'>";
            echo "<h2>$month</h2>";
            echo "<table>";
            echo "<tr>";
            echo "<th>Department</th>";
            echo "<th>Total Received Pages</th>";
            echo "<th>Total Completed Value</th>";
            echo "<th>Total Pending Value</th>";
            echo "</tr>";

            foreach ($departmentData as $department => $totals) {
                echo "<tr>";
                echo "<td>" . $department . "</td>";
                echo "<td>" . $totals['ReceivedPages'] . "</td>";
                echo "<td>" . $totals['Completed'] . "</td>";

                // Calculate the Pending_Value
                $pendingValue = $totals['ReceivedPages'] - $totals['Completed'];
                echo "<td>" . $pendingValue . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }
    } else {
        echo "No data found";
    }
    

    // Step 5: Close the database connection
    $conn->close();
    ?>

    <script>
        function filterData() {
            var select = document.getElementById("selectMonth");
            var selectedMonth = select.value;
            document.getElementById("selectedMonth").textContent = "Selected Month: " + selectedMonth;
            // Hide all month data
            var monthDataElements = document.querySelectorAll('.month-data');
            monthDataElements.forEach(function (element) {
                element.classList.add('hidden');
            });
            // Show the selected month data
            var selectedMonthData = document.querySelector('.month-data[data-month="' + selectedMonth + '"]');
            if (selectedMonthData) {
                selectedMonthData.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>