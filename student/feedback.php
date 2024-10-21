<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Redirect to Gmail compose window
$to = 'support@sonicscholar.com'; // Replace with your support email
$subject = 'Feedback on SonicScholar';
$body = 'Please provide your feedback here.';

$gmailComposeUrl = "https://mail.google.com/mail/?view=cm&fs=1&to=" . urlencode($to) . "&su=" . urlencode($subject) . "&body=" . urlencode($body);

header("Location: " . $gmailComposeUrl);
exit();
?>
