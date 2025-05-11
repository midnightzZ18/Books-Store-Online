<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "data_db";


$conn = new mysqli($servername, $username, $password, $dbname);
$condb= mysqli_connect("localhost","root","","data_db") or die("Error: " . mysqli_error($condb));
        mysqli_query($condb, "SET NAMES 'utf8' ");
        date_default_timezone_set('Asia/Bangkok');
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>

