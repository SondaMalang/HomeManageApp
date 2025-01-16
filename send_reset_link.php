<?php
session_start();
require 'vendor/autoload.php'; // Include Composer's autoloader
use SendGrid\Mail\Mail;

require 'dbconnect.php'; 

// Check if email is provided
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Server-side email validation using PHP
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Please enter a valid email address.';
        header('Location: forgot_password.php');
        exit;
    }

    // Check if email exists in the database
    $query = "SELECT * FROM appusers WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, generate reset token and send email
        $user = $result->fetch_assoc();

        // Generate a unique reset token
        $reset_token = md5(uniqid(rand(), true));
        $expiry_time = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expiry in 1 hour

        // Store the reset token and expiry in the database
        $update_query = "UPDATE appusers SET reset_token = ?, reset_token_expiry = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('sss', $reset_token, $expiry_time, $email);
        $update_stmt->execute();

        // Send reset email using SendGrid
        $sendgridMail = new Mail();
        $sendgridMail->setFrom("musomala123@gmail.com", "Password Reset");
        $sendgridMail->setSubject("Password Reset Request");
        $sendgridMail->addTo($email);
        
        // Email content
        $reset_link = "http://localhost/FinalProject/reset_password.php?token=$reset_token";
        $htmlContent = "Click on the link below to reset your password: <br><a href='$reset_link'>$reset_link</a>";
        $sendgridMail->addContent("text/plain", strip_tags($htmlContent)); // Plain text version
        $sendgridMail->addContent("text/html", $htmlContent); // HTML version

        $sendgrid = new \SendGrid('your_api_key'); 

        try {
            $response = $sendgrid->send($sendgridMail);
            // Log the full response for debugging
            $statusCode = $response->statusCode();
            $responseBody = $response->body();
            $responseHeaders = $response->headers();

            // Output for debugging
            error_log("SendGrid Response Status Code: $statusCode");
            error_log("SendGrid Response Body: $responseBody");
            error_log("SendGrid Response Headers: " . json_encode($responseHeaders));

            // Check if email was successfully sent
            if ($statusCode == 202) {
                $_SESSION['success'] = 'Password reset link has been sent to your email.';
            } else {
                $_SESSION['error'] = 'Failed to send the email. Please try again.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            error_log("Error sending email: " . $e->getMessage());
        }
    } else {
        $_SESSION['error'] = 'No account found with that email address.';
    }
}

header('Location: forgot_password.php');
exit;
?>
