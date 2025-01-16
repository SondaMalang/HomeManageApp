<?php
session_start();
$errors = $_SESSION['errors'] ?? [];
$form_data = $_SESSION['form_data'] ?? [];
$success_message = $_SESSION['success'] ?? null;

// Clear error and form data from the session
unset($_SESSION['errors'], $_SESSION['form_data']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="newuser.css">
</head>
<body>
<!-- Sign-up Form -->
<form method="post" action="crud_function.php">
    <h2>Create New Account</h2>
    <p>
        <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($form_data['username'] ?? ''); ?>" required>
    </p>
    <p>
        <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>" required>
    </p>
    <p>
        <input type="password" name="password" placeholder="Password" required>
    </p>
    <button type="submit" name="create_user">Create User</button>
    <p><a href="loginform.php">Back to Login</a></p>
</form>

<!-- Display success message -->
<?php if ($success_message): ?>
    <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
    <?php unset($_SESSION['success']); // Clear success message only after displaying ?>
<?php endif; ?>

<!-- Display errors -->
<?php if (!empty($errors)): ?>
    <div class="error-message">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

</body>
</html>
