<?php
session_start(); // Start the session to access session variables
require_once 'dbconnect.php'; // Include database connection file

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = htmlspecialchars($_SESSION['username']);
} else {
    header("Location: loginform.php"); // Redirect to login page if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Home Management System</h1>
        </header>

        <main class="main-content">
		<p>The Home Management System allows users to manage property details, 
		including houses and owners. Users can easily update profiles and 
		manage database records. The interactive interface ensures efficient property management 
		in just a few clicks.</p>
            <!-- Section for Managing Database Tables -->
            <section>
				<div class="card">
                    <h3>View Tables</h3>
                    <a href="dbtables.php" class="btn">View Tables</a>
                </div> <br>
                <div class="card">
                 
                    <h3>Manage Houses</h3>
                    <a href="managehouses.php" class="btn">Go to Tables</a>
                </div> <br>             
                <div class="card">
				
				<h3>Manage Owners</h3>
                    <a href="manageowners.php" class="btn">Go to Tables</a>
                </div> <br>             
                <div class="card">
                    
                    <h3>Profile</h3>
                    <a href="profile.php" class="btn">Update Profile</a>
                </div> <br>
				
				<div class="card">
                    
                    <h3>Logout</h3>
                    <a href="logout.php" class="btn">Logout</a>
                </div>
				
            </section>
			
        </main>

        <footer class="footer">
            <p>&copy; Home Management System <?php echo date('Y'); ?> </p>
        </footer>
    </div>
</body>
</html>
