<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Clear the remember me cookie, if it exists
if (isset($_COOKIE['remember_user'])) {
    unset($_COOKIE['remember_user']);
    setcookie('remember_user', '', time() - 3600, '/');
}

// Redirect to the login page or any other page you prefer
header("Location:../index.php");
exit();
?>
