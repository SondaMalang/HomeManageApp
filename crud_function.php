<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
<?php
session_start();
include('dbconnect.php');

// Function to sanitize input
function sanitizeInput($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));  // This function sanitizes input to prevent SQL injection
}

// Function to check for existing records (username/email)
function checkExistence($column, $value, $table) {
    global $conn;
    $sql = "SELECT * FROM $table WHERE $column = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $value);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

// New user creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_user'])) {
    $errors = [];

    // Sanitize and validate inputs
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    $email = sanitizeInput($_POST['email']);

    if (empty($username) || empty($password) || empty($email)) {
        $errors[] = "Username, Password, and Email cannot be empty.";
    }

    // Password validation
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[\W_]/', $password)) {
        $errors[] = "Password must contain at least 8 characters, one uppercase letter, one number, and one special character.";
    }

    // Check if username or email already exists
    if (empty($errors)) {
        if (checkExistence('username', $username, 'APPUSERS')) {
            $errors[] = "Username is already taken.";
        }
        if (checkExistence('email', $email, 'APPUSERS')) {
            $errors[] = "Email is already registered.";
        }
    }

    // If errors, redirect back with error messages
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: newuser.php");
        exit;
    }

    // Hash password and insert into database
    $hashed_password = md5($password);
    $sql = "INSERT INTO APPUSERS (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
	

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "User created successfully!";
        header("Location: newuser.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}



// New owner creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_owner'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Validate inputs
    if (empty($name) || empty($email)) {
        echo "Both Name and Email are required.";
    } else {
        // Check if email already exists in the database
        $check_email_sql = "SELECT * FROM OWNERS WHERE email = ?";
        $stmt = $conn->prepare($check_email_sql);
        $stmt->bind_param('s', $email); // 's' indicates the parameter is a string
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email already exists
            echo "<div style='font-size: 18px; font-weight: bold; color: red; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin: 20px 0; border-radius: 5px; text-align: center;'>
                    This email is already registered. Please use a different email.<br>
                    <a href='manageowners.php' style='color: #007bff; text-decoration: none; font-weight: normal;'>Back To Owners</a>
                  </div>";
        } else {
            // Proceed with the insert if email doesn't exist
            $sql = "INSERT INTO OWNERS (name, email) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $name, $email); // 'ss' indicates both parameters are strings

            if ($stmt->execute()) {
                echo "<div style='font-size: 18px; font-weight: bold; color: green; background-color: #f1fdf1; border: 1px solid #d4edda; padding: 15px; margin: 20px 0; border-radius: 5px; text-align: center;'>
                        Owner created successfully.<br>
                        <a href='main.php' style='color: #007bff; text-decoration: none; font-weight: normal;'>Main Menu</a>
                      </div>";
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        // Close the prepared statements
        $stmt->close();
    }
}


// New house creation


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_house'])) {
    $address = $_POST['address'];
    $image_path = !empty($_POST['custom_image_url']) ? $_POST['custom_image_url'] : $_POST['image_path'];
    $owners_id = $_POST['owners_id'];

    // Validate inputs
    if (empty($address) || empty($image_path) || empty($owners_id)) {
        echo "Please fill in all fields.";
    } else {
        // Check if owner already has a house
        $check_owner_sql = "SELECT 1 FROM HOUSES WHERE owners_id = '$owners_id'";
        $check_owner_result = $conn->query($check_owner_sql);

        if ($check_owner_result && $check_owner_result->num_rows > 0) {
            echo "Error: This owner already has a house.";
        } else {
            // Insert the house record
            $sql = "INSERT INTO HOUSES (address, image_path, owners_id) VALUES ('$address', '$image_path', '$owners_id')";
            if (mysqli_query($conn, $sql)) {
                echo "<div style='font-size: 18px; font-weight: bold; color: green; background-color: #f1fdf1; border: 1px solid #d4edda; padding: 15px; margin: 20px 0; border-radius: 5px; text-align: center;'>
        House created successfully.<br>
        <a href='main.php' style='color: #007bff; text-decoration: none; font-weight: normal;'>Main Menu</a>
      </div>";   
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
}

// Function to update records


function updateRecord($conn, $table, $data, $id) {
    if (empty($data) || empty($table) || empty($id)) {
        return false;
    }

    $setClause = [];
    $params = [];
    $types = '';

    foreach ($data as $column => $value) {
        $setClause[] = "$column = ?";
        $params[] = $value;
        $types .= 's';
    }

    $setClauseStr = implode(', ', $setClause);
    $params[] = $id;
    $types .= 'i';

    $sql = "UPDATE $table SET $setClauseStr WHERE {$table}_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        return false;
    }

    $stmt->bind_param($types, ...$params);

    if (!$stmt->execute()) {
        echo "Error executing update: " . $stmt->error;
        return false;
    }

    return true;
}
// Handle Update Action
if (isset($_GET['action']) && $_GET['action'] === 'update' && isset($_GET['id']) && isset($_GET['table']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $record_id = intval($_GET['id']);
    $table = $_GET['table'];
    $data = [];
    $errors = [];

    // Collect data based on the table type
    if ($table === 'houses') {
        $address = sanitizeInput($_POST['address']);
        $owners_id = intval($_POST['owners_id']);

        // Validate input
        if (empty($address) || strlen($address) < 5) {
            $errors[] = "Address must be at least 5 characters long.";
        }

        if (empty($owners_id)) {
            $errors[] = "Please select a valid owner.";
        }

        $data = ['address' => $address, 'owners_id' => $owners_id];
    } elseif ($table === 'owners') {
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);

        // Validate input
        if (empty($name)) {
            $errors[] = "Name is required.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email address.";
        }

        $data = ['name' => $name, 'email' => $email];
    }

    // If errors exist, show them
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    } else {
        // Update the record
        if (updateRecord($conn, $table, $data, $record_id)) {
            echo ucfirst($table) . " updated successfully.";
        } else {
            echo "Error updating $table.";
        }
    }
}

// Fetch current data for the form
if (isset($_GET['id']) && isset($_GET['table'])) {
    $table = $_GET['table'];
    $record_id = intval($_GET['id']);
    $sql = "SELECT * FROM $table WHERE {$table}_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $record_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Record not found.";
        exit;
    }

    // Include the correct update form
    if ($table === 'houses') {
        include 'update_house.php';
    } elseif ($table === 'owners') {
        include 'update_owner.php';
    }
}


// **Delete functionality remains unchanged below**
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id']) && isset($_GET['table'])) {

    $record_id = $_GET['id'];
    $table = $_GET['table'];

    if ($table == 'owners') {
        // SQL query to delete the owner record
        $sql = "DELETE FROM owners WHERE owners_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $record_id);  // Assuming owners_id is an integer
        if ($stmt->execute()) {
            echo "Owner deleted successfully.";
            header("Location: manageowners.php");  // Redirect to main page after deletion
            exit;
        } else {
            echo "Error deleting owner: " . $stmt->error;
        }
        $stmt->close();
    } elseif ($table == 'houses') {
        // Handle house deletion (already present in your code)
        $sql = "DELETE FROM houses WHERE houses_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $record_id);  // Assuming houses_id is an integer
        if ($stmt->execute()) {
            echo "House deleted successfully.";
            header("Location: managehouses.php");
            exit;
        } else {
            echo "Error deleting house: " . $stmt->error;
        }
        $stmt->close();
    }
}


?>

</body>
</html>