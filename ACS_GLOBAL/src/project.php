<?php
include "../includes/connection.php";

$nextBatchNumber = '';

function generateBatchNumber($conn) {
    $lastBatchNumberQuery = "SELECT batchnumber FROM client ORDER BY batchnumber DESC LIMIT 1";
    $result = $conn->query($lastBatchNumberQuery);
    if ($result->num_rows > 0) {
        $lastBatchNumber = $result->fetch_assoc()['batchnumber'];
        $numericPortion = intval(substr($lastBatchNumber, 3)) + 1;
        return 'BI-' . str_pad($numericPortion, 4, '0', STR_PAD_LEFT);
    } else {
        return 'BI-0001'; // Start with BI-0001 if no previous records
    }
}

$nextBatchNumber = generateBatchNumber($conn);

// Retrieve the last stored project ID from the database
$sql_last_project_id = "SELECT PROJECTID FROM projects ORDER BY PROJECTID DESC LIMIT 1";
$result_last_project_id = $conn->query($sql_last_project_id);

if ($result_last_project_id->num_rows > 0) {
    $last_project_id = $result_last_project_id->fetch_assoc()['PROJECTID'];

    // Extract the numeric portion from the last project ID
    $last_numeric_portion = (int)substr($last_project_id, -6);
} else {
    // If there are no previous records, start with 1
    $last_numeric_portion = 0;
}

// Increment the numeric portion for the new project ID
$numericPortion = $last_numeric_portion + 1;
$new_project_id = 'PI-2324-' . str_pad($numericPortion, 6, '0', STR_PAD_LEFT);

$sql_get = "SELECT batchnumber, clientname, contactperson, department FROM client ORDER BY batchnumber DESC LIMIT 1";
$result = $conn->query($sql_get);

if (!$result) {
    echo "Error: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $BatchNumber = $row['batchnumber'];
        $lastClientName = $row['clientname'];
        $lastContactPerson = $row['contactperson'];
        $lastDepartment = $row['department'];
    }
}

require '../includes/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$msg = ""; // Initialize the message variable

// Handle Excel file import
if (isset($_FILES['excel-file'])) {
    $file = $_FILES['excel-file']['tmp_name'];
    $extension = pathinfo($_FILES['excel-file']['name'], PATHINFO_EXTENSION);

    if ($extension == 'xlsx' || $extension == 'xls' || $extension == 'csv') {
        $obj = IOFactory::load($file);
        $data = $obj->getActiveSheet()->toArray(null, true, true, true);

        // Remove the first row (headers) from the data
        array_shift($data);

        foreach ($data as $row) {
            $BATCHNUMBER = isset($row['B']) ? $row['B'] : '';
            $WORKTITLE = isset($row['C']) ? $row['C'] : '';
            $ISBNNUMBER = isset($row['D']) ? $row['D'] : '';
            $TYPESCOPE = isset($row['E']) ? $row['E'] : '';
            $COMPLEXITY = isset($row['F']) ? $row['F'] : '';
            $UNIT = isset($row['G']) ? $row['G'] : '';
            $RECEIVEDPAGES = isset($row['H']) ? $row['H'] : '';
            $RECEIVEDDATE = isset($row['I']) ? date('Y-m-d', strtotime($row['I'])) : '';
            $DEPARTMENT = isset($row['J']) ? $row['J'] : '';
            $OURTAT = isset($row['K']) ? date('Y-m-d', strtotime($row['K'])) : '';

            // Insert data into the projects table
            $insert_query = mysqli_query($conn, "INSERT INTO projects (PROJECTID, OURBATCH, WORKTITLE, ISBNNUMBER, TYPESCOPE, COMPLEXITY, UNIT, RECEIVEDPAGES, RECEIVEDDATE, DEPARTMENT, CLIENTNAME, CONTACTPERSON, BATCHNUMBER, OURTAT) 
            VALUES ('$new_project_id', '$BatchNumber', '$WORKTITLE', '$ISBNNUMBER', '$TYPESCOPE', '$COMPLEXITY', '$UNIT', '$RECEIVEDPAGES', '$RECEIVEDDATE', '$lastDepartment', '$lastClientName', '$lastContactPerson', '$BATCHNUMBER', '$OURTAT')");

            if ($insert_query) {
                $msg = "File Imported Successfully!";
            } else {
                $msg = "Not Imported! Error: " . mysqli_error($conn);
            }

            // Increment the project ID for the next record
            $numericPortion = intval(substr($new_project_id, -6)) + 1;
            $new_project_id = 'PI-2324-' . str_pad($numericPortion, 6, '0', STR_PAD_LEFT);
        }

        // Redirect to project.php after processing
        echo '<script>alert("File Imported Successfully!");</script>';
        // exit();
    } else {
        echo '<script>window.location.href = "error.php";</script>';
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
    <link rel="stylesheet" href="../css/styless.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>body{width:99%;}
/* Add a CSS class for the blur effect */
.blur {
    filter: blur(15px); /* Adjust the blur intensity as needed */
}

/* Apply the blur class to the form_one_for_client div */
.form_one_for_client {
    /* Other styles for the div */
}

/* Initially blur the div */
.form_one_for_client.blur {
    filter: blur(15px); /* Adjust the blur intensity as needed */
}
*{font-size:16px;}
label#pro_pm_import {
    font-size: 60%;
}
</style>
</head>
<body>
<!----------------------- start header section  ---------------------------------->
<?php require_once "../includes/header.php";?>
<!-----------------------end header section  ---------------------------------->
<div class="conatiner imp_pro_container">
<?php
if ($user_position == "Project Manager" || $user_position == "General Manager" ||$user_position == "Operational Manager") {
?>
<center>
    <div class="row import_row">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="custom-file-upload">
        <i class="fa fa-times" id="clear-file" style="display: none;"></i>
        <label for="file-upload" class="pro_pm_import" id="pro_pm_import">
            <i class="fas fa-cloud-upload-alt"></i> Upload File
        </label>
        <input id="file-upload" type="file" name="excel-file" style="display: none;">
        <span id="file-name" style="display: none;"></span>
        <button type="submit" class="btn btn-primary" id="upload-button" style="display: none;">Upload</button>
        </div>
    </form>
            </div>
            <div class="row form_one">
                                    <div class="col-1"></div>
                    <div class="col-4" style="text-transform:uppercase;"></div>
                    <div class="col-2"></div>
                    <div class="col-4"  style="text-transform:uppercase;"></div>
                    <div class="col-1"></div>
            </div>
    </center>
            <div class="row form_one_second">
                                <div class="col-2"></div>
                        <div class="col-4 form_one_for_client">
                                <form method="post" action="../controllers/project_controller.php">
                                    <input type="hidden" name="firstFormSubmit" value="1"> 
                                    <h4 class="center projectpg">Project</h4>
                                    <div class="row">
                                    <div class="col-lg-6 project_date">    
                                    <label for="date">Date:</label>                               
                                    <input style="display:inline; border:none; font-weight:600;" type="text" class="form-control" id="date" name="date" value=" <?php echo date('Y-m-d'); ?>" readonly required>  </div>
                                    <div class="col-lg-6 project_batch">   
                                    <label for="batchnumber" class="label_batch">Batch Number:</label>
                                    <input class="form-control"type="text" id="batchnumber1" name="batchnumber" value="<?php echo $nextBatchNumber; ?>" readonly></div></div>                               
                                    <label for="clientname">Client Name:</label>
                                    <input  class="form-control"type="text" id="clientname" name="clientname" placeholder="Type client name & click enter" required>
                                    <label for="contactperson">Contact Person:</label>
                                    <input class="form-control"type="text" id="contactperson" name="contactperson" required>
                                    <label for="department">Department:</label>
                                    <input class="form-control"type="text" id="department" name="department" required>
                                    <button class=" btn btn-primary frst-form-btn"type="submit"  name="submit_form_client">SUBMIT</button>
                                </form>
                        </div>       
                <div class="col-4" id="addClientFormContainer">         
                <div class="form_one_for_client blur" id="form_one_for_client"> <!-- Initial blur -->
                    <i class="far fa-times-circle close-icon" style="color: #ef6001;" style="display: none;"></i>
                        <form id="secondForm" style="    margin-top: -55px;" class="second_cl-form" method="post" action="../controllers/project_controller.php">                        
                            <input type="hidden" name="secondFormSubmit" value="1">  
                            <h4 class="center addpg">Add Client</h4>
                            <label for="date" style="display: none;">Date:</label>
                            <input class="form-control"type="date" id="date" value="<?php echo date('Y-m-d')?>" name="date" style="display: none;" >
                            <label for="clientname" style="margin-top:10px;">Client Name:</label>
                            <input class="form-control"type="text" id="clientname1" name="clientname" required>
                            <label for="contactperson">Contact Person:</label>
                            <input class="form-control"type="text" id="contactperson1" name="contactperson" required>                         
                            <label for="department">Department:</label> 
                                <select type="text" class="form-select" id="department" name="department">
                            <option value=""><?php echo "$lastDepartment"; ?></option>
                            <option value="">*—Select the department—*</option>
                            <?php
                                $sql = "SELECT `DEPARTMENT_ELOIACS` FROM `department_company` WHERE `DEPARTMENT_ELOIACS` IS NOT NULL";
                                          $result = mysqli_query($conn, $sql);
                                          if ($result && mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $department = $row['DEPARTMENT_ELOIACS']; 
                                                echo "<option value='$department'>$department</option>";
                                            }} else {
                                                echo "<option value=''>No department found</option>"; 
                                                }?>
                        </select>
                        </form>
                        <button class="btn btn-primary frst-form-btn" type="submit" id="submitbutton" form="secondForm">SUBMIT</button>
                    </div>
                    <button class="btn btn-primary addButton" type="submit" id="addButton_blur" onclick="toggleAddClientForm()">ADD NEW CLIENTS</button>
                </div> 
                </div>               
            </div>
            <div class="row form_one_end">
            </div>
            <!-- the submitted form -->
            <div class="manual_addbtn">
            <input type="button" value="Click here to enter Project Manually !..."  id="manual_form_toggle" onclick="toggleManualForm()">
            <div class="contain-form" id="manual_form_details" style="display: none;"> 
                <form action="../controllers/project_controller.php" method="post">
            <div class="row">
                <div class="col-1"></div>
                <div class="col-5 input_client-form">
                    <div class="form-group">
                        <label class="form-contain-label" for="ourbatchnumber" class="small-label">OUR BATCH:</label>
                        <input type="text" class="form-control" id="ourbatchnumber" name="ourbatchnumber" value="<?php echo $BatchNumber; ?>">
                    </div>
                </div>
                <div class="col-5 input_client-form right">
                <div class="form-group">
                        <label class="form-contain-label" for="project_id">PROJECT ID</label>
                        <input type="text" class="form-control" id="new_project_id" name="new_project_id" value="<?php echo $new_project_id; ?>">
                    </div>                    
                </div>
                <div class="col-1"></div>
            </div>
            <div class="row">
                <div class="col-1"></div>
                <div class="col-5 input_client-form">
                    <div class="form-group">
                        <label class="form-contain-label" for="department" class="small-label">DEPARTMENT</label>
                        <select type="text" class="form-select" id="department" name="department">
                      
                            <option value=""><?php echo "$lastDepartment"; ?></option>
                            <option value="">*—Select the department—*</option>
                            <?php
                                $sql = "SELECT `DEPARTMENT_ELOIACS` FROM `department_company` WHERE `DEPARTMENT_ELOIACS` IS NOT NULL";
                                          $result = mysqli_query($conn, $sql);
                                          if ($result && mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $department = $row['DEPARTMENT_ELOIACS']; 
                                                echo "<option value='$department'>$department</option>";
                                            }} else {
                                                echo "<option value=''>No department found</option>"; 
                                                }?>
                        </select>
                    </div>
                </div>                
                <div class="col-5 input_client-form right">
                <div class="form-group">
                <label class="form-contain-label" for="client_name">CLIENT NAME:</label>
                        <input type="text" class="form-control" id="clientname" name="clientname" value="<?php echo $lastClientName; ?>">
                    </div>                    
                </div>
                <div class="col-1"></div>
            </div>
            <div class="row">
                <div class="col-1"></div>
                <div class="col-5 input_client-form">
                    <div class="form-group">
                        <label class="form-contain-label" for="contact_person" class="small-label">CONTACT PERSON:</label>
                        <input type="text" class="form-control" id="contactperson" name="contactperson" value="<?php echo $lastContactPerson; ?>">
                    </div>
                </div>
                <div class="col-5 input_client-form right">
                <div class="form-group">
                <label class="form-contain-label" for="batch_number">BATCH NUMBER:</label>
                        <input type="text" class="form-control" id="batchnumber" name="batchnumber" required>
                    </div>                    
                </div>
                <div class="col-1"></div>
            </div>
            <div class="row">
                <div class="col-1"></div>
                <div class="col-5 input_client-form">
                    <div class="form-group">
                    <label class="form-contain-label" for="work_title">WORK COVER TITLE:</label>
                        <input type="text" class="form-control" id="worktitle" name="worktitle" required>
                    </div>
                </div>
                <div class="col-5 input_client-form right">
                <div class="form-group">
                <label class="form-contain-label" for="isbn_number">ISBN NUMBER:</label>
                        <input type="text" class="form-control" id="isbnnumber" name="isbnnumber" required>
                    </div>                    
                </div>
                <div class="col-1"></div>
            </div>
            <div class="row">
                <div class="col-1"></div>
                <div class="col-5 input_client-form">
                    <div class="form-group">
                    <label class="form-contain-label" for="isbn_number">COMPLEXITY:</label>
                        <input type="text" class="form-control" id="complexity" name="complexity" required>
                    </div>
                </div>
                <div class="col-5 input_client-form right">
                <div class="form-group">
                <label class="form-contain-label" for="TYPE-OF-SCOPE">TYPE OF SCOPE:</label>
                        <input type="text" class="form-control" id="typescope" name="typescope" required>
                    </div>                    
                </div>
                <div class="col-1"></div>
            </div>
            <div class="row">
                <div class="col-1"></div>
                <div class="col-5 input_client-form">
                    <div class="form-group">
                    <label class="form-contain-label" for="reference_number">REFERENCE NUMBER:</label>
                        <input type="text" class="form-control" id="refrencenumber" name="refrencenumber" required>
                    </div>
                </div>
                <div class="col-5 input_client-form right">
                <div class="form-group">
                <label class="form-contain-label" for="receivedpages">RECEIVED PAGES:</label>
                        <input type="text" class="form-control" id="receivedpages" name="receivedpages"required>
                    </div>                    
                </div>
                <div class="col-1"></div>
            </div>
            <div class="row">
                <div class="col-1"></div>
                <div class="col-5 input_client-form">
                <div class="form-group">
                <label class="form-contain-label" for="received_date">RECEIVED DATE:</label>
                        <input type="date" class="form-control" id="receiveddate" name="receiveddate" required>
                    </div>                    
                </div>
                <div class="col-5 input_client-form right">
                <div class="form-group">
                <label class="form-contain-label" for="due_date">VENDOR TAT:</label>
                        <input type="date" class="form-control" id="duedate" name="duedate" required>
                    </div>                    
                </div>
                <div class="col-1"></div>
            </div>
            <div class="row">
                <div class="col-1"></div>
                <div class="col-5 input_client-form">
                <div class="form-group">
                <label class="form-contain-label" for="due_date">DUE DATE:</label>
                        <input type="date" class="form-control" id="ourtat" name="ourtat" required>
                    </div>                    
                </div>
                <div class="col-5 input_client-form right">
                <div class="form-group">
                <label class="form-contain-label" for="total_days">UNIT:</label>
                        <input type="text" class="form-control" id="unit" name="unit"required>
                    </div>                    
                </div>
                <div class="col-1"></div>
            </div>
            <div class="row">
                <div class="col-1"></div>
                <div class="col-5 input_client-form" style="display:none">
                <div class="form-group">
                <label class="form-contain-label" for="total_days">TOTAL DAYS:</label>
                        <input type="text" class="form-control" id="totaldays" name="totaldays"  readonly required>
                    </div>                    
                </div>
                <div class="col-5 input_client-form right" style="display:none;">
                <div class="form-group">
                <label class="form-contain-label" for="">LOP DAYS:</label>
                        <input type="text" class="form-control" id="lopdays" name="lopdays" readonly required>
                    </div>                    
                </div>
                <div class="col-1"></div>
            </div>
            <div class="row">   
                <div class="col-3"></div> 
                <center>       
                   <div class="col-6"> <button type="submit" class="btn btn-primary client_pro_entryform" name="client_pro_entryform">SAVE</button></div></center>     
                   <div class="col-3"></div>        
            </div>
        </form>
    </div>
</div> 
            <?php } else {?>
                <div class="autho" style="height:max-content;">
                <div class="authorization">
                    <h1 class="unauth">Unauthorized</h1>
                        <h4 class="unauthorization">Apologies, <span class="unauth"><?php echo $employeeName;?></span>  you don't have the authorization for this action.</h4>
                </div>
                </div>
            <?php }
            ?>
    </div>
</body>
<div class="authorization_footer_if">
        <?php  require_once "../includes/footer.php";?>
    </div>
    <script src="../js/project.js"></script>
</html>