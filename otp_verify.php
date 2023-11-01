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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/common.css">
</head>
<style>
body {
        background-image: url('assets/images/register_backgroun.png');
        background-size: 100%;
        background-repeat: round;
        background-attachment: fixed;
        max-width: 100%;
        max-height: 100%;
        color: white;
    }
    .container {
    max-width: 650px;
    max-height: max-content;
    background: rgba(117, 110, 110, 0.05);
    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25), 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
    backdrop-filter: blur(10px);
    padding: 35px;
    position: absolute;
    left: 30%;
    top:15%;
}
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
#resetPasswordBtn,.reset_pass {
    margin: 20px;
}
.alert-success {
    margin: 0px -4px -38px -4px;
    padding: 2%;
    color: #0f5132;
    background-color: #d1e7dd;
    border-color: #badbcc;
}

.sign_lg_in,.sign_lg_in:hover {
    margin: 15px;
    background-color:#fb5607;
    color:White;
    font-size:16px;
    border:1px solid transparent;
}

</style>
<body class="otp_verify">

<div class="loading-overlay" id="loadingOverlay" style="display:none">
    <!-- You can add loading spinners or text here -->
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<div class="container box_container_lg_sg">
    <div class="row login_sg_reg">
        <div class="form-outline mb-4">
            <h2 class="verify">Verification</h2>
            <?php if (!empty($message)) : ?>
                <div class="alert <?php echo ($status === 'Verification successful') ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
<div id="message-container"></div> 
            <form action="controller.php" method="post">
                <div class="form-outline mb-4">
                    <label for="text">Employee Id:</label>
                    <input type="text" class="form-control" id="employee_id" name="employee_id" required>
                    <div id="emailHelp" class="form-text">Ex: your ID NO:E001 you have to Enter:EMP0001 add extra "0" & "EMP" </div>
                </div>
                <div class="form-outline mb-4">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div id="emailHelp" class="form-text">Registered mail</div>
                </div>
                <div class="form-outline mb-4">
                    <label for="otp">Enter OTP:</label>
                    <input type="text" class="form-control" id="otp" name="otp">
                </div>
                <div class="form-outline mb-4 btn_otpverify">
                    <div class="input-group">
                        <button type="submit" class="btn btn-primary sign_lg_in verify" name="otp_confirm">Verify OTP</button> <br>
                        <button type="button" class="btn btn-primary sign_lg_in" id="resend_otp" name="Resend_mail">Resend OTP</button>
                    </div>
                </div>
            </form>
        </div>
        
    </div>

</div>
<script src="js/login.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function() {
    $("#resend_otp").click(function() {
        // Get form data
        var email = $("#email").val();
        var employee_id = $("#employee_id").val();
        showLoadingOverlay(); // Show loading overlay before making the AJAX call

        // Create an AJAX request to resend OTP via email
        $.ajax({
            type: "POST",
            url: "includes/resendmail.php", // Update the URL to point to your new PHP file
            data: {
                resend_otp: true,
                email: email,
                employee_id: employee_id
            },
            success: function(response) {
                hideLoadingOverlay(); // Hide loading overlay on AJAX success

                // Handle the response from the server
                var jsonResponse = JSON.parse(response);
                var messageContainer = $("#message-container"); // Get the message container

                if (jsonResponse.status === "success") {
                    // OTP resent successfully, show success message
                    messageContainer.html('<div class="alert alert-success">' + jsonResponse.message + '</div>');
                } else {
                    // Handle other status or error messages
                    messageContainer.html('<div class="alert alert-danger">' + jsonResponse.message + '</div>');
                }
            },
            error: function(xhr, status, error) {
                hideLoadingOverlay(); // Hide loading overlay on AJAX error

                // Handle AJAX error
                console.error("AJAX Error: " + error);
                var messageContainer = $("#message-container"); // Get the message container
                messageContainer.html('<div class="alert alert-danger">An error occurred while sending the OTP. Please try again later.</div>');
            }
        });
    });
});

</script>
</body>
</html>

