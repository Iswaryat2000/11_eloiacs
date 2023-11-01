
<?php include_once "includes/login_access.php"; ?>

<?php
session_start(); // Start a PHP session
include "includes/connection.php"; // Include your database connection file

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



<?php
// Handle clock-in and clock-out
if (isset($_POST['clock_in_btn'])) {
    // Handle clock-in
    $employeeName = $_POST['EMPname'];
    $employeeCode = $_POST['EMPcode'];
    date_default_timezone_set('Asia/Kolkata');
    $currentTime = date('h:i a');
    $currentDate = date('Y-m-d');

    // Insert data into tl_attendance table
    $insert_query = "INSERT INTO tl_attendance (EMPNAME, EMPCODE, CRNT_DATE, CLOCKIN, DEL_STATUS) VALUES ('$employeeName', '$employeeCode', '$currentDate', '$currentTime', '0')";

    if (mysqli_query($conn, $insert_query)) {
        // After successful clock-in, update the session variables
        $_SESSION['hasClockedIn'] = true;
        $_SESSION['clkintime'] = $currentTime;
        echo "<script>window.location.href = 'ACS/dashboard.php';</script>";
        $successMessage = "Clock In Successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} elseif (isset($_POST['clock_out_btn'])) {
    // Handle clock-out
    $employeeCode = $_POST['EMPcode'];
    date_default_timezone_set('Asia/Kolkata');
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i a'); // Save the time in 12-hour format with AM/PM

    // Update the corresponding row for the employee in tl_attendance table
    $update_query = "UPDATE tl_attendance SET CLOCKOUT = '$currentTime' WHERE EMPCODE = '$employeeCode' AND CRNT_DATE = '$currentDate'";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['hasClockedIn'] = false;
        echo "<script>window.location.href = 'ACS/dashboard.php';</script>";
        $successMessage = "Clock Out Successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
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
    <link rel="stylesheet" href="css/dashboard_pro.css">
    <link rel="stylesheet" href="css/styless.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
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
    </style>
</head>
<body>
    <!-- Include the header section -->
    <?php require_once "ACS/includes/header.php";
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

        <div class="geoclok in">
            <h1>Get Current Location</h1>
                <div id="location">
                    Latitude: <span id="lat"></span><br>
                    Longitude: <span id="lon"></span>
                </div>
        </div>

        <div class="dash_box_container">
        <div class="line-segment"></div>
        <div class="line-segment"></div>
        <div class="line-segment"></div>
        <div class="line-segment"></div>
            <div class="clock_in">
                <form action="" method="post">

                    <p class="clk_text task_content"><span class="emoji">😇</span><?php echo $greeting; ?>.....<input class="emp_name_dashboard b_dash first_hey" name="EMPname" value="<?php echo "$employeeName"; ?>"  READONLY></input>
                        <input class="emp_code_dashboard b_dash tsk_dash" name="EMPcode" style="display:none;" value="<?php echo "$employeeCode"; ?>" READONLY></input>
                        <br>Time to rock this day with unstoppable energy! Let's go<span class="emoji">✌️</span></p>
                        <!-- <br>Your workday begins now. Ready to seize the day?👉</p> -->
                    <div class="input-group mb-3">
                        <div class="row">
                            <div class="col-3">
                                <p class="current_time_date"><?php echo date('d-Y-m'); ?><span class="time_dashbrd" id="currentTime"></span> <span class="time_dashbrd"><i class="far fa-clock"></i></span></p>
                                <button class="btn btn-outline-secondary" id="clock-in" type="submit" name="clock_in_btn" <?php if ($hasClockedIn) echo 'style="display:none;"'; ?>>Clock in</button>
                                <button class="btn btn-outline-secondary" id="clock-out" type="submit" name="clock_out_btn" <?php if (!$hasClockedIn) echo 'style="display:none;"'; ?>>Clock out</button>
                                <div id="message" class="alert alert-success" style="<?php if (isset($successMessage)) echo 'display:block;'; else echo 'display:none;'; ?>">
                                    <?php if (isset($successMessage)) echo $successMessage; ?>
                                </div>
                                <p id="errorText" style="color: red;"></p>
                            </div>
                            <div class="col-1" style="border-left: 2px solid #fb5607; height: 120px;"></div>
                            <div class="col-4">
                                <p class="right_box_clockin">Your login time <?php echo $clkintime; ?> / Working hours 9.00 am to 6.00 pm</p>
                                <button class="btn btn-outline-secondary tracking_report_dash" id="tracking_report" type="button"><a href="ACS/timetracking.php">My time tracking report</a></button>
                            </div>
                            <div class="col-4">
                                <p class="image_calendar"> <img src="assets/images/calendar-image.png" class="image_calendar" alt="" srcset=""/></p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
    </div>

    

    <script>
        var userLocation;
        var clockInButton = document.getElementById('clock-in');

        function checkClockInLocation(location, mobile) {
            // Coordinates of the constant clock-in location
            const clockInLat = 8.174358888532955;
            const clockInLon = 77.42424829686452;

            // Calculate the distance between the user's location and the clock-in location
            const distance = getDistance(location.lat, location.lng, clockInLat, clockInLon);

            // Determine the appropriate radius based on the user's device
            let radius = mobile ? 500 : 1750; // 0.5 km for mobile, 1.75 km for others (in meters)

            const errorText = document.getElementById('errorText');
            if (distance < radius) {
                clockInButton.style.display = 'block';
                clockInButton.addEventListener('click', function () {
                    alert("You have successfully clocked in!");
                    clockInButton.style.display = 'none'; // Hide the button after successful clock-in
                });
                errorText.textContent = ''; // Clear any previous error message
            } else {
                clockInButton.style.display = 'none';
                errorText.textContent = 'Get in location to clock in';
            }
        }

        function getDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Earth's radius in kilometers
            const dLat = deg2rad(lat2 - lat1);
            const dLon = deg2rad(lon2 - lon1);
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            const distance = R * c;
            return distance * 1000; // Convert to meters
        }

        function deg2rad(deg) {
            return deg * (Math.PI / 180);
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                document.getElementById('lat').textContent = userLocation.lat;
                document.getElementById('lon').textContent = userLocation.lng;

                // Check if the user is accessing from a mobile device
                const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                checkClockInLocation(userLocation, isMobile);
            });
        } else {
            console.log("Geolocation is not supported in your browser.");
        }
    </script>

</body>
<div class="authorization_footer_if">
        <?php  require_once "ACS/includes/footer.php";?>
    </div>
</html>
