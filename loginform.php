<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="loginform.css">
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>

        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <!-- Login Form -->
        <form action="login.php" method="POST">
            <input type="text" name="login" placeholder="Username or Email" required>
            <input type="password" name="pass" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>

        <p>Don't have an account? <a href="newuser.php">Register here</a></p>
	    <p><a href="forgot_password.php">Forgot your password?</a></p> <!-- Forgot password link -->

    </div>

</body>
</html>
