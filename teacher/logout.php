<?php
// Start session
session_start();

// Destroy session
session_destroy();

// Redirect to the login page
header("Location: ..\index.php");
exit();
?>

