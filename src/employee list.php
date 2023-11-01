<?php
include "../includes/connection.php";

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b272402e67.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/styless.css">
    <style>
      
      
        .table-Container {
  max-height: 350px; /* Set the maximum height of the container */
  overflow: auto; /* Enable vertical scrolling */
}

table {
  width: 100%; /* Make the table fill its container */
  border-collapse: collapse;
}

thead {
    position: sticky;
  top: 0;
  background: linear-gradient(180deg, #fc7105, orange);
  color: white;

}

thead th {

  text-transform: uppercase;
  height: 40px;
  z-index: 2; /* Ensure the header stays above the content */
}
table.table {
    border: 2px orange solid;
}


tbody td {
  /* Add padding and adjust height as needed */
  padding: 8px;
  height: 40px; /* You can adjust this based on your design */
  /* Adjust other styles as needed */
  border-bottom: 1px solid #ddd;
}

/* Hide the vertical scrollbar on the container */
.table-Container::-webkit-scrollbar {
  width: 0.5em;
}

/* Style the vertical scrollbar on the container */
.table-Container::-webkit-scrollbar-thumb {
  background-color: #888;
}
#search{
    border: 2px #ed7805 solid;
    float:right;
    width:30%;
}

.search_emp_row{margin-top:12%;

margin-bottom:2%;}
button.transfer-to-employee-button {
    width: 185px;
}
.containerr{
    padding-left:3%;
    padding-right:3%;
}

.employee-list-pro{margin-bottom:3%;}

.hidden{
    display:none;
}
    </style>
</head>
<body>
<?php
// Include the header section
require_once "../includes/header.php";
?>
<div class="containerr employee-list-pro">
    <div class="row">
        <div class="col-12 search_emp_row">
                <input class="form-control mr-sm-2 " id="search" type="search" placeholder="Search by Name" aria-label="Search">
              </div>
    </div>    
    <div  id="tableContainer">
        <form action="../controllers/form_controller.php" method="post">

        <div class="table-Container" id="table-Container">
            <table class="table">
                <thead>
                    <tr>
                        <th>EMPLOYEE NAME</th>
                        <th>CODE</th>
                        <th>Department</th>
                        <th>PROJECT DEPARTMENT</th>
                        <th>TEAM LEADER</th> 
                        
                        <?php
if ($user_position == "General Manager" || $user_position == "Admin") {
    echo '<th>Position</th>';
} else {
    echo '<th class="hidden">Position</th>';
}
?>

                        <?php
if ($user_position == "General Manager" || $user_position == "Admin") {
    echo '<th>Change to Employee</th>';
} else {
    echo '<th class="hidden">Change to Employee</th>';
}
?>



                        <!-- Include any other table headers here -->
                        <th>ACTION</th>
                    </tr>
                </thead>



                <tbody id="tableBody">
                    <?php
                    include "../includes/connection.php";

                    $sql_result_empl = "SELECT `NAME`, CODE, `BRANCH`,`DEPARTMENT`, `Project_department`, `TEAMLEADER` FROM employee_data WHERE STATUS = 'Working'";
                    $result = mysqli_query($conn, $sql_result_empl);

                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . $row['NAME'] . '</td>';
                            echo '<td>' . $row['CODE'] . '</td>';
                            echo '<td>' . $row['DEPARTMENT'] . '</td>';
                            echo '<td class="hidden"><input type="text" name="employee_codes[]" value="' . $row['CODE'] . '"></td>';
                            

                            echo '<td><select name="Project_department[' . $row['CODE'] . ']" class="input_wi">';
                            echo '<option value="' . $row['Project_department'] . '">' . $row['Project_department'] . '</option>';
                            echo "<option value='Update' disabled>--------------</option>";
                            $sql = "SELECT DISTINCT `DEPARTMENT_ELOIACS` FROM `department_company` WHERE `DEPARTMENT_ELOIACS` IS NOT NULL";
                            $result_DEPARTMENT_ELOIACS = mysqli_query($conn, $sql);
                            if ($result_DEPARTMENT_ELOIACS && mysqli_num_rows($result_DEPARTMENT_ELOIACS) > 0) {
                                while ($DEPARTMENT_ELOIACS_row = mysqli_fetch_assoc($result_DEPARTMENT_ELOIACS)) {
                                    $DEPARTMENT_ELOIACS = $DEPARTMENT_ELOIACS_row['DEPARTMENT_ELOIACS'];
                                    echo "<option value='$DEPARTMENT_ELOIACS'>$DEPARTMENT_ELOIACS</option>";
                                }
                            } else {
                                echo "<option value=''>No departments found</option>";
                            }
                            echo '</select></td>';

                            echo '<td><select name="teamleader[' . $row['CODE'] . ']" class="input_wi">';
                            echo '<option value="' . $row['TEAMLEADER'] . '">' . $row['TEAMLEADER'] . '</option>';
                            echo "<option value='Update' disabled>--------------</option>";
                            $sql = "SELECT `Team_Leader` FROM `department_company` WHERE `Team_Leader` IS NOT NULL";
                            $result_team_leader = mysqli_query($conn, $sql);
                            if ($result_team_leader && mysqli_num_rows($result_team_leader) > 0) {
                                while ($team_leader_row = mysqli_fetch_assoc($result_team_leader)) {
                                    $Team_Leader = $team_leader_row['Team_Leader'];
                                    echo "<option value='$Team_Leader'>$Team_Leader</option>";
                                }
                            }
                            echo '</select></td>';
                            if ($user_position == "General Manager" || $user_position == "Admin") {
                                echo '<td>';
                                ?>
                                
                                
                                <div class="input-group mb-3">
                                    <select class="form-select input_wi" id="position" name="position_update[<?php echo $row['CODE']; ?>]">
                                        <?php
                                        $employeeCode = $row['CODE'];
                                        $sql_user_table = "SELECT DISTINCT position FROM usertable WHERE name = '$employeeCode'";
                                        $result_usertable = mysqli_query($conn, $sql_user_table);
                                        // Fetch and set the initial value
                                        $initialValue = ""; // Initialize it to empty
                                        if ($result_usertable && mysqli_num_rows($result_usertable) > 0) {
                                            $row_usertable = mysqli_fetch_assoc($result_usertable);
                                            $initialValue = $row_usertable['position'];
                                        }
                                        
                                        echo "<option value='$initialValue'>$initialValue</option>";
                                        echo "<option value='Update' disabled>-------------------------------</option>";
                                        $sql = "SELECT `Position` FROM `department_company` WHERE `DEPARTMENT_ELOIACS` IS NOT NULL";
                                        $result_positions = mysqli_query($conn, $sql);
                                        if ($result_positions && mysqli_num_rows($result_positions) > 0) {
                                            while ($position_row = mysqli_fetch_assoc($result_positions)) {
                                                $position = $position_row['Position'];
                                                echo "<option value='$position'>$position</option>";
                                            }
                                        } else {
                                            echo "<option>No positions found</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <?php
                                echo '</td>';
                                echo '<td>';
                            echo '<button name="transfer_to_employee" class="transfer-to-employee-button"';
                            echo ' data-employee-name="' . $row['NAME'] . '"';
                            echo ' data-show-button="true"';
                            echo ' type="button">Transfer to Employee</button>';
                           

                            
                            } else {
                                // If not a General Manager, display an empty <td>
                            
                                echo '</td>';
                            }

                           
                            echo '<td class="empl_list"><button type="submit" class="btn btn-primary btn_qa_dashboard" name="save_emp_update">Save</button></td>';
                            echo '</tr>';

                        }
                    } else {
                        echo '<tr><td colspan="6">No employees found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>
</div>

<script>
  $(document).ready(function () {
    // Attach a click event handler to the "Transfer to Employee" button
    $('.transfer-to-employee-button').on('click', function () {
        const button = $(this);
        const employeeName = button.data('employee-name');
        const employeeCode = button.data('code');

        // Call the PHP function to generate a new CODE
        $.ajax({
            url: 'generate_employee_code.php',
            method: 'POST',
            data: { employeeName: employeeName },
            success: function (newCodeResponse) {
                // Handle the response here
                const newCode = newCodeResponse.trim();
                if (newCode !== "") {
                    // Ask for user confirmation before updating the CODE
                    if (confirm("Do you want to update the employee CODE to " + newCode + "?")) {
                        // Send an AJAX request to update the CODE
                        $.ajax({
                            url: 'update_employee_code.php',
                            method: 'POST',
                            data: { employeeName: employeeName, newCode: newCode },
                            success: function (response) {
                                // Handle the response from the server, e.g., show a success message
                                alert(response);
                                // Update the specific row in the table with the new code if needed
                                // For example, you can use jQuery to update the relevant cell:
                                button.closest('tr').find('td:eq(1)').text(newCode);
                                
                                // Reload the page after the second AJAX request has completed
                                location.reload();
                            },
                            error: function (xhr, status, error) {
                                // Handle errors here
                                console.error(xhr.responseText);
                            }
                        });
                    } else {
                        alert("Update canceled.");
                    }
                } else {
                    // New code is empty, handle this case if needed
                }
                
            },
            error: function (xhr, status, error) {
                // Handle errors when fetching the new CODE
                console.error(xhr.responseText);
            }
        });
    });
});

function setupFiltering() {
    const tableBody = $('#tableBody');
    const searchInput = document.getElementById('search');

    // Function to filter and display rows based on the search term
    function filterRows() {
        const searchTerm = searchInput.value.trim().toLowerCase();

        tableBody.find('tr').each(function() {
            const row = $(this);
            const nameCell = row.find('td:first-child');
            const name = nameCell.text().toLowerCase();

            if (name.includes(searchTerm)) {
                row.show();
            } else {
                row.hide();
            }
        });
    }

    // Initial filter on page load
    filterRows();

    // Search input event listener
    searchInput.addEventListener('input', function() {
        // Call the filter function when the search input changes
        filterRows();
    });
}
function transferemployeeshow(){
    // Select all buttons with the "show-button" class and show them
    $('.show-button').show();

    // Loop through each row in the table
    $('table tbody tr').each(function() {
        // Find the code cell within the current row
        const codeCell = $(this).find('td:eq(1)');
        const code = codeCell.text().trim();

        // Check if the code starts with "TR"
        if (code.startsWith('ETMP')) {
            // If it starts with "TR," show the "Transfer to Employee" button in the same row
            $(this).find('.transfer-to-employee-button').show();
        } else {
            // If it doesn't start with "TR," hide the button in the same row
            $(this).find('.transfer-to-employee-button').hide();
        }
    });
}

// Call the setupFiltering function when the document is ready
$(document).ready(function() {
    setupFiltering();
    transferemployeeshow();
});

// Call the resetpassword function when the document is ready


</script>


</body>
</html>
