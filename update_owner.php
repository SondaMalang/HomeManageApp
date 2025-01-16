<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Owner</title>
<link rel="stylesheet" href="updateowner.css">
</head>
<body>


<h3>Update Owner</h3>

<!-- Form for updating the owner -->
<form action="crud_function.php?action=update&id=<?php echo $record_id; ?>&table=owners" method="POST">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required><br><br>

    <input type="submit" value="Update Owner">
<br><br>
<a href="manageowners.php">Back</a><br><br>
<a href="main.php">Main Menu</a><br><br>
<a href="logout.php">Log out</a>

</form>

</body>
</html>