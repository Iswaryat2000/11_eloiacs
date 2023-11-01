<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b272402e67.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/common.css">
</head>
<body class="frgt_passwrd">
<div class="loading-overlay" id="loadingOverlay" style="display:none">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<div class="container mt-5 forget_contain">
     <div id="message_new" class="alert" style="display: none;"></div>
    <h2 class="mb-4">Forgot Password</h2>
   
    <form id="resetPasswordForm" action="file_controler/logincontroller.php" method="post">
        <div class="form-group">
            <label for="employee_id">Employee ID:</label>
            <input type="text" class="form-control" id="employee_id" name="employee_id" value="EMP" required>
            <div id="emailHelp" class="form-text">Ex: your ID EMP OR TRAINEE : A23DE1070000  (OR) ATMP1070000  </div>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" value="" name="email" required>
            <div id="emailHelp" class="form-text">Registered mail</div>
        </div>
        <button type="button" class="btn btn-primary" id="resetPasswordBtn">Reset Password</button>
        <div class="new_verification mt-4" style="display: none;">
            <div class="form-group">
                <input type="text" class="form-control" id="code" name="code" placeholder="Enter the code" required>
            </div>
            <div class="form-group">
                <label></label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter the password" required>
            </div>
            <!-- <div class="form-group">
                <label></label>
                <input type="password" class="form-control" id="confirmpassword" name="confirmpassword"
                       placeholder="Confirm the password" required>
                       <span class="icon_eye" id="loginPasswordBtn" onclick="togglePasswordVisibility()">
                            <i class="far fa-eye" id="loginPasswordToggleIcon"></i>
                        </span>
            </div> -->
            <div class="form-outline mb-4 form-group">
            <label></label>
                        <div class="password-input-container">
                        <input type="password" id="confirmpassword" placeholder="Password" class="form-control" name="confirmpassword" value="">                        
                        <span class="icon_eye_frgtpasswrd" id="confirmpasswordbtn" onclick="forgetvisibllity()">
                            <i class="far fa-eye" id="confirmpasswordToggleIcon"></i>
                        </span>
                        </div> 
                    </div>
            <button type="submit" class="btn reset_pass" id="updatePasswordBtn" name="update_Password">Update Password
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $("#resetPasswordBtn").click(function () {
            var email = $("#email").val();
            var employee_id = $("#employee_id").val(); // Get the Employee ID
            showLoadingOverlay();
            $.ajax({
                type: "POST",
                url: "includes/check_email.php", // Replace with the URL where your PHP script is located
                data: {email: email, employee_id: employee_id}, // Pass both email and employee_id
                success: function (response) {
                    if (response === "success") {
                        $("#message_new").html("Verification code sent. Check your email.");
                        $("#message_new").removeClass("alert-danger").addClass("alert-success").show();
                        $(".new_verification").show(); // Show the "Verify Password" div
                        hideLoadingOverlay();
                    } else if (response === "not_exists") {
                        $("#message_new").html("Email not found in our records.");
                        $("#message_new").removeClass("alert-success").addClass("alert-danger").show();
                        $(".new_verification").hide(); // Hide the "Verify Password" div
                        hideLoadingOverlay();
                    } else {
                        $("#message_new").html("Error: " + response);
                        $("#message_new").removeClass("alert-success").addClass("alert-danger").show();
                        $(".new_verification").hide(); // Hide the "Verify Password" div
                        hideLoadingOverlay();
                    }
                },
                error: function () {
                    $("#message_new").html("Error: Could not connect to the server.");
                    $("#message_new").removeClass("alert-success").addClass("alert-danger").show();
                    $(".new_verification").hide(); // Hide the "Verify Password" div
                    hideLoadingOverlay();
                }
            });
        });
    });
</script>
<script src="js/login.js"></script>   
</body>
</html>
