

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TL WORK ASSIGN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b272402e67.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/styless.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<style>
/* CSS for loading overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: transparent; /* Semi-transparent white background */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999; /* Ensure it's on top of other content */
}

.spinner-border {
    width: 5rem; /* Adjust the size of the spinner as needed */
    height: 5rem; /* Adjust the size of the spinner as needed */
}

/* CSS to blur the background */
body.loading {
    filter: blur(5px); /* Adjust the blur intensity as needed */
    pointer-events: none; /* Prevent interactions with the blurred background */
}


body {
    width: 95%;
    margin: 5% 2%;
}.tl_work_trash{
    color:#fb5607;
    font-size:20px
}
  
    .table-container{
        margin:8% 2%;
    }#second-table-container
    {
        margin:8% 0%;
    } 
    .table-Container {
  max-height: 350px;
  overflow: auto; 
}
table {
  width: 100%; 
  border-collapse: collapse;
}
thead {
    position: sticky;
  top: 0;
  background: linear-gradient(180deg, #fc7105, orange);
  color: white;
  z-index:3;
}
thead th {
  text-transform: uppercase;
  height: 40px;
  z-index: 2; 
  border:1px solid transparent;
  border:#000 #fb5607;
  text-align:center;
}tbody tr{
  padding: 8px;
  height: 40px; 
  border-bottom: 1px solid #ddd;
  background-color:#ddd;
  text-align:center;
}

tbody tr:hover{

  background-color:white;
  border-bottom:1px solid black;
}.fetch-btn:hover{
    color:white;
    background-color:#fb5607;
    font-weight:600;
}
.table-Container::-webkit-scrollbar {
  width: 0.5em;
}
.table-Container::-webkit-scrollbar-thumb {
  background-color: #888;
}.view-btn,a
{
    color:white !important;
    text-decoration: none;
}
.save_tl_btn {
    margin: 15px 0px;
    color: white;
    font-size: 17px;
    font-weight: 600;
}.tl_work-select,.tl_work-input,.tl_work-select-input,.tl_work-input{
    border:none;    
    width:max-content;
    background-color:transparent;
}.tl_work-select{
    font-weight:bold;
}
.tl_work-select:hover
{
    border:1px solid black;
}.tl_work-input:read-only:focus {
            outline: none;
        }
</style>
<body>
    
<?php
 require_once "../includes/header.php";

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$sql_tl_work = "SELECT
    p.PROJECTID,
    p.BATCHNUMBER AS BATCHNUMBER,
    p.WORKTITLE,
    p.TL_STATUS,
    p.DEPARTMENT,
    p.RECEIVEDPAGES AS RECEIVEDPAGES,
    p.ISBNNUMBER
FROM
    projects p
WHERE
    (p.STATUS = 'Assign' OR p.STATUS = 'In Progress') AND
    p.DEPARTMENT = '$PROJECT_DEPARTMENT' AND
    '$EMP_TEAMLEADER' = 'Yes';";
$result = $conn->query($sql_tl_work);
if (!$result) {
    die("Database query failed: " . $conn->error);
}
?>
    <div class="table-container ">
    <div class="loading-overlay" id="loadingOverlay" style="display:none">
        <!-- You can add loading spinners or text here -->
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
        <table class="table1" border="1px">
            <thead>
                <tr>
                    <th>Project ID</th>
                    <th>Batch No</th>
                    <th>Title</th>
                    <th>Department</th>
                    <th>Total Pages</th>
                    <th>Final TAT</th>
                    <th>Status</th>
                    <th>File Target</th>
                    <th>QC Target</th>
                    <th>Pending</th>
                    <th>Error</th>
                    <th colspan="2">Action</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["PROJECTID"] . "</td>";
                        echo "<td>" . $row["BATCHNUMBER"] . "</td>";
                        echo "<td>" . $row["WORKTITLE"] . "</td>";
                        echo "<td>" . $row["DEPARTMENT"] . "</td>";
                        echo "<td>" . $row["RECEIVEDPAGES"] . "</td>";
                        echo "<td>" . $row["ISBNNUMBER"] . "</td>";
                        echo "<td>";
                        ?>
                        <select id="status-dropdown-<?= $row["PROJECTID"] ?>" class="status-dropdown tl_work-select" data-department="<?= $row["DEPARTMENT"] ?>" data-project-id="<?= $row["PROJECTID"] ?>">

    <option selected value="<?= $row['TL_STATUS'] ?>"><?= $row['TL_STATUS'] ?></option>
    <option value='' disabled>----------------</option>
    <?php
    // Populate the dropdown options as before
    $sql = "SELECT `STATUS` FROM `department_company` WHERE `STATUS` IS NOT NULL";
    $statusResult = $conn->query($sql);

    if ($statusResult->num_rows > 0) {
        while ($statusRow = $statusResult->fetch_assoc()) {
            $STATUS = $statusRow['STATUS'];
            echo '<option value="' . $STATUS . '">' . $STATUS . '</option>';
        }
    } else {
        echo '<option value="No department found">No department found</option>';
    }
    ?>
</select>
                        
<?php
                        echo "</td>";

                        // Fetch and display completed and QC_TARGET values from the 'new' table based on projectid
                        $projectID = $row["PROJECTID"];
                        $completedValues = [];
                        $qcTargetValues = [];

                        $newSql = "SELECT completed, QC_TARGET FROM new WHERE projectid = '$projectID'";
                        $newResult = $conn->query($newSql);

                        if ($newResult->num_rows > 0) {
                            while ($newRow = $newResult->fetch_assoc()) {
                                $completedValues[] = $newRow["completed"];
                                $qcTargetValues[] = $newRow["QC_TARGET"];
                            }
                        }

                        // Calculate and display the sum of completed and QC_TARGET values
                        $totalCompleted = array_sum($completedValues);
                        $totalQCTarget = array_sum($qcTargetValues);
                        $totalpages = $row["RECEIVEDPAGES"];
                        $pending_tl_work = (int)$totalpages - $totalCompleted;
                        $pending_qc_work = (int)$totalpages -  $totalQCTarget;

                        echo '<td>' . $totalCompleted . '</td>';
                        echo '<td>' . $totalQCTarget . '</td>';
                       echo '<td>' . $pending_tl_work . '</td>';

                       
if (($pending_tl_work < 0)||($pending_qc_work < 0)) {
    echo "<td><p style='color:red;font-size: 15px;font-weight: Bold;padding-top: 15px;'>The file is greater than Received pages </p></td>";
} else {
    echo "<td><p style='color:green;font-size: 15px;font-weight: Bold;padding-top: 15px;'>Congrat's! </p></td>";
}


                        echo "<td colspan='2';><button class='fetch-btn btn btn-primary' data-project-id='" . $row["PROJECTID"] . "' data-department='" . $row["DEPARTMENT"] . "' data-status= '" . $row['TL_STATUS'] ."'>Fetch</button></td>";

                       
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12'>No projects found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div id="second-table-container">       
  
        </div>
        
        

    </div>

    <!-- Your HTML code -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    
      function showLoadingOverlay() {
        $("body").addClass("loading");
        $("#loadingOverlay").fadeIn();
    }

    // Function to hide the loading overlay
    function hideLoadingOverlay() {
        $("body").removeClass("loading");
        $("#loadingOverlay").fadeOut();
    }

        // Add an event listener to all "Fetch" buttons
        $(document).on('click', '.fetch-btn', function() {
            var projectID = $(this).data('project-id');
            var department = $(this).data('department');
            var tlStatus = $(this).data('status');

            // Load the second table using AJAX
            $.ajax({
                type: 'POST',
                url: 'fetch_employee_data.php',
                data: { projectID: projectID, department: department, tlStatus: tlStatus }, // Corrected variable name
                success: function(data) {
                    $('#second-table-container').html(data);
                },
                error: function() {
                    alert('Error fetching data.');
                }
            });
        });
        

        // Add change event listener to the select tag
        $(document).on('change', '.status-dropdown', function() {
            var projectId = $(this).data('project-id');
            var newStatus = $(this).val();
            showLoadingOverlay();

            // Send an AJAX request to update the tl_status
            $.ajax({
                type: 'POST',
                url: 'update_tl_status.php',
                data: {
                    projectId: projectId,
                    tlStatus: newStatus
                },
                success: function(response) {
                    hideLoadingOverlay();
                    if (response === 'success') {
                        alert('Status updated successfully');

                    } else {
                        alert('Status update failed');
                    }
                },
                error: function() {
                    alert('Error updating status.');
                }
            });
        });
    </script>
</body>
</html>
