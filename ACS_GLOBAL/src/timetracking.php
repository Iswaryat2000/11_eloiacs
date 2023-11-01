<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b272402e67.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="../css/styless.css" rel="stylesheet">
    <style>   
    body{
        width:98%;
    }    
        .timetracks_total_container {
    margin-top: 6%;
    margin-bottom: 5%;
    margin-left: 2%;
    width: 95%;
    height: max-content;
    padding: 25px 10px;
} .time-off-request{

        display:none;
    }
    input#time_off_btn {
        border:10px #0e649b solid;
        color:white;
        background:#0e649b;
    float: right;
}
    .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .popup-content {
    position: absolute;
    top: 53%;
    left: 53%;
    transform: translate(50%, -50%);
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    width: 400px; /* Set a fixed width for the popup content */
    height: auto; /* Let the height adjust based on content */
}
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
        }
        input#fullhalfday {
    width: 50%;
}
.full {
    margin-left: -25px;
    margin-right: 20%;
}
.half{margin-left:25px;}
.form-check.form-switch {
    display: flex;
}
button#Requested_btn {
    margin-top: 5%;
    margin-bottom: 2%;
}
.top_timeoff{margin-top:3%;}


table {
  width: 100%; /* Make the table fill its container */
  border-collapse: collapse;
}

thead {
    position: sticky;
  top: 0;
  background: linear-gradient(180deg, #f8f9fa, #0e649b);
  color: white;
 
}
thead th {

  text-transform: uppercase;
  height: 40px;
  z-index: 2; /* Ensure the header stays above the content */
}
tbody td {
  /* Add padding and adjust height as needed */
  padding: 8px;
  height: 40px; /* You can adjust this based on your design */
  /* Adjust other styles as needed */
  border-bottom: 1px solid #ddd;
}
.filtering {
    display: flex;
}
button#filterButton {
    color: white;
    border: 1px #0e649b solid;
    background: #0e649b;
    padding: 0px 8px;
    border-radius: 5px;
    margin-left: 2%;
}
.input-daterange {
    margin-left: 1%;
}
.calendar {
    margin-top: -27px;
}
.table-responsive {
    margin-left:3%;
    margin-right:3%;
    height: 270px;
}
.filtering {
    display: flex;
    height: 30px;
    width: 50%;
    font-size: 14px;
}.datepicker
{
    text-align: center;
    font-size: 10px;
}.entities_show, .records_entit {
    height: 36px;
    margin: 0% 0% 0% 74%;
    font-size: 14px;
    float: right;
}
.form-group.recordss {
    position: relative;
    display: flex;
    flex-direction: row;
}
    </style>
</head>
<body>   
    <!-- Include your header and footer as needed -->
    <?php require_once "../includes/header.php"; 
    // Initialize variables for start and end dates
    $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
    $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';
    $recordsPerPage = isset($_GET['recordsPerPage']) ? intval($_GET['recordsPerPage']) : 10;
    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($currentPage - 1) * $recordsPerPage;
    $sql = "SELECT EMPNAME, EMPCODE, CRNT_DATE, CLOCKIN, CLOCKOUT, EMP_LEAVE 
            FROM tl_attendance 
            WHERE EMPNAME = '$employeeName'";
    if (!empty($startDate) && !empty($endDate)) {
        $sql .= " AND CRNT_DATE BETWEEN '$startDate' AND '$endDate'";
    }
    $sql .= " LIMIT $offset, $recordsPerPage";
    $result = $conn->query($sql);
    $hasFilteredResults = $result->num_rows > 0;
    ?>

    <title>Time Off Request</title>
</head>
<body>

<div class="timetracks_total_container">
        <input type="button" class="" id="time_off_btn" value="+ NEW TIME OFF REQUEST">
    </div>

    <div class="popup" id="popup">
        <div class="popup-content">
            <span class="close-btn" id="close-btn">&times;</span>
            <form method="POST" action="../includes/timeoff_mail.php">
                <div class="container">
                    <div class="row">
                        <div class="col-md-9 top_timeoff">
                            <label for="from">Name:</label>
                            <input type="text" class="form-control" name="from_name" id="form_name" placeholder="Enter the user name" value="<?php echo "$employeeName"; ?>" readonly>
                            <input hidden type="text" class="form-control" name="form_department" id="form_department" placeholder="Enter the user department" value="<?php echo "$EMP_DEPARTMENT";?>" readonly>
                            <input type="hidden" name="from_email" id="form_email" placeholder="Enter the useremail" value="<?php echo "$useremail"; ?>" readonly>
                            <input type="hidden" name="from_id" id="form_id" placeholder="Enter the useremail" value="<?php echo "$user_name"; ?>" readonly>
                        </div>
</div>

<div class="row">
                        <div class="col-md-12 top_timeoff">
                            <label for="form_leave_option">Leave Type:</label>
                            <select class="form-select" id="form_leave_option" name="form_leave_option">
                                <option selected>Select the leave type</option>
                                <option value="Requested Leave">Requested Leave</option>
                                <option value="Casual Leave">Casual Leave</option>
                                <option value="Planned Leave">Planned Leave</option>
                            </select>
                        </div>
</div>
<div class="row">
                        <div class="col-md-6 top_timeoff">
                            <label for="from">From:</label>
                            <input type="date" class="form-control" name="from_date" id="from_date" placeholder="From date">
</div>
<div class="col-md-6 top_timeoff">
                            <label for="from">TO:</label>
                            <input type="date" class="form-control" name="to_date" id="to_date" placeholder="To date">
                        </div>
</div>

<div class="row">
                        <div class="col-md-12 flex_row top_timeoff">
                            <label class="form-check-label" id="fullDay" for="fullDay">Leave Duration:</label>
                            <div class="form-check form-switch">
                                
                            <p class="full">Full Day</p>
                                <input class="form-check-input" type="checkbox" id="fullDayCheckbox" name="fullDay">
                                <input type="text" style="display:none;" class="form-control" name="full_halfday" id="fullhalfday" value="">
                                <p class="half">Half Day</p>
                            </div>
                        </div>
</div>
<div class="row">
                        <div class="col-md-12 flex_row top_timeoff" id="First_halfDay">
                            <label class="form-check-label" id="firsthalfDay" for="fullDay">Half Day Options:</label>
                            <div class="form-check form-switch">
                                <p class="full">First Half</p>
                                <input class="form-check-input" type="checkbox" id="firstsecondCheckbox" name="">
                                <input style="display:none" type="text" class="form-control" name="first_secondary_half" id="firstsecondday" value="">
                            <p class="half">Second Half</p>
                            </div>
                        </div>

                        <div class="col-md-12 top_timeoff">
                            <label for="text_area">Leave Reason:</label>
                            <textarea class="form-control" id="text_area" name="text_area" aria-label="With textarea"></textarea>
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="submit" id="Requested_btn" class="btn btn-primary btn-md" name="request_leave">Requested Leave</button> 
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById("time_off_btn").addEventListener("click", function() {
            document.getElementById("popup").style.display = "block";
        });

        document.getElementById("close-btn").addEventListener("click", function() {
            document.getElementById("popup").style.display = "none";
        });
    </script>
    <div class="row">
        <div class="col-8">
        <div class="calendar">
            <form method="GET" action="timetracking.php">
                <div class="filtering">
                <div class="input-daterange">
                <span class="date-range-separator">FROM: </span>
                    <input type="date" name="startDate" id="startDate" value="<?php echo $startDate; ?>" class="datepicker" placeholder="from date">
                    <span class="date-range-separator">TO: </span>
                    <input type="date" name="endDate" id="endDate" value="<?php echo $endDate; ?>" class="datepicker" placeholder="to date">
                </div>
                <button type="submit" id="filterButton">FILTER</button>
                <a href="timetracking.php"><button type="button" id="clearDateFilter" style="display: <?php echo empty($startDate) && empty($endDate) ? 'none' : 'block'; ?>"><i class="fa-solid fa-xmark"></i></button></a>
                </div>
            </form>
        </div>
        </div>
        <div class="col-4">
        <!-- Records per page dropdown -->
        <div class="form-group recordss">           
            <select class="form-control entities_show" id="recordsPerPage" onchange="changeRecordsPerPage(this.value)" style="width: 60px;">
                <option value="10" <?php if ($recordsPerPage == 10) echo 'selected'; ?>>10</option>
                <option value="25" <?php if ($recordsPerPage == 25) echo 'selected'; ?>>25</option>
                <option value="50" <?php if ($recordsPerPage == 50) echo 'selected'; ?>>50</option>
            </select>
        </div>
    </div>
    </div>

        <div class="table-responsive">
            <?php
            if ($hasFilteredResults) {
                echo '<p></p>';
            }
            ?>
            <table class="table table-bordered mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>EMP NAME</th>
                        <th>EMP CODE</th>
                        <th>CURRENT DATE</th>
                        <th>CLOCK IN</th>
                        <th>CLOCK OUT</th>
                        <th>EMP LEAVE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($hasFilteredResults) {
                        while ($row = $result->fetch_assoc()) {
                            if (!empty($row["EMPNAME"])) {
                                echo "<tr>";
                                echo "<td>" . $row["EMPNAME"] . "</td>";
                                echo "<td>" . $row["EMPCODE"] . "</td>";
                                echo "<td>" . $row["CRNT_DATE"] . "</td>";
                                $clockInTime = strtotime($row["CLOCKIN"]);
                                if ($clockInTime > strtotime('9:00:00')) {
                                    echo "<td>" . $row["CLOCKIN"] . " <span style='color: red;'>Late</span></td>";
                                } else {
                                    echo "<td style='color: green;'>" . $row["CLOCKIN"] . "</td>";
                                }

                                echo "<td>" . $row["CLOCKOUT"] . "</td>";
                                echo "<td>" . $row["EMP_LEAVE"] . "</td>";
                                echo "</tr>";
                            } else {
                                echo "";
                            }
                        }
                    } else {
                        echo '<p>No results found.</p>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <?php
        $sql = "SELECT COUNT(*) AS total FROM tl_attendance WHERE EMPNAME = '$employeeName'";
        if (!empty($startDate) && !empty($endDate)) {
            $sql .= " AND CRNT_DATE BETWEEN '$startDate' AND '$endDate'";
        }
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $totalPages = ceil($row['total'] / $recordsPerPage);
        echo "<nav aria-label='Page navigation'>";
        echo "<ul class='pagination justify-content-center'>";
        if ($currentPage > 1) {
            echo "<li class='page-item'><a class='page-link' href='?page=" . ($currentPage - 1) . "&recordsPerPage=$recordsPerPage&startDate=$startDate&endDate=$endDate'>Previous</a></li>";
        }
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<li class='page-item " . ($i == $currentPage ? 'active' : '') . "'><a class='page-link' href='?page=$i&recordsPerPage=$recordsPerPage&startDate=$startDate&endDate=$endDate'>$i</a></li>";
        }
        if ($currentPage < $totalPages) {
            echo "<li class='page-item'><a class='page-link' href='?page=" . ($currentPage + 1) . "&recordsPerPage=$recordsPerPage&startDate=$startDate&endDate=$endDate'>Next</a></li>";
        }
        echo "</ul>";
        echo "</nav>";
        // Close the database connection
        $conn->close();
        ?>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        $("#startDate").datepicker({
            format: "yyyy-mm-dd",
            clearBtn: true,
            todayHighlight: true,
            autoclose: true
        });
        $("#endDate").datepicker({
            format: "yyyy-mm-dd",
            clearBtn: true,
            todayHighlight: true,
            autoclose: true
        });
        function toggleClearButton() {
            if ($("#startDate").val() || $("#endDate").val()) {
                $("#clearDateFilter").show();
            } else {
                $("#clearDateFilter").hide();
            }
        }
        $("#startDate, #endDate").on("change", function () {
            toggleClearButton();
        });
        $("#clearDateFilter").on("click", function () {
            $("#startDate").val('');
            $("#endDate").val('');
            $(this).hide();
            $("form").submit();
        });
        toggleClearButton();
    });
    </script>
      <script>
    function initializeTimeOff() {
        const fullDayCheckbox = document.getElementById('fullDayCheckbox');
        const dayField = document.getElementById('fullhalfday');
        const firstsecondhalf = document.getElementById('First_halfDay');
        const firstsecondCheckbox = document.getElementById('firstsecondCheckbox');
        const firstsecondday = document.getElementById('firstsecondday');
        function updateFields() {
            if (fullDayCheckbox.checked) {
                dayField.value = 'Half Day';
                firstsecondhalf.style.display = 'block';
                firstsecondday.value = 'First Half';
            } else {
                dayField.value = 'Full Day';
                firstsecondhalf.style.display = 'none';
                firstsecondday.value = '0';
            }
        }
        fullDayCheckbox.addEventListener('change', updateFields);
        updateFields();
        function updateFirstSecondDay() {
            if (firstsecondCheckbox.checked) {
                firstsecondday.value = 'Second Half';
            } else {
                firstsecondday.value = 'First Half';
            }
        }

        firstsecondCheckbox.addEventListener('change', updateFirstSecondDay);
        updateFirstSecondDay();
    }
    function hideTimeOff() {
        const Time_off = document.getElementById('Time_off');
        const time_off_btn = document.getElementById('time_off_btn');
        time_off_btn.addEventListener('click', function() {
            if (Time_off.style.display === 'none' || Time_off.style.display === '') {
                Time_off.style.display = 'block';
            } else {
                Time_off.style.display = 'none';
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        initializeTimeOff();
        hideTimeOff();
    });
</script>

    <!-- Include your footer as needed -->
    <?php require_once "../includes/footer.php"; ?>
    <!-- Include your footer as needed -->
</div>
</body>
</html>
