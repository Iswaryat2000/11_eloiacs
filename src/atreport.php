<!DOCTYPE html>

<html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b272402e67.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/dashboard_pro.css">
    <link rel="stylesheet" href="../css/styless.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  
    <title>Employee Attendance</title>
    <style>


        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: orange;
            color: white;
        }

        /* Style for the div wrapper */
        .table-wrapper {
             max-height: 400px;
    margin-top: 43px;
    overflow: auto;
    outline: 2px solid orange;
        }

        .table-wrapper::-webkit-scrollbar {
            width: 12px; 
        }

        .table-wrapper::-webkit-scrollbar-thumb {
            background-color: orange;
            border: 2px solid white;
        }

        .table-wrapper::-webkit-scrollbar-track {
            background-color: #f1f1f1;
        }
        form {
    margin-top: 4%;
}
    </style>
    <script>
        function filterTable() {
            var employeeFilter = document.getElementById("employee_filter").value.toLowerCase();
            var fromDate = document.getElementById("from_date").value;
            var toDate = document.getElementById("to_date").value;

            var table = document.querySelector("table");
            var rows = table.getElementsByTagName("tr");

           
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                var cells = row.getElementsByTagName("td");
                if (cells && cells.length >= 3) { 
                    var empName = cells[0].textContent.toLowerCase();
                    var date = cells[2].textContent;

                    var showRow = (!employeeFilter || empName.includes(employeeFilter)) &&
                                  (!fromDate || date >= fromDate) &&
                                  (!toDate || date <= toDate);

                    row.style.display = showRow ? "" : "none";
                }
            }
        }
    </script>
</head>
<body>

<h1>Filter Employee Attendance</h1>

<form>
    Employee Name/Code:
    <input type="text" id="employee_filter">
    From Date: <input type="date" id="from_date">
    To Date: <input type="date" id="to_date">
    <button type="button" onclick="filterTable()">Filter</button>
    <button type="button" onclick="downloadXLSX()">Download Excel</button>

</form>

<?php
include "../connection.php";
include_once "../includes/header.php";


$initial_sql = "SELECT * FROM `tl_attendance` ORDER BY CRNT_DATE DESC, EMPNAME";
$initial_result = $conn->query($initial_sql);

if ($initial_result->num_rows > 0) {
    // Add a div wrapper to make the table scrollable
    echo '<div class="table-wrapper"><table>';
    echo "<tr><th>EMPNAME</th><th>EMPCODE</th><th>CRNT_DATE</th><th>CLOCKIN</th><th>CLOCKOUT</th><th>EMP_LEAVE</th></tr>";

    while ($row = $initial_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["EMPNAME"] . "</td>";
        echo "<td>" . $row["EMPCODE"] . "</td>";
        echo "<td>" . $row["CRNT_DATE"] . "</td>";
        echo "<td>" . $row["CLOCKIN"] . "</td>";
        echo "<td>" . $row["CLOCKOUT"] . "</td>";
        echo "<td>" . $row["EMP_LEAVE"] . "</td>";
        echo "</tr>";
    }

    echo '</table></div>'; // Close the table and div wrapper
} else {
    echo "0 results";
}

$conn->close();
?>
</body>
</html>
<script>
    function downloadXLSX() {
        // Collect the filtered data
        var employeeFilter = document.getElementById("employee_filter").value.toLowerCase();
        var fromDate = document.getElementById("from_date").value;
        var toDate = document.getElementById("to_date").value;

        // Create a workbook
        var wb = XLSX.utils.book_new();

        // Create a worksheet
        var ws_data = [];

        // Add headers to the worksheet
        ws_data.push(["EMPNAME", "EMPCODE", "CRNT_DATE", "CLOCKIN", "CLOCKOUT", "EMP_LEAVE"]);

        var table = document.querySelector("table");
        var rows = table.getElementsByTagName("tr");

        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var cells = row.getElementsByTagName("td");
            if (cells && cells.length >= 3) {
                var empName = cells[0].textContent.toLowerCase();
                var date = cells[2].textContent;

                var showRow = (!employeeFilter || empName.includes(employeeFilter)) &&
                              (!fromDate || date >= fromDate) &&
                              (!toDate || date <= toDate);

                if (showRow) {
                    // Add row data to the worksheet
                    ws_data.push([
                        cells[0].textContent,
                        cells[1].textContent,
                        cells[2].textContent,
                        cells[3].textContent,
                        cells[4].textContent,
                        cells[5].textContent
                    ]);
                }
            }
        }

        var ws = XLSX.utils.aoa_to_sheet(ws_data);

        // Add the worksheet to the workbook
        XLSX.utils.book_append_sheet(wb, ws, "EmployeeAttendance");

        // Generate the XLSX file
        XLSX.writeFile(wb, "employee_attendance.xlsx");
    }
</script>
