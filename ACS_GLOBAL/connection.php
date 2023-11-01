<?php
$hostname = "localhost";
$username = "u726014584_acs_details";
$password = "Arun@Bibin@123";
$database = "u726014584_acs_details";
$conn = mysqli_connect($hostname,$username,$password,$database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>