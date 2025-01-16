<?php
session_start();

// Check if the user is not logged in, if so, redirect them to login page
if (!isset($_SESSION['login'])) {
    header('Location: login.php'); // Redirect to login page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tables</title>
    <link rel="stylesheet" href="managetables.css">
</head>
<body>

<?php
include('dbconnect.php');
?>

<h2>Manage Tables</h2>

<h3>HOUSES</h3>

<?php
$houses_sql = "SELECT houses_id, address, image_path, owners_id FROM Houses";
$houses_result = $conn->query($houses_sql);

if ($houses_result && $houses_result->num_rows > 0) {
    echo "<table class='houses-table'>";
    echo "<tr><th>House ID</th><th>Address</th><th>Image</th><th>Owner ID</th><th>Actions</th></tr>";
    
    while ($row = $houses_result->fetch_assoc()) {
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
                <td>
                    <a href='crud_function.php?action=update&id=" . $row['houses_id'] . "&table=houses' class='update-link'>Update</a> | 
                    <a href='crud_function.php?action=delete&id=" . $row['houses_id'] . "&table=houses' class='delete-link' onclick=\"return confirm('Are you sure you want to delete this house?');\">Delete</a>
                </td>
            </tr>";
    }

    // Add the last row with the "Create New House" link
    echo "<tr class='create-house-row'>";
    echo "<td colspan='5'><a href='newhouse.php' class='create-house-link'>Add New House</a></td>";
    echo "</tr>";

    echo "</table>";
} else {
    echo "No houses found.";
}
?>

<br>

<a href="main.php" >Main Menu</a><br><br>
<a href="logout.php" >Log out</a>

</body>
</html>
