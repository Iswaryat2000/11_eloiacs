
function togglePasswordVisibility() {
var passwordInput = document.getElementById('loginPassword');
var toggleIcon = document.getElementById('loginPasswordToggleIcon');

if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    toggleIcon.classList.remove('far', 'fa-eye');
    toggleIcon.classList.add('fas', 'fa-eye-slash');
} else {
    passwordInput.type = 'password';
    toggleIcon.classList.remove('fas', 'fa-eye-slash');
    toggleIcon.classList.add('far', 'fa-eye');
}}
function forgetvisibllity() {
    var passwordInput = document.getElementById('confirmpassword'); // Change 'loginPassword' to 'confirmpassword'
    var toggleIcon = document.getElementById('confirmpasswordToggleIcon'); // Change 'loginPasswordToggleIcon' to 'confirmpasswordToggleIcon'

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


function showLoadingOverlay() {
    $("body").addClass("loading");
    $("#loadingOverlay").fadeIn();
}

// Function to hide the loading overlay
function hideLoadingOverlay() {
    $("body").removeClass("loading");
    $("#loadingOverlay").fadeOut();
}

