<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sonicscholar";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in student's email
$email = $_SESSION['email'];

// Fetch student details
$sql = "SELECT * FROM student WHERE email_id = '$email'";
$result = $conn->query($sql);

// Check if student exists
if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    echo "Student not found.";
    exit();
}

// Handle password update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Validate old password
    if ($old_password === $student['password']) {
        // Update password in the database
        $update_sql = "UPDATE student SET password = '$new_password' WHERE email_id = '$email'";
        if ($conn->query($update_sql) === TRUE) {
            $message = "Password updated successfully!";
        } else {
            $message = "Error updating password: " . $conn->error;
        }
    } else {
        $message = "Old password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile | SonicScholar</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light">
                <div class="position-sticky">
                    <h4 class="text-center my-4">SonicScholar</h4>
                    <div class="d-grid gap-2">
                        <a class="btn btn-primary btn-block mb-2" href="tutorials.php">
                            <i class="fa fa-book"></i> Course Materials
                        </a>
                        <a class="btn btn-warning btn-block mb-2" href="virtualkeyboard/index.php">
                            <i class="fa fa-music"></i> Musical Keyboard
                        </a>
                        <a class="btn btn-warning btn-block mb-2" href="profile.php">
                            <i class="fa fa-music"></i> Profile
                        </a>
                        <a class="btn btn-info btn-block mb-2" href="ebooks.php">
                            <i class="fa fa-book"></i> e-Books
                        </a>
                        <a class="btn btn-success btn-block mb-2" href="feedback.php">
                            <i class="fa fa-comments"></i> Feedback
                        </a>
                        <a class="btn btn-primary btn-block mb-2" href="song_search.php">
                            <i class="fa fa-search"></i> Song Search
                        </a>
                        <a class="btn btn-danger btn-block mb-2" href="logout.php">
                            <i class="fa fa-sign-out"></i> Logout
                        </a>
                    </div>
                </div>
            </nav>   
<div class="container mt-4">


    <h1>Student Profile</h1>
    
    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <h4>Details</h4>
    <ul class="list-group mb-4">
        <li class="list-group-item"><strong>Registration ID:</strong> <?php echo htmlspecialchars($student['registration_id']); ?></li>
        <li class="list-group-item"><strong>Name:</strong> <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></li>
        <li class="list-group-item"><strong>Birthday:</strong> <?php echo htmlspecialchars($student['birthday']); ?></li>
        <li class="list-group-item"><strong>Gender:</strong> <?php echo htmlspecialchars($student['gender']); ?></li>
        <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($student['email_id']); ?></li>
        <li class="list-group-item"><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone']); ?></li>
        <li class="list-group-item"><strong>Subject:</strong> <?php echo htmlspecialchars($student['subject']); ?></li>
    </ul>

    <h4>Update Password</h4>
    <form method="POST" action="profile.php">
        <div class="mb-3">
            <label for="old_password" class="form-label">Old Password</label>
            <input type="password" class="form-control" id="old_password" name="old_password" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <button type="submit" name="update_password" class="btn btn-primary">Update Password</button>
    </form>

    <!-- Back to Dashboard Button -->
    <a href="dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
</div>

<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
