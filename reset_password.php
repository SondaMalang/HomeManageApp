<?php
session_start();
require 'dbconnect.php';

// Initialize error and success arrays
$errors = [];
$success = '';

if (isset($_GET['token'])) {
    $reset_token = $_GET['token'];

    // Validate the reset token
    $query = "SELECT * FROM appusers WHERE reset_token = ? AND reset_token_expiry > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $reset_token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid, process the form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_password'])) {
            $new_password = $_POST['new_password'];

            // Password validation: at least 8 characters, one uppercase, one number, one special character
            if (strlen($new_password) < 8) {
                $errors[] = 'Password must be at least 8 characters long.';
            }
            if (!preg_match('/[A-Z]/', $new_password)) {
                $errors[] = 'Password must contain at least one uppercase letter.';
            }
            if (!preg_match('/[0-9]/', $new_password)) {
                $errors[] = 'Password must contain at least one number.';
            }
            if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $new_password)) {
                $errors[] = 'Password must contain at least one special character.';
            }

            // If no errors, hash the password and update in the database
            if (empty($errors)) {
                // Hash the password using MD5
                $hashed_password = md5($new_password);

                // Update password in the database
                $update_query = "UPDATE appusers SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param('ss', $hashed_password, $reset_token);
                $update_stmt->execute();

                // Set success message
                $success = 'Your password has been reset successfully!';
                header('Location: loginform.php'); // Redirect after success
                exit;
            }
        }
    } else {
        $errors[] = 'Invalid or expired reset token.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="loginform.css">
</head>
<body>

    <div class="login-container">
        <h2>Reset Password</h2>

        <?php if (!empty($errors)) { ?>
            <ul class="error">
                <?php foreach ($errors as $error) { ?>
                    <li><?php echo $error; ?></li>
                <?php } ?>
            </ul>
        <?php } ?>

        <?php if ($success) { ?>
            <p class="success"><?php echo $success; ?></p>
        <?php } ?>

        <form action="" method="POST">
            <input type="password" name="new_password" placeholder="Enter your new password" required value="<?php echo isset($_POST['new_password']) ? $_POST['new_password'] : ''; ?>">
            <input type="submit" value="Reset Password">
        </form>

        <p><a href="loginform.php">Back to Login</a></p>
    </div>

</body>
</html>
