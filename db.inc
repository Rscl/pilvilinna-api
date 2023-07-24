<?php
$servername = "localhost";
$username = "pilvilinna";
$password = "Kyberlinna!";
$dbname = "pilvilinna";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
