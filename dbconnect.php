<?php

$servername = "sql7.freesqldatabase.com";
$username = "sql7773778";
$password = "3RXF5SqSpr";
$dbname = "sql7773778";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
