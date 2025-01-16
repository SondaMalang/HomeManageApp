<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['house_photo'])) {
    $uploadDir = __DIR__ . '/uploads/'; // Path to uploads directory
    $fileName = uniqid() . '-' . basename($_FILES['house_photo']['name']); // Generate unique file name
    $filePath = $uploadDir . $fileName;

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($_FILES['house_photo']['tmp_name'], $filePath)) {
        echo "File uploaded successfully! <br>";
        echo "Path: " . $filePath . "<br>";
        echo "<img src='uploads/" . htmlspecialchars($fileName) . "' alt='Uploaded Image' style='max-width:300px;'>";
    } else {
        echo "Failed to upload the file.";
    }
} else {
    echo "No file uploaded.";
}
?>

<form action="upload.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="house_photo" accept="image/*">
    <button type="submit">Upload</button>
</form>
