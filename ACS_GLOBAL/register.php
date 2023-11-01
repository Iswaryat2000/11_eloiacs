<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b272402e67.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/ind.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dr+Sugiyama&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
<body class="register_login">
<!--                     registeration page                          -->
<div class="container register_contain">
      <!-- Loading Overlay -->
      
  <form action="controller.php" method="post">
          <div class="form-outline mb-4 registerpg">
            <label class="form-label" for="registerName">Employee ID</label>
            <input type="text" id="registerName" placeholder="Employee ID" class="form-control" value="EMP" name="name" required/>
           <div id="emailHelp" class="form-text">Ex: your ID EMP OR TRAINEE : A23DE1070000  (OR) ATMP1070000  </div>
          </div>
          <div class="form-outline mb-4 registerpg" hidden>
            <label class="form-label" for="registerEmail">Place</label>
            <input class="form-select" id="department" name="department"  value="ELOIACS" /> 
            
          </div>
          <div class="form-outline mb-4 hidden">
            <label class="form-label" for="registerEmail">Position</label>
            <input type="text" id="position" class="form-control hidden" name="position" value="Employee" readonly required />
          </div>
          <div class="form-outline mb-4 registerpg">
            <label class="form-label" for="registerEmail">Email</label>
            <input type="email" id="registerEmail" placeholder="Email" class="form-control" value="" name="email" required/>
          </div>
          <div class="form-outline mb-4 registerpg" >
            <label class="form-label" for="registerPassword">Password</label>
            <input type="password" id="registerPassword" placeholder="Password" name="password"  value="" class="form-control" required/>
          </div>
          <div class="form-outline mb-4">          
            <label class=" form-label" for="registerRepeatPassword">Confirm password</label>
          <div class="password-input-container">
            <input type="password" id="registerRepeatPassword" placeholder="Confirm Password" name="cpassword" value="" class="form-control" required/>
            <span class="icon_eye_register" id="loginPasswordBtn" onclick="togglePasswordVisibility()">                            
                            <i class="far fa-eye" id="cpasswordToggleIcon"></i>
            </span>      
          </div>
</div>
          <div class="form-check d-flex justify-content-center mb-4">
            <input class="form-check-input me-2" type="checkbox" checked value="" id="registerCheck" aria-describedby="registerCheckHelpText" required/>
            <label class="form-check-label" for="registerCheck">
              I have read and agree to the terms
            </label>
          </div>
          <div class="form-outline mb-4">
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="Enter OTP" id="otpInput" aria-label="Recipient's OTP" aria-describedby="button-addon2" name="otp" style="display: none;">        
            </div>
          <div id="message"></div> <!-- To display messages to the user -->
          </div>          
          <center>
          <button class="btn btn-outline-primary sign_lg_in_border" type="button" id="getOTP" name="signup"> GET OTP</button>
          <button class="btn btn-outline-primary sign_lg_in_border" type="submit" id="verifyOTP" style="display: none;" name="check">VERIFY OTP</button>
          </center>
          </form>
          <div class="text-center forg_lg_sg">
                        <p class="forg_lg_sg">Already have an account <a href="index.php"><span class="register_btn">Sign in</span></a></p>                        
                    </div>    
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>
</body>
<script src="js/register.js"></script>
</html>
