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

<h3>OWNERS</h3>

<?php
$owners_sql = "SELECT owners_id, name, email FROM OWNERS";
$owners_result = $conn->query($owners_sql);

if ($owners_result && $owners_result->num_rows > 0) {
    echo "<table class='owners-table'>";
    echo "<tr><th>Owner ID</th><th>Name</th><th>Email</th><th>Actions</th></tr>";

    while ($row = $owners_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['owners_id'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>
                <a href='crud_function.php?action=update&id=" . $row['owners_id'] . "&table=owners' class='update-link'>Update</a> | 
                <a href='crud_function.php?action=delete&id=" . $row['owners_id'] . "&table=owners' class='delete-link' onclick=\"return confirm('Are you sure you want to delete this owner?');\">Delete</a>
              </td>";
        echo "</tr>";
    }

    // Add the last row with the "Create New Owner" link
    echo "<tr class='create-owner-row'>";
    echo "<td colspan='4'><a href='newowner.php' class='create-owner-link'>Add New Owner</a></td>";
    echo "</tr>";

    echo "</table>";
} else {
    echo "No owners found.";
}
?>

<br>

<a href="main.php" class="back-link">Main Menu</a><br><br>
<a href="logout.php" class="logout-link">Log out</a>

</body>
</html>
