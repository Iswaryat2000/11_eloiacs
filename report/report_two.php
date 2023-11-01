<!DOCTYPE html>
<html lang="en">
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
<style>
    .whole_container{margin-top:10%}
</style>
<body>
<?php include_once '../includes/header.php'; ?>
    <div class="container whole_container ">
    <h2>Employee Production Report</h2>
    <div class="row">
        <div class="col">
    <?php
    include_once "../connection.php";

    if(isset($_GET['project_id'])) {
        $project_id = $_GET['project_id'];
    
        // Prepare the SQL query using a prepared statement
        $sql_employee_production = "SELECT * FROM new WHERE projectid = ?";
        $stmt = mysqli_prepare($conn, $sql_employee_production);
    
        // Bind the project_id parameter
        mysqli_stmt_bind_param($stmt, "s", $project_id);
    
        // Execute the prepared statement
        mysqli_stmt_execute($stmt);
    
        // Get the result set
        $result_employee_production = mysqli_stmt_get_result($stmt);
    
        // Check if there are rows in the result set
        if(mysqli_num_rows($result_employee_production) > 0) {
            // Arrays to store data for the chart
            $labels = ['Target', 'Completed', 'Pending'];
            $datasets = [];
    
            // Display employee production data as a table
            echo '<h3>'. $project_id . '</h3>';
            echo '<table border="1">';
            echo '<thead>';
            echo '<th>Employee ID</th>';
            echo '<th>Employee Name</th>';
            echo '<th>Target</th>';
            echo '<th>Completed</th>';
            echo '<th>Pending</th>';
            echo '<th>Total Pages</th>';
            echo '</thead>';
            echo '<tbody>';
            while($row = mysqli_fetch_assoc($result_employee_production)) {
                echo '<tr>';
                echo '<td>' . $row['employeeid'] . '</td>';
                echo '<td>' . $row['employeename'] . '</td>';
                echo '<td>' . $row['target'] . '</td>';
                echo '<td>' . $row['completed'] . '</td>';
                echo '<td>' . $row['pending'] . '</td>';
                echo '<td>' . $row['totalpages'] . '</td>';
                echo '</tr>';
    
                // Prepare data for the chart
                $datasets[] = [
                    'label' => $row['employeename'],
                    'data' => [$row['target'], $row['completed'], $row['pending']],
                ];
            }
            echo '</tbody>';
            echo '</table>';
    
            // Generate line chart based on employee targets, completed, and pending values
            ?>
            </div>
            <div class="col-6">
            <canvas id="employeeLineChart" width="400" height="200"></canvas>
            <script>
                var ctx = document.getElementById('employeeLineChart').getContext('2d');
                var data = {
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: [
                        <?php
                        foreach ($datasets as $dataset) {
                            echo '{
                                label: "' . $dataset['label'] . '",
                                data: ' . json_encode($dataset['data']) . ',
                                fill: false,
                                borderColor: "' . '#' . substr(md5(mt_rand()), 0, 6) . '",
                            },';
                        }
                        ?>
                    ]
                };
                var myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: data,
                });
            </script>
            </div>
            </div>
            </div>
            <?php
        } else {
            echo "No data available for the selected project ID.";
        }
    } else {
        echo "Invalid Project ID";
    }

    mysqli_close($conn);
    ?>
</body>
</html>
