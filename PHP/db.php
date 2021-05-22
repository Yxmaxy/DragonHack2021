<?php
$servername = "localhost";
$username = "nikigre_gifUser";
$password = "Geslo-12345";
$dbname = "nikigre_gif";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


?>