<?php
session_start();
// Include your database connection here
include "connection.php";
require_once('PHPMailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');