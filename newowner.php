<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Owner</title>
    <link rel="stylesheet" href="newowner.css"> 
</head>
<body>
    <form method="post" action="crud_function.php">
        <p>
            <input type="text" name="name" placeholder="Name" required>
        </p>
        <p>
            <input type="email" name="email" placeholder="Email" required>
        </p>
        <button type="submit" name="create_owner">
            Create Owner
        </button>
		
		 <p><a href="manageowners.php">Back</a></p>
    <p><a href="main.php">Main menu</a></p>
    <p><a href="logout.php">Logout</a></p>
    </form>




<script>
    // Focus effect for inputs
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('focus', function() {
            input.style.borderColor = '#7889f5';
        });
        input.addEventListener('blur', function() {
            input.style.borderColor = '#ddd';
        });
    });

    // Button hover effect
    const button = document.querySelector('button[type="submit"]');
    button.addEventListener('mouseover', function() {
        button.style.backgroundColor = '#5468c1';
    });
    button.addEventListener('mouseout', function() {
        button.style.backgroundColor = '#7889f5';
    });
</script>


</body>
</html>