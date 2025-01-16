

<link rel="stylesheet" href="updatehouse.css">
<h3>Update House</h3>

<form action="crud_function.php?action=update&id=<?php echo $record_id; ?>&table=houses" method="POST">
    <label for="address">Address:</label>
    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($row['address']); ?>" required><br><br>


    <label for="owners_id">Owner ID:</label>
    <select id="owners_id" name="owners_id" required>
        <option value="">Select an Owner</option>
        <?php
        $ownersSql = "
            SELECT owners_id, name 
            FROM owners 
            WHERE owners_id NOT IN (
                SELECT owners_id FROM houses WHERE houses_id != ?
            ) OR owners_id = ?
        ";
        $stmt = $conn->prepare($ownersSql);
        $stmt->bind_param('ii', $record_id, $row['owners_id']);
        $stmt->execute();
        $ownersResult = $stmt->get_result();

        if ($ownersResult->num_rows > 0) {
            while ($owner = $ownersResult->fetch_assoc()) {
                $selected = ($owner['owners_id'] == $row['owners_id']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($owner['owners_id']) . "' $selected>" . htmlspecialchars($owner['name']) . "</option>";
            }
        } else {
            echo "<option value=''>No available owners</option>";
        }
        ?>
    </select><br><br>

    <input type="submit" value="Update House">
    <a href="managehouses.php">Back</a>
    <a href="main.php">Main menu</a>
    <a href="logout.php">Logout</a>
</form>
