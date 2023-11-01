<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batches for Department</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b272402e67.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/dashboard_pro.css">
    <link rel="stylesheet" href="../css/styless.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<style>
     .whole_container { margin-top: 8%; }
</style>
<body>
    <?php include_once "../includes/header.php"?>
    <div class="container whole_container">
    <h1>Batches for Department</h1>
    <div class="calendar_days">
        <input type="date" name="from_date" id="from_date"/>
        <input type="date" name="to_date" id="to_date">
        <input type="button" value="Get" id="filter_button"/>
        <input type="button" value="Current Date" id="current_date_button">
    </div>
    <div class="row">
        <div class="col">
            <?php
            // Check if the department parameter is set in the URL
            if (isset($_GET['department'])) {
                $selectedDepartment = $_GET['department'];

                // Include your database connection code
                include_once '../connection.php';

                // Build SQL query to retrieve batches for the selected department
              // Build SQL query to retrieve batches for the selected department
$sql = "SELECT OURBATCH, COUNT(PROJECTID) AS ProjectCount, SUM(RECEIVEDPAGES) AS TotalReceivedPages
FROM projects
WHERE DEPARTMENT = '$selectedDepartment'";

if (isset($_GET['from_date']) && !empty($_GET['from_date'])) {
$from_date = mysqli_real_escape_string($conn, $_GET['from_date']);
$sql .= " AND DATE(RECEIVEDDATE) >= '$from_date'";
}

if (isset($_GET['to_date']) && !empty($_GET['to_date'])) {
$to_date = mysqli_real_escape_string($conn, $_GET['to_date']);
$sql .= " AND DATE(RECEIVEDDATE) <= '$to_date'";
}

if (isset($_GET['filter_date']) && !empty($_GET['filter_date'])) {
    $selected_date = mysqli_real_escape_string($conn, $_GET['filter_date']);
    $sql .= " AND CURRENTDATE = '$selected_date'";
}

$sql .= " GROUP BY OURBATCH
  ORDER BY ID DESC";



                $result = mysqli_query($conn, $sql);

                if ($result) {
                    echo '<h2>' . $selectedDepartment . '</h2>';
                    echo "<table>";

                    echo "<tr>
                        <th>Our Batch</th>
                        <th>Batch Number</th>
                        <th>Received Pages</th>
                        <th>Completed</th>
                        <th>Pending</th>
                    </tr>";

                    // Initialize an array to store project data
                    $projects = array();

                    while ($row = mysqli_fetch_assoc($result)) {
                        $ourBatch = $row['OURBATCH'];
                        $projectCount = $row['ProjectCount'];
                        $totalReceivedPages = $row['TotalReceivedPages'];

                        // Fetch completed and pending values for the projects in the batch
                        $sql_new = "SELECT SUM(completed) AS CompletedValue, SUM(pending) AS PendingValue FROM new WHERE projectid IN (SELECT PROJECTID FROM projects WHERE OURBATCH = '$ourBatch')";
                        $sql_new_result = mysqli_query($conn, $sql_new);

                        if ($sql_new_result && $row_sql_new_result = mysqli_fetch_assoc($sql_new_result)) {
                            $completedValue = $row_sql_new_result['CompletedValue'];
                            $pendingValue = $totalReceivedPages - $completedValue;

                            // Store project data in the array
                            $projectData = array(
                                'ourBatch' => $ourBatch,
                                'projectCount' => $projectCount,
                                'totalReceivedPages' => $totalReceivedPages,
                                'completedValue' => $completedValue,
                                'pendingValue' => $pendingValue
                            );

                            $projects[] = $projectData; // Add project data to the array

                            echo "<tr>";
                           # <a href="report_two.php?project_id=' . $project['PROJECTID'] . '">' . $project['PROJECTID'] 
                           echo '<td><a href="report project.php?ourbatch=' . $ourBatch . '">' . $ourBatch . '</a></td>';

                            echo "<td>$projectCount</td>"; // Display the count of projects in the Batch Number column
                            echo "<td>$totalReceivedPages</td>"; // Display the total received pages
                            echo "<td>$completedValue</td>"; // Display the completed value
                            echo "<td>$pendingValue</td>"; // Display the pending value
                            echo "</tr>";
                        }
                    }

                    echo "</table>";
                } else {
                    echo "No projects found for the selected department.";
                }
            } else {
                // Handle the case where the department parameter is not set
                echo "Department parameter is missing.";
            }
            ?>
        </div>
        <div class="col">
            <!-- Create a canvas for the line chart -->
            <canvas id="lineChart" width="400" height="200"></canvas>
            <script>
                var ctx = document.getElementById('lineChart').getContext('2d');

                <?php
                // Loop through the projects and create a chart for each one
                echo "var projectData = " . json_encode($projects) . ";"; // Convert the PHP array to JavaScript

                echo "var labels = ['Batch Number'];";
                echo "var receivedPagesData = [0];";
                echo "var completedData = [0];";
                echo "var pendingData = [0];";

                foreach ($projects as $project) {
                    $ourBatch = $project['ourBatch'];
                    $projectCount = $project['projectCount'];
                    $totalReceivedPages = $project['totalReceivedPages'];
                    $completedValue = $project['completedValue'];
                    $pendingValue = $project['pendingValue'];

                    // Append project data to JavaScript arrays
                    echo "labels.push('$ourBatch');";
                    echo "receivedPagesData.push($totalReceivedPages);";
                    echo "completedData.push($completedValue);";
                    echo "pendingData.push($pendingValue);";
                }
                ?>

                var data = {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Received Pages',
                            borderColor: 'blue', // Line color
                            fill: false, // Don't fill the area under the line
                            data: receivedPagesData
                        },
                        {
                            label: 'Completed',
                            borderColor: 'green', // Line color
                            fill: false, // Don't fill the area under the line
                            data: completedData
                        },
                        {
                            label: 'Pending',
                            borderColor: 'red', // Line color
                            fill: false, // Don't fill the area under the line
                            data: pendingData
                        }
                    ]
                };

                var config = {
                    type: 'line', // Use a line chart
                    data: data,
                    options: {
                        scales: {
                            x: {
                                beginAtZero: true
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                };

                var myChart = new Chart(ctx, config);
            </script>
        </div>
    </div>
    <script>
   document.getElementById('filter_button').addEventListener('click', function() {
    var from_date = document.getElementById('from_date').value;
    var to_date = document.getElementById('to_date').value;
    var department = "<?php echo $selectedDepartment; ?>"; // Get the selected department from PHP

    // Construct the URL with "from_date," "to_date," and "department" parameters
    var url = 'batches.php?from_date=' + from_date + '&to_date=' + to_date + '&department=' + department;

    // Redirect to the filtered page
    window.location.href = url;
});

document.getElementById('current_date_button').addEventListener('click', function() {
            var currentDate = new Date().toISOString().slice(0, 10);
            document.getElementById('filter_date').value = currentDate; // Set current date in the input field
            window.location.href = 'report.php?filter_date=' + currentDate;
        });
    </script>
    </div>
</body>
</html>
