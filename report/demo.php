<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col">

        
    <h2>REPORT</h2>

    <div class="calendar_days">
        <input type="date" name="filter_date" id="filter_date"/>
        <input type="button" value="Get" id="filter_button"/>
        <input type="button" value="Current Date" id="current_date_button"/>
        <select id="status_filter">
            <option value="">Select Status</option>
            <?php
            // Fetch status options from the database
            include_once "../connection.php";
            $sql_status = "SELECT DISTINCT `STATUS` FROM `department_company`";
            $result_status = mysqli_query($conn, $sql_status);
            while($row_status = mysqli_fetch_assoc($result_status)) {
                echo '<option value="' . $row_status['STATUS'] . '">' . $row_status['STATUS'] . '</option>';
            }
            ?>
        </select>
    </div>

    <!-- Table to display project details -->
    <div id="project_details">
    <?php
    include_once "../connection.php";

    // Initial SQL query
    $sql_report = "SELECT * FROM projects WHERE 1";


    // Filter by date if provided
    if(isset($_GET['filter_date']) && !empty($_GET['filter_date'])) {
        $selected_date = mysqli_real_escape_string($conn, $_GET['filter_date']);
        $sql_report .= " AND CURRENTDATE = '$selected_date'";
    }

    // Filter by status if provided
    if(isset($_GET['status']) && !empty($_GET['status'])) {
        $status_filter = mysqli_real_escape_string($conn, $_GET['status']);
        $sql_report .= " AND STATUS = '$status_filter'";
    }

    $sql_report .= " ORDER BY id DESC";

    $result_report = mysqli_query($conn, $sql_report);



    $groupedProjects = [];
    while ($row = mysqli_fetch_assoc($result_report)) {

        
    
    
        $department = $row['DEPARTMENT'];
        if (!isset($groupedProjects[$department])) {
            $groupedProjects[$department] = [];
        }
        $groupedProjects[$department][] = $row;
    }

    // Display grouped projects
    foreach ($groupedProjects as $department => $projects) {
        echo "<h3>$department</h3>"; // Department heading
        
            // Display project details in a table row
            echo '<table>';
            echo '<thead>';
            echo '<th>Project ID</th>';
            echo '<th>Our Batch</th>';
            echo '<th>Department</th>';
            echo '<th>Received Pages</th>';
            echo '<th>File Target</th>';
            echo '<th>QC Target</th>';
            echo '<th>Status</th>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($projects as $project) {
           
            echo '<tr>';
            echo '<td><a href="report_two.php?project_id=' . $project['PROJECTID'] . '">' . $project['PROJECTID'] . '</a></td>';
            echo '<td>' . $project['OURBATCH'] . '</td>';
            echo '<td>' . $project['DEPARTMENT'] . '</td>';
            echo '<td>' . $project['RECEIVEDPAGES'] . '</td>';
            echo '<td>' . $project['File_target'] . '</td>';
            echo '<td>' . $project['QC_TARGET'] . '</td>';
            echo '<td>' . $project['STATUS'] . '</td>';
            echo '</tr>';
          
            }
            echo '<tr>';
            $sql_new_report = "SELECT * FROM new WHERE department = '$department'";
            $result_report_new = mysqli_query($conn, $sql_new_report);
            
            $total_target = 0;
            $total_completed = 0;
            
            while($row_result = mysqli_fetch_assoc($result_report_new)){
                $total_target += $row_result['target'];
                $total_completed += $row_result['completed'];
            }
            
            $total_pending = $total_target - $total_completed;
            
            echo '<td colspan="4">Total Target: ' . $total_target . '</td>';
            echo '<td colspan="4">Total Completed: ' . $total_completed . '</td>';
            echo '<td colspan="4">Total Pending: ' . $total_pending . '</td>';
            echo '</tr>';
            
            echo '</tbody>';
            echo '</table>';
        
    }
    ?>
</div>
</div>

<div class="col">
    <!-- Filter dropdown for departments -->
    <select id="department_filter">
        <option value="">Select Department</option>
        <?php
        // Fetch department options from the database column DEPARTMENT_ELOIACS
        include_once "connection.php";
        $sql_department = "SELECT DISTINCT `DEPARTMENT_ELOIACS` FROM `department_company` WHERE `DEPARTMENT_ELOIACS` IS NOT NULL";
        $result_department = mysqli_query($conn, $sql_department);
        while ($row_department = mysqli_fetch_assoc($result_department)) {
            echo '<option value="' . $row_department['DEPARTMENT_ELOIACS'] . '">' . $row_department['DEPARTMENT_ELOIACS'] . '</option>';
        }
        ?>
    </select>

    <!-- Canvas for the pie chart -->
    <canvas id="pieChart" width="400" height="400"></canvas>
</div>
</div>
</div>

<!-- Canvas for the pie chart -->
<canvas id="pieChart" width="400" height="400"></canvas>

<script>
// Function to update the pie chart based on selected department
function updatePieChart(department) {
    // Clear previous chart if it exists
    var existingChart = Chart.getChart("pieChart");
    if (existingChart) {
        existingChart.destroy();
    }

    fetch('fetch_data.php?department=' + department + '&status=Assign')
        .then(response => response.json())
        .then(data => {
            var statuses = [];
            var counts = [];
            data.forEach(item => {
                statuses.push(item.STATUS);
                counts.push(item.count);
            });

            // Create a pie chart
            var ctx = document.getElementById('pieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: statuses,
                    datasets: [{
                        data: counts,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Event listener for department selection
document.getElementById('department_filter').addEventListener('change', function () {
    var selectedDepartment = this.value;
    if (selectedDepartment !== '') {
        // Update the pie chart based on the selected department
        updatePieChart(selectedDepartment);
    }
});

// Initial update of the pie chart based on the default selected department
var initialSelectedDepartment = document.getElementById('department_filter').value;
updatePieChart(initialSelectedDepartment);
</script>



    <script>
        document.getElementById('filter_button').addEventListener('click', function() {
            var selected_date = document.getElementById('filter_date').value;
            window.location.href = 'report.php?filter_date=' + selected_date;
        });

        document.getElementById('current_date_button').addEventListener('click', function() {
            var currentDate = new Date().toISOString().slice(0, 10);
            document.getElementById('filter_date').value = currentDate; // Set current date in the input field
            window.location.href = 'report.php?filter_date=' + currentDate;
        });
        document.getElementById('status_filter').addEventListener('change', function() {
            var selected_status = this.value;
            // Append status filter to the URL and reload the page
            window.location.href = 'report.php?filter_date=' + document.getElementById('filter_date').value + '&status=' + selected_status;
        });
    </script>
</body>
</html>
