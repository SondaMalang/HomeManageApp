<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tables</title>
<link rel="stylesheet" href="viewtables.css">
    
</head>
<?php
session_start();

if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
    header('Location: loginform.php');
    exit();
}


// Database parameters
$dbhost = "localhost";
$dbuser = "tenant";
$dbpass = "Rent123!";
$dbname = "finproj";

// Connect to the database
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "";
}
mysqli_set_charset($link, "utf8");


// Fetch from House Table
$sql = "SELECT houses_id, address, image_path, owners_id FROM houses"; 
$result = mysqli_query($link, $sql);

echo "<table border='1'>
        <tr>
            <th>House ID</th>
            <th>Address</th>
            <th>Image</th>
            <th>Owner ID</th>
        </tr>";

while ($row = mysqli_fetch_assoc($result)) {
    $image_url = $row['image_path']; 
    echo "<tr>
            <td>{$row['houses_id']}</td>
            <td>{$row['address']}</td>
            <td>
                <a href='$image_url' target='_blank'>
                    <img src='$image_url' alt='House Image' style='width:100px;height:auto;'>
                </a>
            </td>
            <td>{$row['owners_id']}</td>
          </tr>";
}
echo "</table>";


// Fetch from Owner Table
$sql = "SELECT owners_id, name, email FROM owners"; 
$result = mysqli_query($link, $sql);

echo "<table border='1'>
        <tr>
            <th>Owner ID</th>
            <th>Name</th>
            <th>Email</th>
        </tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['owners_id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
          </tr>";
}
echo "</table>";



$uploadDir = '/uploads/'; // Relative path


mysqli_close($link);
?>


<body>

<p>
    You are logged in as: 
    <strong>
        <?php 
        if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
            echo htmlspecialchars($_SESSION['login']); 
        } else {
            echo "Unknown User";
        }
        ?>
    </strong>
</p>

<a href="main.php">Back to Main</a>
<br/><br/>
<a href="logout.php">Logout</a>

</body>
</html>
