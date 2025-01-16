<?php
session_start();  // Start the session
include('dbconnect.php');

// Check if the user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    // If not logged in, redirect to the login page
    header('Location: loginform.php');
    exit();
}

// Fetch user details based on the user_id session variable
$user_id = $_SESSION['user_id'];
$sql = "SELECT user_id, username, email, password FROM appusers WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Bind the user_id to the query
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();  // Fetch the user details
} else {
    echo "User not found.";
    exit();
}

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // If the user is submitting the form to update profile
    if (isset($_POST['update_profile'])) {
        // Retrieve form data
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $current_password = trim($_POST['current_password']);
        $new_password = !empty($_POST['new_password']) ? trim($_POST['new_password']) : null;
        $confirm_password = !empty($_POST['confirm_password']) ? trim($_POST['confirm_password']) : null;

        // Validation: Check for empty fields
        if (empty($username) || empty($email) || empty($current_password) || empty('new_password') || empty('confirm_password')){
            $_SESSION['error'] = "Username, email, and current password are required.";
            header('Location: profile.php');
            exit();
        }

        // Validation: Check if new password and confirm password match
        if ($new_password && $new_password !== $confirm_password) {
            $_SESSION['error'] = "New password and confirmation password do not match.";
            header('Location: profile.php');
            exit();
        }

        // Check if the current password is correct
        if (md5($current_password) !== $user['password']) {
            $_SESSION['error'] = "Current password is incorrect.";
            header('Location: profile.php');
            exit();
        }

        // If the new password is provided, prevent reuse of the current password
        if ($new_password && md5($new_password) === $user['password']) {
            $_SESSION['error'] = "New password cannot be the same as the current password.";
            header('Location: profile.php');
            exit();
        }

        // If password is valid, proceed to update user profile
        if ($new_password) {
            // Hash the new password
            $new_password = md5($new_password);
            // Update the profile with the new password
            $sql = "UPDATE appusers SET username = ?, email = ?, password = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $username, $email, $new_password, $user_id);
        } else {
            // If no new password is provided, update just username and email
            $sql = "UPDATE appusers SET username = ?, email = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $username, $email, $user_id);
        }

        if ($stmt->execute()) {
            $_SESSION['success'] = "Profile updated successfully.";
            $_SESSION['username'] = $username;  // Update session username
            header('Location: loginforn.php');
            exit();
        } else {
            $_SESSION['error'] = "Error updating profile.";
            header('Location: profile.php');
            exit();
        }
    }

    // Handle Account Deletion
    if (isset($_POST['delete_account'])) {
        $sql = "DELETE FROM appusers WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            // After deleting, destroy session and redirect to login page
            session_destroy();
            header('Location: loginform.php');
            exit();
        } else {
            $_SESSION['error'] = "Error deleting account.";
            header('Location: profile.php');
            exit();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>

<!-- Display Success or Error Messages -->
<?php if (isset($_SESSION['error'])): ?>
    <p style="color: red;"><?php echo $_SESSION['error']; ?></p>
    <?php unset($_SESSION['error']); ?>  <!-- Clear error message after displaying -->
<?php elseif (isset($_SESSION['success'])): ?>
    <p style="color: green;"><?php echo $_SESSION['success']; ?></p>
    <?php unset($_SESSION['success']); ?>  <!-- Clear success message after displaying -->
<?php endif; ?>

<!-- Profile Form to Update Details -->
<form action="profile.php" method="POST" onsubmit="return validateForm()">
    <h2>Update Profile</h2>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

    <label for="current_password">Current Password:</label>
    <input type="password" id="current_password" name="current_password" required>

    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password">

    <label for="confirm_password">Confirm New Password:</label>
    <input type="password" id="confirm_password" name="confirm_password">

    <div id="error-messages" style="color: red;"></div>

    <input type="submit" value="Update Profile" name="update_profile">
</form>

<!-- Option to Delete Account -->
<h2>Delete Account</h2>
<p>If you want to delete your account, please click below. This action cannot be undone.</p>
<form action="profile.php" method="POST">
    <input type="submit" value="Delete Account" name="delete_account" style="background-color: 7889f5; color: white;">
</form>

<a href="main.php">Main Menu</a><br>
<a href="logout.php">Logout</a>
</body>
</html>

<script>
    function validateForm() {
        let errorMessages = [];
        
        let currentPassword = document.getElementById('current_password').value;
        let newPassword = document.getElementById('new_password').value;
        let confirmPassword = document.getElementById('confirm_password').value;
        let errorDiv = document.getElementById('error-messages');
        
        // Check if new password is at least 8 characters long
        if (newPassword.length < 8) {
            errorMessages.push("Password must be at least 8 characters long.");
        }
        
        // Check if new password contains at least one number
        if (!/[0-9]/.test(newPassword)) {
            errorMessages.push("Password must contain at least one number.");
        }
        
        // Check if new password contains at least one special character
        if (!/[\W_]/.test(newPassword)) {
            errorMessages.push("Password must contain at least one special character.");
        }
        
        // Check if new password contains at least one uppercase letter
        if (!/[A-Z]/.test(newPassword)) {
            errorMessages.push("Password must contain at least one uppercase letter.");
        }
        
        // Check if new password matches the confirm password
        if (newPassword !== confirmPassword) {
            errorMessages.push("New password and confirmation password do not match.");
        }
        
        // Show errors if there are any
        if (errorMessages.length > 0) {
            errorDiv.innerHTML = errorMessages.join('<br>');
            return false;  // Prevent form submission
        }
        
        return true;  // Allow form submission if no errors
    }
</script>
