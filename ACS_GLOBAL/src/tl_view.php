<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b272402e67.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/styless.css">
</head>
<style>
.asdf {
    padding-right: 2%;
    padding-left: 2%;
}

.tl_view {
    width: 28%;
    margin: 8% 1% 0% 0%;

}
.tl_work_trash{
    color:#0e649b;
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
  background: linear-gradient(180deg, #f8f9fa, #0e649b);
  color: white;
  z-index:3;
}
thead th {
  text-transform: uppercase;
  height: 40px;
  z-index: 2; 
  border:1px solid transparent;
  border:#000 #0e649b;
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
}
.filter_options {
    display: none;
}.tl_work-depart {
    font-size: 25px;
    height: 20px;
    width: max-content;
    color: #0e649b;
    font-weight: bold;
}.project-id {
    margin: 30px 0px 15px 50px;
}
.grey, .grey:hover {
    height: 0px;
    background-color: #e3ddd93d;
    color: black;
    border: 1px solid;
    width: max-content;
    text-align: center;
    border-color: #0e649b #fff;
}

</style>
<body>
<?php
// Include your PHP code for database connection and other functions here
include "../includes/header.php";

function populateDropdown($sql, $columnName)
{
    include "../includes/connection.php"; // Include your database connection code
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $value = $row[$columnName];
            echo "<option value='$value'>$value</option>";
        }
    } else {
        echo "<option value=''>No options found</option>";
    }
   // Close the database connection
}
$rows = []; 
if (($user_position === 'Project Manager') || ($user_position === 'General Manager') || ($user_position === 'Operational Manager') || ($user_position === 'Trainner Department')) {
    $sql = "SELECT n.date, n.tl, n.employeeid, n.employeename, n.department,n.qc_target, n.projectid, n.target, n.pending, n.completed, p.BATCHNUMBER, p.WORKTITLE, p.RECEIVEDPAGES, p.STATUS, p.TL_STATUS 
            FROM new n
            LEFT JOIN projects p ON n.projectid = p.projectid
            WHERE (p.TL_STATUS != 'completed') AND p.STATUS != 'Hold' AND p.STATUS != 'Completed'";
}
elseif (($user_position === 'Employee') && ($EMP_TEAMLEADER === 'Yes')) {
    // Ensure you properly escape the department variable in the query
    $department = mysqli_real_escape_string($conn, $EMP_DEPARTMENT);
    $sql = "SELECT n.date, n.tl, n.employeeid, n.employeename, n.department,n.qc_target, n.projectid, n.target, n.pending, n.completed, p.BATCHNUMBER, p.WORKTITLE, p.RECEIVEDPAGES, p.STATUS, p.TL_STATUS 
            FROM new n
            LEFT JOIN projects p ON n.projectid = p.projectid
            WHERE n.projectid IS NOT NULL 
            AND n.status != 'completed'
            AND (p.TL_STATUS IS NULL OR p.TL_STATUS != 'completed') AND n.department = '$department'";
} else {
    include_once "../includes/error.php";
}

if (isset($sql)) {
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error: " . $conn->error);
    }
    $rows = $result->fetch_all(MYSQLI_ASSOC);
}
?>
    <center>
    <div class="tl_view">
            <div class="input-group mb-3">
                <input type="text" id="searchBox" class="form-control" placeholder="Search by Project ID or Client Name" aria-label="Recipient's username" aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary filter_style" type="button" id="toggle-filter" name="submit_filter"><i class="fa-brands fa-searchengin"></i></button>
            </div>
            </div>
        </center>

<div class="tl_work-heading">
    <?php 
    if (($user_position === 'Employee') && ($EMP_TEAMLEADER === 'Yes')){ ?>
        <center><div class="tl_work-depart"><?php echo $row['department']; ?></div></center> 
    <?php } else {?>
        <center><div class="tl_work-depart"><?php echo "Welcome! $user_position" ?></div></center>
    <?php
    }?>
</div>

<?php
$currentProjectId = null; // Initialize variable to track current project ID
$totalPages = 0;
$completedValue = 0;
$qccompleted = 0;
foreach ($rows as $row) {
    $projectid = $row['projectid'];
    // Check if the project ID has changed, and if so, create a new table
    if ($projectid !== $currentProjectId) {
        // Close the previous table if it exists
        if ($currentProjectId !== null) {
            echo '<tr class="grey">';
            echo '<td class="center" colspan="2">TOTAL PAGES : ' . $totalPages . '</td>';
            echo '<td class="center" colspan="3">TOTAL COMPLETED : ' . $completedValue . '</td>';
            echo '<td class="center" colspan="3">TOTAL QC COMPLETED : ' . $qccompleted . '</td>';          
            echo '<td class="center" colspan="3">TOTAL PENDING : ' . ($totalPages - $completedValue) . '</td>';
            echo '</tr>';
            echo '</tbody></table></div></div>';
            $totalPages = 0;
            $completedValue = 0;
            $qccompleted = 0;
        }
        // Create a new table for the current project ID
        $currentProjectId = $projectid;
        ?>
        <div class="project-id" style="font-weight: bold;">
                Project ID: <?php echo $projectid; ?>
            </div>                
            <div class="asdf">
                <table border="1">
                    <thead>
                        
                        <tr>
                            <th hidden>Date</th>
                            <th hidden>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Department</th>
                            <th>Batch Number</th>
                            <th>Project ID</th>
                            <th>Target</th>
                            <th>Pending</th>
                            <th>Qc Completed</th>
                            <th>File Completed</th>                                    
                            <th>EMP Status</th>
                            <th hidden>TL Status</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
    }
    // Update total values for each row
    $totalPages += $row['RECEIVEDPAGES'];
    $completedValue += $row['completed'];
    $qccompleted += empty($row['qc_target']) ? 0 : $row['qc_target'];
?>
    <tr>
        <td hidden><?php echo $row['date']; ?></td>
        <td hidden><?php echo $row['employeeid']; ?></td>
        <td><?php echo $row['employeename']; ?></td>
        <td><?php echo $row['department']; ?></td>
        <td><?php echo $row['BATCHNUMBER']; ?></td>
        <td><?php echo $row['projectid']; ?></td>
        <td><?php echo $row['target']; ?></td>
        <td><?php echo $row['pending']; ?></td>
        <td><?php echo empty($row['qc_target']) ? 0 : $row['qc_target']; ?></td>
        <td><?php echo $row['completed']; ?></td>            
        <td><?php echo $row['STATUS']; ?></td>
        <td hidden><?php echo $row['TL_STATUS']; ?></td>
    </tr>
<?php
}        
// Close the last table if it exists
if ($currentProjectId !== null) { 
    echo '<tr class="grey">';
    echo '<td class="center" colspan="2">TOTAL PAGES : ' . $totalPages . '</td>';
    echo '<td class="center" colspan="3">TOTAL COMPLETED : ' . $completedValue . '</td>';
    echo '<td class="center" colspan="3">TOTAL QC COMPLETED : ' . $qccompleted . '</td>';     
    echo '<td class="center" colspan="3">TOTAL PENDING : ' . ($totalPages - $completedValue) . '</td>';
    echo '</tr>';
    echo '</tbody></table></div></div>';
}
?>


<script>
$('#searchBox').on('input', function () {
    var searchText = $(this).val().toLowerCase();

    $('.project-id').each(function () {
        var projectContainer = $(this).next('.asdf');
        var found = false;

        projectContainer.find('tbody tr').each(function () {
            var row = $(this);
            var searchData = row.find('td').text().toLowerCase();
            if (searchData.includes(searchText)) {
                row.show();
                found = true;
            } else {
                row.hide();
            }
        });

        if (found) {
            projectContainer.show(); // Show the table container for this project
        } else {
            projectContainer.hide(); // Hide the table container for this project
        }
    });
});
</script>

</body>
</html>
