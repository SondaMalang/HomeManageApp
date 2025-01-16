<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="loginform.css">
</head>
<body>

    <div class="login-container">
        <h2>Forgot Password</h2>

        <!-- Display error message if any -->
        <?php if (isset($_SESSION['error'])) { ?>
            <p class="error"><?php echo $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); // Clear error message after displaying ?>
        <?php } ?>

        <!-- Display success message if any -->
        <?php if (isset($_SESSION['success'])) { ?>
            <p class="success"><?php echo $_SESSION['success']; ?></p>
            <?php unset($_SESSION['success']); // Clear success message after displaying ?>
			<p class="note">If you don't see the email, please check your <strong>Spam</strong> or <strong>Junk</strong> folder.</p>
        <?php } ?>

        
		<form action="send_reset_link.php" method="POST" onsubmit="return validateEmail();">
    <input type="email" name="email" id="email" placeholder="Enter your email" required>
    <input type="submit" value="Send Reset Link">
</form>

    
        <p><a href="loginform.php">Back to Login</a></p>
    </div>

<script>
function validateEmail() {
    var email = document.getElementById('email').value;
    var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/; // Basic email regex pattern

    // Check if email matches the regex pattern
    if (!emailRegex.test(email)) {
        alert("Please enter a valid email address.");
        return false; // Prevent form submission if invalid
    }
    return true; // Allow form submission if valid
}
</script>

</body>
</html>
