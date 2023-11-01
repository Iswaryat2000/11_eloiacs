
<?php
include_once "../includes/login_access.php"; 
date_default_timezone_set('Asia/Kolkata');
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s'); // Define $currentTime here

if (isset($_SESSION['hasClockedIn'])) {
    $hasClockedIn = $_SESSION['hasClockedIn'];
    $clkintime = $_SESSION['clkintime'];
} else {
    $hasClockedIn = false;
    if (isset($_COOKIE['hasClockedIn']) && $_COOKIE['hasClockedIn'] === 'true') {
        $hasClockedIn = true;
    }
    
    // Check if the employee has already clocked in for the day
    $select_query = "SELECT CLOCKIN FROM tl_attendance WHERE EMPCODE = '$employeeCode' AND CRNT_DATE = '$currentDate'";
    $result = mysqli_query($conn, $select_query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $clkintime = $row['CLOCKIN'];
        $hasClockedIn = true;
    } else {
        $hasClockedIn = false;
        $clkintime = "9.00 am";
    }

    $_SESSION['hasClockedIn'] = $hasClockedIn;
    $_SESSION['clkintime'] = $clkintime;
}

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
    <link rel="stylesheet" href="../css/dashboard_pro.css">
    <link rel="stylesheet" href="../css/styless.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  
                    <style>
                    .first_hey {
width:25% !important;
}
                    *{
                        font-size:15px;
                    }
                        .dash_box_container {
                    box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;
                    height: 250px;
                    background-color: white;
                    margin:110px 0px 0px 0px;
                }
                button a{text-decoration:none;
                    color:white;
                }

                button:hover a{text-decoration:none;
                    color:darkblue;
                }
                .image_calendar {
                    width: 85%;
                    margin: -23px 0px 10px 42px;
                }
                button#tracking_report {
                    margin-top: 2%;
                }
                .alert {
    position: relative;
    padding: 0rem 0rem;
    margin-bottom: 0rem;
    border: 1px solid transparent;
    border-radius: 0.25rem;
    font-size: 12px;
    width: fit-content;

}
                    </style>
</head>
                <body>
                    <!-- Include the header section -->
                    <?php require_once "../includes/header.php";

                        // Fetch "PROD" projects
                        $select_project_PROD = "SELECT n.`employeename`, n.`department`, n.`projectid`, n.`target`, n.`pending`, n.`completed`, n.`totalpages`, n.`status`, n.`batchnumber`, n.`prod_qc`, p.`OURBATCH`, p.`BATCHNUMBER`, p.`ISBNNUMBER`, p.`File_target`, p.`RECEIVEDPAGES`,p.`WORKTITLE`, p.`QC_TARGET`, p.`TL_STATUS`
                        FROM new AS n
                        LEFT JOIN projects AS p ON n.`projectid` = p.`PROJECTID`
                        WHERE n.`employeename` = '$employeeName' AND p.`TL_STATUS` NOT IN ('Completed', 'Hold') AND n.`prod_qc` = 'PROD'";

                        $result_PROD = $conn->query($select_project_PROD);

                        // Fetch "QC" projects
                        $select_project_QC = "SELECT n.`employeename`, n.`department`, n.`projectid`, n.`target`, n.`pending`, n.`completed`, n.`totalpages`, n.`status`, n.`batchnumber`, n.`prod_qc`, p.`OURBATCH`, p.`BATCHNUMBER`, p.`ISBNNUMBER`, p.`File_target`,p.`QC_TARGET`, p.`RECEIVEDPAGES`,p.`WORKTITLE`, p.`QC_TARGET`, p.`TL_STATUS`
                        FROM new AS n
                        LEFT JOIN projects AS p ON n.`projectid` = p.`PROJECTID`
                        WHERE n.`employeename` = '$employeeName' AND p.`TL_STATUS` NOT IN ('Completed', 'Hold') AND n.`prod_qc` = 'QC'";

                        $result_QC = $conn->query($select_project_QC);    
                            ?>
                            <?php
                        $time = date('H'); // Get the current hour (in 24-hour format)
                        if ($time < 11) {
                        $greeting = "Good Morning";
                        } elseif ($time < 15) {
                        $greeting = "Good Afternoon";
                        } else {
                        $greeting = "Good Evening";
                        }
                        ?>
                    <!-- Your content goes here -->
                    <div class="dash_clk">
                        <div class="dash_box_container">
                        <div class="line-segment"></div>
                        <div class="line-segment"></div>
                        <div class="line-segment"></div>
                        <div class="line-segment"></div>
                            <div class="clock_in">
                                <form action="../file_controler/form_controller.php" method="post">
                                    <p class="clk_text task_content"><span class="emoji">😇</span><?php echo $greeting; ?>.....<input class="emp_name_dashboard b_dash first_hey" name="EMPname" value="<?php echo "$employeeName"; ?>"  READONLY></input>
                                        <input class="emp_code_dashboard b_dash tsk_dash" name="EMPcode" style="display:none;" value="<?php echo "$employeeCode"; ?>" READONLY></input>
                                        <br>Time to rock this day with unstoppable energy! Let's go<span class="emoji">✌️</span></p>
                                        <!-- <br>Your workday begins now. Ready to seize the day?👉</p> -->
                                    <div class="input-group mb-3">
                                        <div class="row">
<div class="col-3">
    <p class="current_time_date"><?php echo date('d-Y-m'); ?><span class="time_dashbrd" id="currentTime"></span> <span class="time_dashbrd"><i class="far fa-clock"></i></span></p>
</form>
    <?php if (isset($successMessage)) : ?>
        <center><div id="successMessage" class="alert alert-success">
            <?php echo $successMessage; ?>
        </div></center>
    <?php endif; ?>

    <?php
    
    if (!empty($clockInBlocked)) : ?>
        <p class="alert alert-danger">Clock In Blocked</p>
        
    <?php else : ?>
    
        <?php if ($hasClockedIn) : ?>
            <button class="btn btn-outline-secondary" id="clock-in" type="submit" name="clock_in_btn" style="display:none;">Clock in</button>
            <button class="btn btn-outline-secondary" id="clock-out" type="submit" name="clock_out_btn">Clock out</button>
        <?php else : ?>
            <button class="btn btn-outline-secondary" id="clock-in" type="submit" name="clock_in_btn">Clock in</button>
            <button class="btn btn-outline-secondary" id="clock-out" type="submit" name="clock_out_btn" style="display:none;">Clock out</button>
        <?php endif; ?>
    <?php endif; ?>
</div>
                                            <div class="col-1" style="border-left: 2px solid #0e649b; height: 120px;"></div>
                                            <div class="col-4">
                                                <p class="right_box_clockin">Your login time <?php echo $clkintime; ?> / Working hours 9.00 am to 6.00 pm</p>
                                                <button class="btn btn-outline-secondary tracking_report_dash" id="tracking_report" type="button"><a href="timetracking.php">My time tracking report</a></button>
                                            </div>
                                            <div class="col-4">
                                                <p class="image_calendar"> <img src="../assets/images/calendar-image.png" class="image_calendar" alt="" srcset=""/></p>
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                        </div>    
        <div class="work_report_assign">
            <!-- table 1 -->
        <form action="../file_controler/total_controller.php" method="POST">
            <p class="task_content">Hey! <span class="first_hey"><?php echo $employeeName; ?></span> <span class="tsk_dash" style="font-size:18px;"><em><b>" your task for today awaits...  Best of luck!" <span class="emoji">👍</span></b></em></span></p>
            <input type="hidden" name="employeeName" value="<?php echo $employeeName; ?>"/>
            <div class="prod_file" <?php if ($result_PROD->num_rows === 0) echo 'style="display: none;"'; ?>>


                    <?php if ($result_PROD->num_rows > 0) { ?>
                            <h4 class="h3_heading-table">PRODUCTION Projects </h4>
                            <div class="table_scroll_dashb">
                            <table class="table_1-dashboard">
                        <thead class="head_position-fix">
                            <tr class="qc-file-row">
                                <th class="hidden prod_qc_th">Employee Name</th>
                                <th class="prod_qc_th">Project Id</th>
                                <th class="hidden prod_qc_th">totalpages</th>
                                <th class="prod_qc_th">Book title</th>
                                <th class="prod_qc_th">Batch Number</th>
                                <th <?php echo ($EMP_DEPARTMENT === 'XML') ? '' : 'class="hidden"'; ?>>ISBN Number</th>
                                <th class="prod_qc_th">Department</th>
                                <th class="prod_qc_th">Target</th>
                                <th class="prod_qc_th">Completed</th>
                                <th class="prod_qc_th">Pending</th>
                                <th class="prod_qc_th">EMP status</th>
                                <th class="prod_qc_th">Action</th>
                                <th class="hidden prod_qc_th">OURBATCH</th>
                                <th class="hidden prod_qc_th">TL Status</th>
                                <th class="hidden prod_qc_th">PROD/QC</th>
                            </tr>  
                        </thead>
                        <?php
                        if ($result_PROD->num_rows > 0) {
                            while ($row = $result_PROD->fetch_assoc()) {
                                echo "<tr class='prod-file-row tr_tbody_row'>";
                                echo "<td class='hidden prod_qc_td'><input type='text' class='' name='employeename' value='" . $row["employeename"] . "'></td>";
                                echo "<td class='prod_qc_td'>" . $row["projectid"] . "</td>";
                                echo "<td class='hidden prod_qc_td'>" . $row["totalpages"] . "</td>";
                                echo "<td class='prod_qc_td'>" . $row["WORKTITLE"] . "</td>";
                                echo "<td class='prod_qc_td'>" . $row["BATCHNUMBER"] . "</td>";
                                if ($EMP_DEPARTMENT === 'XML') {
                                    echo "<td class='prod_qc_td'>" . $row["ISBNNUMBER"] . "</td>";

                                } else{
                                    echo "<td class='hidden prod_qc_td'></td>";
                                }
                                echo "<td class='prod_qc_td'>" . $row["department"] . "</td>";
                                echo "<td class='prod_qc_td'><input readonly type='text' class='target-input'name='new_target_PROD[]' value='" . $row["File_target"] . "'></td>";
                                echo "<td class='prod_qc_td'><input type='text'class='completed-input' name='new_completed_PROD[]' value='" . $row["completed"] . "'></td>";
                                echo "<td class='prod_qc_td'><input readonly type='text' class='pending-input'  name='new_pending_PROD[]' value='" . $row["pending"] . "'></td>";
                              
                                echo '<td>';
                                echo '<select id="status-dropdown" name="prod_empstatus[]">'; // Note: Change the name to an array
                                echo '<option value="" disabled>----------------</option>';
                                
                                // Fetch status options from the database
                                $sql = "SELECT `STATUS` FROM `department_company` WHERE `STATUS` IS NOT NULL";
                                $statusResult = $conn->query($sql);
                                
                                if ($statusResult->num_rows > 0) {
                                    while ($statusRow = $statusResult->fetch_assoc()) {
                                        $statusOption = $statusRow['STATUS'];
                                        $selected = ($row['status'] == $statusOption) ? 'selected' : '';
                                        echo '<option value="' . $statusOption . '" ' . $selected . '>' . $statusOption . '</option>';
                                    }
                                } else {
                                    echo '<option value="No department found">No department found</option>';
                                }
                                
                                echo '</select>';
                                echo '</td>'; 
                                
                                
                                echo '<input type="hidden" name="projectid_PROD[]" value="' . $row["projectid"] . '">';
                                echo '<td class="prod_qc_td"><button type="submit" class="btn btn-primary btn_qa_dashboard" name="save_prod[' . $row["projectid"] . ']">Save</button></td>';
                                echo "<td class='hidden prod_qc_td'>" . $row["OURBATCH"] . "</td>";
                                echo "<td class='hidden prod_qc_td'>" . $row["TL_STATUS"] . "</td>";
                                echo "<td class='hidden prod_qc_td'>" . $row["prod_qc"] . "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                        <?php } ?>
                    </table>
                    </div>
                    </div>
                    <!-- table 2 -->
                    <div class="qc_file" <?php if ($result_QC->num_rows === 0) echo 'style="display: none;"'; ?>>
    <?php if ($result_QC->num_rows > 0) { ?>
    <h4 class="h3_heading-table">QC Projects</h4>
    <input type="hidden" name="employeeName" value="<?php echo $employeeName; ?>" />
    <!-- 2nd table -->
    <div class="table_scroll_dashb">
        <form method="post" action="">
            <table class="table_1-dashboard">
                <tr class="qc-file-row">
                    <th class="hidden prod_qc_th">Employee Name</th>
                    <th class="prod_qc_th">Project Id</th>
                    <th class="hidden prod_qc_th">totalpages</th>
                    <th class="prod_qc_th">Book title</th>
                    <th class="prod_qc_th">Batch Number</th>
                    <th <?php echo ($EMP_DEPARTMENT === 'XML') ? '' : 'class="hidden"'; ?>>ISBN Number</th>
                    <th class="prod_qc_th">Department</th>
                    <th class="prod_qc_th">Target</th>
                    <th class="prod_qc_th">Completed</th>
                    <th class="prod_qc_th">Pending</th>
                    <th class="prod_qc_th">EMP status</th>
                    <th class="prod_qc_th">Action</th>
                    <th class="hidden prod_qc_th">OURBATCH</th>
                    <th class="hidden prod_qc_th">TL Status</th>
                    <th class="hidden prod_qc_th">PROD/QC</th>
                </tr>
                <?php
                if ($result_QC->num_rows > 0) {
                    while ($row = $result_QC->fetch_assoc()) {
                        echo "<tr class='qc-file-row tr_tbody_row'>";
                        echo "<td class='hidden prod_qc_td'><input type='text' name='employeename' value='" . $row["employeename"] . "'></td>";
                        echo "<td class='prod_qc_td'>" . $row["projectid"] . "</td>";
                        echo "<td class='hidden prod_qc_td'>" . $row["totalpages"] . "</td>";
                        echo "<td class='prod_qc_td'>" . $row["WORKTITLE"] . "</td>";
                        echo "<td class='prod_qc_td'>" . $row["BATCHNUMBER"] . "</td>";
                        if ($EMP_DEPARTMENT === 'XML') {
                            echo "<td class='prod_qc_td'>" . $row["ISBNNUMBER"] . "</td>";
                        } else {
                            echo "<td class='hidden prod_qc_td'></td>";
                        }
                        echo "<td class='prod_qc_td'>" . $row["department"] . "</td>";
                        echo "<td class='prod_qc_td'><input type='text' class='target-input_qc' name='new_target_QC[" . $row["projectid"] . "]' value='" . $row["QC_TARGET"] . "' readonly></td>";
                        echo "<td class='prod_qc_td'><input type='text' class='completed-input_qc' name='new_completed_QC[" . $row["projectid"] . "]' value='" . $row["completed"] . "'></td>";
                        echo "<td class='prod_qc_td'><input type='text' class='pending-input_qc' name='new_pending_QC[" . $row["projectid"] . "]' value='" . $row["pending"] . "' readonly></td>";
                       
                         
                      $projectid = $row['projectid'];

                        echo '<td>';
echo '<select id="status-dropdown" name="qc_empstatus[' . $projectid. '][]">'; // Note: Change the name to an array
echo '<option value="" disabled>----------------</option>';

// Fetch status options from the database
$sql = "SELECT `STATUS` FROM `department_company` WHERE `STATUS` IS NOT NULL";
$statusResult = $conn->query($sql);

if ($statusResult->num_rows > 0) {
    while ($statusRow = $statusResult->fetch_assoc()) {
        $statusOption = $statusRow['STATUS'];
        $selected = (is_array($_POST['qc_empstatus'][$projectid]) && in_array($statusOption, $_POST['qc_empstatus'][$projectid])) ? 'selected' : '';
        echo '<option value="' . $statusOption . '" ' . $selected . '>' . $statusOption . '</option>';
    }
} else {
    echo '<option value="No department found">No department found</option>';
}

echo '</select>';
echo '</td>';

                        
                       
                       
                    
                        echo '<input type="hidden" name="projectid_QC[]" value="' . $row["projectid"] . '">';
                        echo "<td class='prod_qc_td'><button type='submit' class='btn btn-primary btn_qa_dashboard' name='save_qc[" . $row['projectid'] . "]'>Save</button></td>";
                        echo "<td class='hidden prod_qc_td'>" . $row["OURBATCH"] . "</td>";
                        echo "<td class='hidden prod_qc_td'>" . $row["TL_STATUS"] . "</td>";
                        echo "<td class='hidden prod_qc_td'>" . $row["prod_qc"] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </form>
    </div>
    <?php } else {
        echo "<p>No records found</p>";
    } ?>
</div>

           
</body>
    <div class="authorization_footer_if">
        <?php  require_once "../includes/footer.php";?>
    </div>   
<script src="../js/dashboard.js"></script>


<script>

// Function to calculate the distance between two sets of latitude and longitude coordinates
function calculateDistance(lat1, lon1, lat2, lon2) {
    const earthRadius = 6371000; // Radius of the Earth in meters
    const dLat = (lat2 - lat1) * (Math.PI / 180);
    const dLon = (lon2 - lon1) * (Math.PI / 180);
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const distance = earthRadius * c; // Distance in meters
    return distance;
}

// function checkLocationPermission() {
//     navigator.permissions.query({ name: 'geolocation' }).then(permissionStatus => {
//         if (permissionStatus.state === 'granted') {
//             // User has granted location access, check user's location
//             checkUserLocation();
//         } else {
//             // User has not granted location access, hide the button
//             document.getElementById('clock-in').style.display = 'BLOCK';
//         }
//     });
// }

function checkUserLocation() {
    const clockInLat = 8.174358888532955;
    const clockInLon = 77.42424829686452;
    navigator.geolocation.getCurrentPosition(position => {
        const userLat = position.coords.latitude;
        const userLon = position.coords.longitude;
        const minDistance = 200; // Minimum distance in meters
        const maxDistance = 500; // Maximum distance in meters

        const distance = calculateDistance(userLat, userLon, clockInLat, clockInLon);
        const isMobile = window.innerWidth <= 768; // Adjust the width as needed for your specific mobile breakpoint

        // Check whether the user has already clocked in
        const hasClockedIn = <?php echo json_encode($_SESSION['hasClockedIn']); ?>;
        
        if (hasClockedIn) {
            // User has already clocked in, hide the "Clock In" button
            document.getElementById('clock-in').style.display = 'none';
            document.getElementById('clock-out').style.display = 'block';
        } else {
            // User has not clocked in, show the "Clock In" button based on location
            if (isMobile) {
                if (distance >= minDistance && distance <= maxDistance) {
                    // User is within the radius on a mobile device, show the "Clock In" button
                    document.getElementById('clock-in').style.display = 'block';
                } else {
                    // User is outside the radius on a mobile device, hide the "Clock In" button
                    document.getElementById('clock-in').style.display = 'none';
                }
            } else {
                // For non-mobile devices, always show the "Clock In" button
                document.getElementById('clock-in').style.display = 'block';
            }
        }
    });
}
checkLocationPermission();
</script>

</html>
