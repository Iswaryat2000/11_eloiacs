<?php include_once "file_controler/login_form.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b272402e67.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dr+Sugiyama&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
</head>
<body class="index_login">
    <div class="loading-overlay" id="loadingOverlay" style="display:none">
        <!-- You can add loading spinners or text here -->
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="container index_container">
    <div class="Image_logo">
        <center><img  src="assets/images/logo.png" alt="" style = "width: 200px;height: 70px;"/></center>
    </div>
    <div class="ind_content">
    <center><p class="otrait"><span class="typoghraphy">P</span>ortrait <span class="typoghraphy">L</span>ogin <span class="typoghraphy">F</span>orm </p></center>
    </div>
    <form action="" method="post">
        <!------------- login forms start----------------->
        <div id="errorMessage" class="alert alert-danger" style="display: none;"></div>        
        <div class="login_form">
                    <div class="tab-content login show login_sg_reg" id="pills-login-content">            
                        <form action="#" method="post">
                    <div class="form-outline mb-4">
                        <div id="emailHelp" class="form-text">EMAIL</div>
                        <input type="text" id="loginName" placeholder="Email" class="form-control" name="username" value="" />
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <!-- Password input -->                          
                   <div class="form-outline mb-4">
                        <div class="password-input-container">
                        <input type="password" id="loginPassword" placeholder="Password" class="form-control" name="password" value="">
                        <span class="icon_eye" id="loginPasswordBtn" onclick="togglePasswordVisibility()">
                            <i class="far fa-eye" id="loginPasswordToggleIcon"></i>
                        </span>
                        </div> 
                    </div>        
                    <!-- 2 column grid layout -->
                    <div class="row mb-4">
                        <div class="col-md-6 d-flex justify-content-start">
                            <div class="form-check mb-3 mb-md-0">
                                <input class="form-check-input" type="checkbox" name="remember_user" value="" id="loginCheck" />
                                <label class="form-check-label" for="remember_user"> Remember me </label>
                            </div>
                        </div>                      
                    </div>
                    <!-- Submit button -->
                    <center>
                        <button type="submit" class="btn btn-primary btn-block mb-4 sign_lg_in" name="signin_login">Sign in</button>
                    
                    
                    <div class="col-md-6 d-flex justify-content-center frgt_password ">
                            <a href="forget_password.php">Forgot password?</a>
                    </div>
                        
                        </center>
                    <!-- Register buttons -->
                    <div class="text-center forg_lg_sg">
                        <p class="forg_lg_sg">Don't have an account <a href="register.php"><span class="register_btn">Register Here</span></a></p>                        
                    </div>                 
        </form>
        </div>
    </div>
    <script src="js/login.js"></script>
    <script>
  <?php if (!empty($error_message)): ?>
    document.getElementById('errorMessage').textContent = '<?php echo $error_message; ?>';
    document.getElementById('errorMessage').style.display = 'block';

    // Add a timer to hide the error message after 30 seconds
    setTimeout(function () {
      document.getElementById('errorMessage').style.display = 'none';
    }, 15000); // 30,000 milliseconds (30 seconds)
  <?php endif; ?>
</script>


</body>
</html>
