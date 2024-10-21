<?php
// Start session
session_start();

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'teacher') {
    header("Location: index.php"); // Redirect to login if not logged in as teacher
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sonicscholar";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in teacher's email
$email = $_SESSION['email'];

// Fetch teacher information
$sql_teacher = "SELECT * FROM teacher WHERE email_id = '$email'";
$result_teacher = $conn->query($sql_teacher);
$teacher = $result_teacher->fetch_assoc();

// Update teacher information if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update profile information
    if (isset($_POST['update_profile'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $theory_level = intval($_POST['theory_level']);
        $practical_level = intval($_POST['practical_level']);

        $sql_update = "UPDATE teacher SET name='$name', theory_level='$theory_level', practical_level='$practical_level' WHERE email_id='$email'";

        if ($conn->query($sql_update) === TRUE) {
            // Optionally, you can set a session message or redirect after successful update
            header("Location: teacher_profile.php?update=profile_success");
            exit();
        } else {
            $error_message = "Error updating profile: " . $conn->error;
        }
    }

    // Update password if the password change form is submitted
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Check if current password matches
        if ($teacher['password'] === $current_password) {
            // Check if new password and confirm password match
            if ($new_password === $confirm_password) {
                $new_password = $conn->real_escape_string($new_password);
                $sql_password_update = "UPDATE teacher SET password='$new_password' WHERE email_id='$email'";

                if ($conn->query($sql_password_update) === TRUE) {
                    header("Location: teacher_profile.php?update=password_success");
                    exit();
                } else {
                    $error_message = "Error updating password: " . $conn->error;
                }
            } else {
                $error_message = "New password and confirmation do not match.";
            }
        } else {
            $error_message = "Current password is incorrect.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icons/music-note.png"/>
    <link rel="stylesheet" href="/PROJECT/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/PROJECT/css/main.css">
    <title>Teacher Profile | SonicScholar</title>
</head>
<body>

<div class="container mt-4">
<a href="teacher_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    <h1>Teacher Profile</h1>
    
    <?php if (isset($_GET['update']) && $_GET['update'] == 'profile_success'): ?>
        <div class="alert alert-success" role="alert">
            Profile updated successfully!
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['update']) && $_GET['update'] == 'password_success'): ?>
        <div class="alert alert-success" role="alert">
            Password updated successfully!
        </div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <h3>Update Profile</h3>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($teacher['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="theory_level" class="form-label">Theory Level</label>
            <input type="number" class="form-control" id="theory_level" name="theory_level" value="<?php echo htmlspecialchars($teacher['theory_level']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="practical_level" class="form-label">Practical Level</label>
            <input type="number" class="form-control" id="practical_level" name="practical_level" value="<?php echo htmlspecialchars($teacher['practical_level']); ?>" required>
        </div>
        <input type="hidden" name="update_profile" value="1">
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>

    <form method="POST" class="mt-4">
        <h3>Change Password</h3>
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <input type="hidden" name="change_password" value="1">
        <button type="submit" class="btn btn-danger">Change Password</button>
    </form>

   
</div>

<script src="/PROJECT/vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="/PROJECT/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
