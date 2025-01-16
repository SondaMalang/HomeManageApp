<?php 
session_start();
include('dbconnect.php');  // Make sure to include the database connection file

function authorizelogin($login, $pass, $conn) {
    // Sanitize input to prevent SQL injection
    $login = mysqli_real_escape_string($conn, $login);
    
    // Hash the entered password using MD5
    $hashed_pass = md5($pass);
    
    // Prepare SQL query to check both username and email using placeholders
    $sql = "SELECT * FROM APPUSERS WHERE username = ? OR email = ?";
    
    // Prepare the statement
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "ss", $login, $login);  // "ss" means two string parameters
        
        // Execute the statement
        mysqli_stmt_execute($stmt);
        
        // Get the result of the query
        $result = mysqli_stmt_get_result($stmt);
        
        // Check if query found any rows
        if (mysqli_num_rows($result) > 0) {
            // Fetch the row
            $row = mysqli_fetch_assoc($result);
            
            // Compare the hashed entered password with the database stored hash
            if ($hashed_pass === $row['password']) {
                // Set session variables for username and email
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                
                // Optional: You can store the login if you prefer
                $_SESSION['login'] = $login;
                
                return true;
            } else {
                // Invalid password (do not show details)
                return false;
            }
        } else {
            // User not found (do not show details)
            return false;
        }
        
        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing the SQL statement.<br>";
    }
    
    return false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the form
    $login = $_POST['login'];
    $pass = $_POST['pass'];
    
    // Check if login is valid
    if (authorizelogin($login, $pass, $conn)) {
        // If login is successful, redirect to the main page
        header('Location: main.php');
        exit();
    } else {
        // Set error message for invalid login
        echo "Invalid login or password!<br>";
    }
}
?>
