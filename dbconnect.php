<?php

$servername = "localhost";
$username = "tenant";
$password = "Rent123!";
$dbname = "finproj";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>