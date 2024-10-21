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
    } else {
        $message = "Error updating student: " . $conn->error;
    }
}

// Delete Student
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    $sql = "DELETE FROM student WHERE registration_id = '$delete_id'";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Student deleted successfully.";
    } else {
        $message = "Error deleting student: " . $conn->error;
    }
}

// Fetch Students
$sql = "SELECT * FROM student";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icons/music-note.png"/>
    <link rel="stylesheet" href="/PROJECT/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/PROJECT/css/main.css">
    <title>Manage Students | SonicScholar</title>
</head>
<body>

<div class="container">
    <h1 class="mt-4">Manage Students</h1>

    <a href="admin_dashboard.php" class="btn btn-primary mb-3">Back to Dashboard</a> <!-- Moved button to the top -->

    <?php if ($message): ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="card mt-4">
        <div class="card-header">Existing Students</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Registration ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Birthday</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subject</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['registration_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['birthday']); ?></td>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td><?php echo htmlspecialchars($row['email_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                <td>
                                    <!-- Redirect to edit_student.php -->
                                    <a href="edit_student.php?registration_id=<?php echo $row['registration_id']; ?>" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <!-- Delete Button -->
                                    <a href="?delete_id=<?php echo $row['registration_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">No students found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="/PROJECT/vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="/PROJECT/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
