        // Function to toggle password visibility
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById('registerRepeatPassword');
            var toggleIcon = document.getElementById('cpasswordToggleIcon');
        
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('far', 'fa-eye');
                toggleIcon.classList.add('fas', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fas', 'fa-eye-slash');
                toggleIcon.classList.add('far', 'fa-eye');
            }
        }
                // Function to change buttons when a message is displayed
                function changeButtonsForMessage(otpSent) {
                    var getOTPButton = document.getElementById('getOTP');
                    var verifyOTPButton = document.getElementById('verifyOTP');
                    var otpInput = document.getElementById('otpInput');
        
                    if (otpSent) {
                        getOTPButton.style.display = 'none';
                        verifyOTPButton.style.display = 'block';
                        otpInput.style.display = 'block';
                    } else {
                        getOTPButton.style.display = 'block';
                        verifyOTPButton.style.display = 'none';
                        otpInput.style.display = 'none';
                    }
                }
        
                // Function to show the loading overlay
        function showLoadingOverlay() {
            $("body").addClass("loading");
            $("#loadingOverlay").fadeIn();
        }
        
        // Function to hide the loading overlay
        function hideLoadingOverlay() {
            $("body").removeClass("loading");
            $("#loadingOverlay").fadeOut();
        }
        
        
        
        $(document).ready(function () {
            $("#getOTP").click(function () {
                // Get form data
                var name = $("#registerName").val();
                var email = $("#registerEmail").val();
                var password = $("#registerPassword").val();
                var cpassword = $("#registerRepeatPassword").val();
                var department = $("#department").val();
                var position = $("#position").val();
        
                // Perform basic client-side validation
                if (name === "" || email === "" || password === "" || cpassword === "" || department === "" || position === "") {
                    $("#message").html("Please fill in all fields.");
                    return;
                }
        
                if (password !== cpassword) {
                    $("#message").html("Passwords do not match.");
                    return;
                }
        
                // Create an AJAX request to insert data and send OTP via email
                $.ajax({
                    type: "POST",
                    url: "includes/sendmail.php", // Update the URL to your sendmail.php file
                    data: {
                        signup: true,
                        name: name,
                        email: email,
                        password: password,
                        cpassword: cpassword,
                        department: department,
                        position: position
                    },
                    beforeSend: function () {
                        // Show loading overlay before the AJAX request is sent
                        showLoadingOverlay();
                    },
                    success: function (response) {
                        // Hide loading overlay on AJAX success
                        hideLoadingOverlay();
        
                        // Handle the response from the server
                        var jsonResponse = JSON.parse(response);
                        if (jsonResponse.status === "success") {
                            // User registered successfully, show success message
                            $("#message").html(jsonResponse.message);
        
                            // Update buttons to show "VERIFY OTP" button
                            changeButtonsForMessage(true);
                        } else {
                            // Handle other status or error messages
                            $("#message").html(jsonResponse.message);
                        }
                    },
                    error: function () {
                        // Hide loading overlay on AJAX error
                        hideLoadingOverlay();
                    }
                });
            });
        });