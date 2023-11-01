<?php
include "../includes/login_access.php";

$currentDate = date('Y-m-d'); // Get the current date

// Define a base SQL query
$sql_timeoff = "SELECT `ID`, `EMP_ID`, `EMPLOYEE_NAME`, `DEPARTMENT`, `FROM_DATE`, `TO_DATE`, `FULL_HALF_DAY`, `FISRT_SECOND_OFF`, `MESSAGE`, `STATUS_LEAVE` FROM `time_off_tracking`";

if ($user_position == "Employee" && $EMP_TEAMLEADER == "Yes") {
    // For Employee with Team Leader status, apply additional conditions
    $sql_conditions = "WHERE `DEPARTMENT` = '$EMP_DEPARTMENT'";

    if (isset($_POST['pending_button'])) {
        $sql_conditions .= " AND `STATUS_LEAVE` = 'Pending' ORDER BY `FROM_DATE` DESC";
    } elseif (isset($_POST['rejected_button'])) {
        $sql_conditions .= " AND `STATUS_LEAVE` = 'Rejected' ORDER BY `FROM_DATE` DESC";
    } elseif (isset($_POST['approved_button'])) {
        $sql_conditions .= " AND `STATUS_LEAVE` = 'Approved' ORDER BY `FROM_DATE` DESC";
    }
} elseif ($user_position == "General Manager" || $user_position == "Human Resource Manager") {
    // For General Manager and Human Resource Manager, no additional conditions
    $sql_timeoff = "SELECT `ID`, `EMP_ID`, `EMPLOYEE_NAME`, `DEPARTMENT`, `FROM_DATE`, `TO_DATE`, `FULL_HALF_DAY`, `FISRT_SECOND_OFF`, `MESSAGE`, `STATUS_LEAVE` FROM `time_off_tracking`";
 if (isset($_POST['pending_button'])) {
        $sql_conditions .= " AND `STATUS_LEAVE` = 'Pending' ORDER BY `FROM_DATE` DESC";
    } elseif (isset($_POST['rejected_button'])) {
        $sql_conditions .= " AND `STATUS_LEAVE` = 'Rejected' ORDER BY `FROM_DATE` DESC";
    } elseif (isset($_POST['approved_button'])) {
        $sql_conditions .= " AND `STATUS_LEAVE` = 'Approved' ORDER BY `FROM_DATE` DESC";
    }
} else {
    // Redirect to an unauthorized page for other users
    header("Location: error.php");
    exit;
}

// Finalize the SQL query with conditions
$sql_timeoff .= $sql_conditions;

// Execute the final query
$result = $conn->query($sql_timeoff);
?>


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
       .O {
        font-size: 20px !important;
        color: #0e649b;
        background-color: transparent;
    }hr {
    height: 1px;
    color: black;
}
.header_name{
    color:black;
}
</style>
<body>
<header style="" class="navbar_header_fix">
    <nav class="navbar" style="background-color: #e3f2fd;">
        <button class="navbar-toggler-icon navbar-toggler icon" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasDarkNavbar" style="color: black;"><i class="fa-solid fa-bars menu_icon"></i></button>
        <div class="container">
            <div class="company_name">
                <a class="navbar-brand" href="dashboard.php">
                    <img src="../assets/images/acslogo.png" alt="Bootstrap" width="100%" height="55px">
                </a>
            </div>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">ABOUT</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">CONTACT</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link active" data-bs-toggle="dropdown" href="profile.php" role="button" aria-expanded="false"
                        aria-current="page">PROFILE</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">ADMIN</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">LOGIN</a></li>
                    </ul>
                </li>
                <div class="icon_pro_bell">

                    <li class="nav-item dropdown">
                        <a class="nav-link active mg_left" data-bs-toggle="dropdown" role="button"
                            aria-expanded="false" aria-current="page" href="#"><i class="fa-regular fa-bell O "></i></a>
                        <ul class="dropdown-menu Profile_dropdown notification_bar">

                            <li class="notification_content">
                                <div class="" id="notificationContent">
                                    <hr class="dropdown-divider">
                                    Notification content goes here.
                                    You have a new notification!
                                    <hr class="dropdown-divider">
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <center><a class="dropdown-item btn_showall" type="button" href="">Show All</a></center>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link active mg_left" data-bs-toggle="dropdown" role="button"
                            aria-expanded="false" aria-current="page" href="#"><i class="far fa-user O"></i></a>
                        <ul class="dropdown-menu Profile_dropdown">
                            <li><a class="dropdown-item" href="#">Profile Details</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../includes/logout.php">Sign out</a></li>
                        </ul>
                    </li>
                </div>
            </ul>
        </div>
    </nav>
    <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar"
        aria-labelledby="offcanvasDarkNavbarLabel">
        <div class="offcanvas-header header_name">
            <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel"><?php echo $employeeName; ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"
                style="color: black;">
            </button>
        </div>
        <div class="offcanvas-body">

        <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <li class="nav-item">
                    <li><hr class="dropdown-divider"></li>
                    <a class="nav-link active" aria-current="page" href="dashboard.php">DASHBOARD</a>
                    <li><hr class="dropdown-divider"></li>
                </li>

                
                <li class="nav-item dropdown">
                <li><hr class="dropdown-divider"></li>
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        TIME OFF
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <?php if ($user_position == "General Manager" || $user_position == "Human Resource Manager" || $user_position == "Admin") { ?>
                            <li><a class="dropdown-item" href="timetracking.php">Requested Timeoff / My Report</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="time_off.php">Time Off</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="calander.php">Calendar</a></li>
                        <?php } else { ?>
                            <li><a class="dropdown-item" href="timetracking.php">Requested Timeoff / My Report</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="calander.php">Calendar</a></li>
                        <?php } ?>
                    </ul>
                    <li><hr class="dropdown-divider"></li>
                    
                </li>

                <?php if ($user_position == "General Manager" || $user_position == "Admin") { ?>
                
                   
                    <li class="nav-item active">
                    <li><hr class="dropdown-divider"></li>
                        <a class="nav-link" href="employee list.php">EMPLOYEE LIST'S</a>
                        
                    <li><hr class="dropdown-divider"></li>
                    </li>
                <?php } else
                {} ?>

                <li class="nav-item dropdown">
                <li><hr class="dropdown-divider"></li>
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        CANDIDATE'S LIST'S
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <?php if ($user_position == "Human Resource Manager" || $user_position == "Admin") { ?>
                            <li><a class="dropdown-item" href="#">EMPLOYEE</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">TRAINEE</a></li>
                        <?php } else { ?>
                            <li><p class="dropdown-item">Authorization Blocked by Admin</p></li>
                        <?php } ?>
                    </ul>
                    <li><hr class="dropdown-divider"></li>
                </li>

                
                <li class="nav-item dropdown">
                <li><hr class="dropdown-divider"></li>
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        SALARY
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <?php if ($user_position == "General Manager" || $user_position == "Human Resource Manager") { ?>
                            <li><a class="dropdown-item" href="#">EMPLOYEE</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">TRAINEE</a></li>
                        <?php } else { ?>
                            <li><p class="dropdown-item">Authorization Blocked by Admin</p></li>
                        <?php } ?>
                    </ul>
                    <li><hr class="dropdown-divider"></li>
                </li>

                <li class="nav-item dropdown">
                <li><hr class="dropdown-divider"></li>
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        PAYSLIP
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <?php if ($user_position == "General Manager" || $user_position == "Human Resource Manager" || $user_position == "Admin") { ?>
                            <li><a class="dropdown-item" href="#">EMPLOYEE</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">TRAINEE</a></li>
                        <?php } else { ?>
                            <li><p class="dropdown-item">Authorization Blocked by Admin</p></li>
                        <?php } ?>
                    </ul>
                    <li><hr class="dropdown-divider"></li>
                </li>

                <li class="nav-item dropdown">
                <li><hr class="dropdown-divider"></li>
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">WORK ASSIGN
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <?php if (($user_position == "Employee" && $EMP_TEAMLEADER == "YES") || $user_position == "Project Manager" || $user_position == "Trainner Department" || $user_position == "Operational Manager" || $user_position == "General Manager") { ?>
                            <li><a class="dropdown-item" href="tl_work.php">Assign</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="tl_view.php">View</a></li>
                        <?php } else { ?>
                            <li><p class="dropdown-item">Authorization Blocked by Admin</p></li>
                        <?php } ?>
                    </ul>
                <li><hr class="dropdown-divider"></li>
                </li>

                <li class="nav-item dropdown">
                <li><hr class="dropdown-divider"></li>
                    <a class="nav-link" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">PROJECTS</a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <?php if ($user_position == "Project Manager" || $user_position == "General Manager" || $user_position == "Operational Manager" || $user_position == "Admin") { ?>
                            <li><a class="dropdown-item" href="project.php">PROJECTS</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="project assign.php">VIEW DETAILS</a></li>
                        <?php } else { ?>
                            <li><p class="dropdown-item">Authorization Blocked by Admin</p></li>
                        <?php } ?>
                    </ul>
                <li><hr class="dropdown-divider"></li>
                </li>

                
                <?php if ($user_position == "Project Manager" || $user_position == "General Manager") { ?>

                    <li><hr class="dropdown-divider"></li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="report.php">REPORT</a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                <?php } else{
                    }?>


                <li class="nav-item dropdown">
               
                    <?php if ($user_position == "Accounts Manager" || $user_position == "General Manager") { ?>
                        <li><hr class="dropdown-divider"></li>
                        <a class="nav-link active" aria-current="page" href="accounts.php">ACCOUNTS</a>
                        <li><hr class="dropdown-divider"></li>
                    <?php } else { ?>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><p class="dropdown-item">Authorization Blocked by Admin</p></li>
                        </ul>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </nav>
</header>





<!-- ---------------------gjgufig-------------------- -->
    <div class=" container_time_off">
        <div class="row search_input">
     <div class="col-12 search_emp_row">
            <form action="time_off.php" class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" id="search" type="search" placeholder="Search by Name" aria-label="Search">

            </form>
        </div>

    </div>
<div class="tabless">

        <div class="row ">
          
            <div class="col-11">
            <div class="tables">

    <?php
    include "../connection.php";
    // Rest of your code to display the table
    if ($result->num_rows > 0) {
    ?>
    <div class="table_timeoff">
    <form action="../file_controler/total_controller.php" method="post">
        
            <table class="table_timeoff_leave">
                <thead>
                    <tr>
                        <th style="display:none">ID</th>
                        <th>EMP ID</th>
                        <th>Employee Name</th>
                        <th>Department</th>
                        <th colspan="2">Leave Date</th>
                        <th>Total days</th>
                        <th colspan="2">Full day / half day</th>
                        <th>Message</th>
                        <th>Status</th>                        
                        <th colspan="2" style="text-align:center;">Action</th>                        
                    </tr>
                </thead>
                <tbody id="tableBody">


<?php
while ($rows = $result->fetch_assoc()) {
    // Check if the status is not "Approved" to display action buttons
    if ($rows['STATUS_LEAVE'] !== 'Approved') {
        echo '<tr>';
        echo '<td style="display:none">' . $rows['ID'] . '</td>';
        echo '<td>' . $rows['EMP_ID'] . '</td>';
        echo '<td>' . $rows['EMPLOYEE_NAME'] . '</td>';
        echo '<td>' . $rows['DEPARTMENT'] . '</td>';
        echo '<td>' . $rows['FROM_DATE'] . '</td>';
        echo '<td> to ' . $rows['TO_DATE'] . '</td>';
        echo '<td>' . calculateTotalDays($rows['FROM_DATE'], $rows['TO_DATE']) . '</td>';
        echo '<td>' . $rows['FULL_HALF_DAY'] . '</td>';
        echo '<td>' . (($rows['FULL_HALF_DAY'] === 'Full Day') ? '0' : $rows['FISRT_SECOND_OFF']) . '</td>';
        echo '<td title="' . htmlspecialchars($rows['MESSAGE']) . '" onclick="showMessage(\'' . htmlspecialchars($rows['MESSAGE']) . '\')">' . substr($rows['MESSAGE'], 0, 5) . '</td>';
        echo '<td>' . $rows['STATUS_LEAVE'] . '</td>';
        echo '<td><button type="submit" name="check_icon_approve" value="' . $rows['ID'] . '" class="border btn icon-class approve-btn"><i class="fa-solid fa-check icon-class"></i></button></td>';
        echo '<td><button type="submit" name="mark_icon_reject" value="' . $rows['ID'] . '" class="border btn icon-class reject-btn"><i class="fa-solid fa-xmark icon-class"></i></button></td>';
        echo '</tr>';
    } else {
        echo '<tr>';
        echo '<td style="display:none">' . $rows['ID'] . '</td>';
        echo '<td>' . $rows['EMP_ID'] . '</td>';
        echo '<td>' . $rows['EMPLOYEE_NAME'] . '</td>';
        echo '<td>' . $rows['DEPARTMENT'] . '</td>';
        echo '<td>' . $rows['FROM_DATE'] . '</td>';
        echo '<td> to ' . $rows['TO_DATE'] . '</td>';
        echo '<td>' . calculateTotalDays($rows['FROM_DATE'], $rows['TO_DATE']) . '</td>';
        echo '<td>' . $rows['FULL_HALF_DAY'] . '</td>';
        echo '<td>' . (($rows['FULL_HALF_DAY'] === 'Full Day') ? '0' : $rows['FISRT_SECOND_OFF']) . '</td>';
        echo '<td title="' . htmlspecialchars($rows['MESSAGE']) . '" onclick="showMessage(\'' . htmlspecialchars($rows['MESSAGE']) . '\')">' . substr($rows['MESSAGE'], 0, 5) . '</td>';
        echo '<td>' . $rows['STATUS_LEAVE'] . '</td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '</tr>';
    }
}
?>




                </tbody>
            </table>
        </form>
    </div>
    <?php
    } else {
    ?>
    <table class="table_timeoff_leave">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>EMP ID</th>
                        <th>Employee Name</th>
                        <th colspan="2">Leave Date</th>
                        <th>Total days</th>
                        <th colspan="2">Full day / half day</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th colspan="3">Action</th> <!-- Always show the "Action" column -->
                    </tr>
                </thead>
                <tbody>
    <td colspan="9"><p>No records Found</p></td>
    <?php
    }
    function calculateTotalDays($fromDate, $toDate) {
        $fromDate = new DateTime($fromDate);
        $toDate = new DateTime($toDate);
        $interval = $fromDate->diff($toDate);
        return $interval->days;
    }
    ?>
</tbody></table>
</div>
</div>



<div class="col-1">   

<!-- 
    <label for="date_filter">Date Filter:</label>
    <select name="date_filter" id="date_filter" onchange="updateDateInputs()">
    <option value="today" selected>Today</option>
    <option value="yesterday">Yesterday</option>
    <option value="last_week">Last Week</option>
    <option value="custom">Custom</option>
</select>
<div id="custom_date_range" style="display: none;">
    <label for="start_date">Start Date:</label>
    <input type="date" name="start_date" id="start_date"/>
    <label for="end_date">End Date:</label>
    <input type="date" name="end_date" id="end_date"/></div>


<input type="button" name="filter_by_date" value="Filter by Date" onclick="filterByDate()">  -->


                <form class="filtering" action="" method="post">            
                    <input type="submit" name="all_button" value="ALL"/>
                    <input type="submit" name="pending_button" value="PENDING"/>
                    <input type="submit" name="rejected_button" value="REJECTED"/>
                    <input type="submit" name="approved_button" value="APPROVED"/>
</form>
</div>
</div>
</div>
</div>



                    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="fullMessage"></p>
        </div>
    </div>
    <div id="suggestions" class="suggestions"></div>
                </form>
            </div>

        </div>
    </div>
</div>
    <script>
        // JavaScript function to show the full message
        function showMessage(message) {
            // Get the modal and message elements
            var modal = document.getElementById('messageModal');
            var messageContent = document.getElementById('fullMessage');

            // Set the message content
            messageContent.innerHTML = message;

            // Show the modal
            modal.style.display = 'block';

            // Close the modal when the user clicks the close button
            var closeBtn = document.getElementsByClassName('close')[0];
            closeBtn.onclick = function() {
                modal.style.display = 'none';
            }

            // Close the modal when the user clicks outside of it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        }
    </script>
<script> 
    function filterRows() {
        const searchTerm = $('#search').val().trim().toLowerCase();

        $('#tableBody tr').each(function() {
            const row = $(this);
            const employeeNameCell = row.find('td:nth-child(3)'); // 3rd column contains Employee Name
            const employeeName = employeeNameCell.text().toLowerCase();

            if (employeeName.includes(searchTerm)) {
                row.show();
            } else {
                row.hide();
            }
        });
    }
    // Initial filter on page load
    filterRows();
    // Search input event listener
    $('#search').on('input', function() {
        // Call the filter function when the search input changes
        filterRows();
    });
</script>

<script>
$(document).ready(function() {
    // Event listener for the date filter select
    $('#date_filter').change(function() {
        var selectedFilter = $(this).val();
        
        // Hide or show date range inputs based on the selected filter
        if (selectedFilter === 'custom') {
            $('#custom_date_range').show();
        } else {
            $('#custom_date_range').hide();
        }
    });
});
</script>


<script>
function filterByDate() {
    // Get the selected date range or custom dates
    var dateFilter = document.getElementById("date_filter").value;
    var startDate = document.getElementById("start_date").value;
    var endDate = document.getElementById("end_date").value;
    // Loop through each row in the table body
    $('#tableBody tr').each(function () {
        var row = $(this);
        var fromDate = row.find('td:nth-child(4)').text(); // 4th column contains FROM_DATE
        var toDate = row.find('td:nth-child(5)').text();   // 5th column contains TO_DATE

        // Convert FROM_DATE and TO_DATE to Date objects
        var fromDateObj = new Date(fromDate);
        var toDateObj = new Date(toDate);
        // Check if the row should be displayed based on the selected date filter
        var displayRow = false;
        if (dateFilter === "today") {
            var today = new Date().toLocaleDateString();
            displayRow = fromDateObj.toLocaleDateString() === today || toDateObj.toLocaleDateString() === today;
        } else if (dateFilter === "yesterday") {
            var yesterday = new Date();
            yesterday.setDate(yesterday.getDate() - 1);
            displayRow = fromDateObj.toLocaleDateString() === yesterday.toLocaleDateString() || toDateObj.toLocaleDateString() === yesterday.toLocaleDateString();
        } else if (dateFilter === "last_week") {
            var lastWeek = new Date();
            lastWeek.setDate(lastWeek.getDate() - 7);
            displayRow = fromDateObj >= lastWeek;
        } else if (dateFilter === "custom") {
            // Check if the dates are within the custom range
            var customStartDate = new Date(startDate);
            var customEndDate = new Date(endDate);
            displayRow = fromDateObj >= customStartDate && toDateObj <= customEndDate;
        } else {
            // Default to displaying the row if date filter is not recognized
            displayRow = true;
        }
        // Show or hide the row based on the displayRow flag
        if (displayRow) {
            row.show();
        } else {
            row.hide();
        }
    });
}
</script> 


</body>
</html>
