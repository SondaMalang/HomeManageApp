<?php
include('dbconnect.php');

// Function to get available images from the 'uploads' directory, excluding used images
function getAvailableImages($conn) {
    // Fetch images that are already used in the HOUSES table
    $sql = "SELECT image_path FROM HOUSES";
    $result = $conn->query($sql);

    // Create an array of used image paths
    $used_images = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $used_images[] = $row['image_path'];
        }
    }

    // Get available images from the 'uploads' directory
    $image_directory = 'uploads/';
    $images = array_diff(scandir($image_directory), array('..', '.'));

    // Filter out the used images
    $available_images = array_filter($images, function($image) use ($used_images, $image_directory) {
        return !in_array($image_directory . $image, $used_images);
    });

    return $available_images;
}

// Fetch available images for the dropdown
$images = getAvailableImages($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New House</title>
    <link rel="stylesheet" href="newhouse.css">

</head>
<body>

<form action="crud_function.php" method="post">
    <label for="address">Address:</label>
    <input type="text" name="address" id="address" required>
    <br><br>

    <label for="image">Select Image:</label>
    <select name="image_path" id="image">
        <?php
        if (!empty($images)) {
            // If there are available images, show them in the dropdown
            foreach ($images as $image) {
                echo "<option value='uploads/$image'>$image</option>";
            }
        } else {
            // If no images are available, show a custom URL input
            echo "<option value=''>No images available</option>";
        }
        ?>
    </select>
    
    <!-- Conditionally display a text input for a custom image URL -->
    <?php if (empty($images)) : ?>
        <br><br>
        <label for="custom_image_url">Or Enter Image URL:</label>
        <input type="text" name="custom_image_url" id="custom_image_url" placeholder="Enter custom image URL">
    <?php endif; ?>

    <br><br>

    <label for="owners_id">Select Owner:</label>
    <select name="owners_id" id="owners_id">
        <?php
        // Fetch owners who don't already own a house
$owners_sql = "
    SELECT o.owners_id, o.name 
    FROM OWNERS o
    LEFT JOIN HOUSES h ON o.owners_id = h.owners_id
    WHERE h.owners_id IS NULL
";
$owners_result = $conn->query($owners_sql);

if ($owners_result && $owners_result->num_rows > 0) {
    while ($row = $owners_result->fetch_assoc()) {
        echo "<option value='" . $row['owners_id'] . "'>" . $row['owners_id'] . " - " . $row['name'] . "</option>";
    }
} else {
    echo "<option value=''>No owners available</option>";
}

        ?>
    </select>
    <br><br>

    <input type="hidden" name="create_house" value="1">
    <input type="submit" value="Add House">


    <p><a href="managehouses.php">Back</a></p>
    <p><a href="main.php">Main menu</a></p>
    <p><a href="logout.php">Logout</a></p>
</form>

	


</body>
</html>