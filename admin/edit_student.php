<?php
// Start session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php"); // Redirect to login if not logged in
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

// Initialize variables for messages
$message = "";

// Fetch student details if registration_id is set
if (isset($_GET['registration_id'])) {
    $registration_id = (int)$_GET['registration_id'];
    
    $sql = "SELECT * FROM student WHERE registration_id = '$registration_id'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        die("No student found with this registration ID.");
    }
}

// Update Student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_student'])) {
    $registration_id = (int)$_POST['registration_id'];
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $birthday = $conn->real_escape_string($_POST['birthday']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $password = $conn->real_escape_string($_POST['password']);
    
    $sql = "UPDATE student SET first_name = '$first_name', last_name = '$last_name', 
            birthday = '$birthday', gender = '$gender', email_id = '$email', 
            phone = '$phone', subject = '$subject', password = '$password' 
            WHERE registration_id = '$registration_id'";

    if ($conn->query($sql) === TRUE) {
        $message = "Student updated successfully.";
        header("Location: manage_students.php"); // Redirect to manage students page
        exit(); // Ensure no further code is executed
    } else {
        $message = "Error updating student: " . $conn->error;
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
    <title>Edit Student | SonicScholar</title>
</head>
<body>

<div class="container">
    <h1 class="mt-4">Edit Student Details</h1>

    <?php if ($message): ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="registration_id" value="<?php echo $student['registration_id']; ?>">
        
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="birthday">Birthday</label>
            <input type="date" class="form-control" name="birthday" value="<?php echo htmlspecialchars($student['birthday']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="gender">Gender</label>
            <select class="form-control" name="gender" required>
                <option value="Male" <?php echo $student['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo $student['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo $student['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($student['email_id']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" class="form-control" name="subject" value="<?php echo htmlspecialchars($student['subject']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" value="<?php echo htmlspecialchars($student['password']); ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary" name="update_student">Update Student</button>
        <a href="manage_students.php" class="btn btn-secondary">Back to Manage Students</a>
    </form>
</div>

<script src="/PROJECT/vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="/PROJECT/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
