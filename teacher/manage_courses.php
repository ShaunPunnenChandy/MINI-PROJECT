<?php
// Start session to check teacher login
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sonicscholar";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize messages
$message = "";

// Get teacher ID based on logged-in email
$teacher_email = $_SESSION['email'];
$sql_teacher = "SELECT teacher_id FROM teacher WHERE email_id = '$teacher_email'";
$result_teacher = $conn->query($sql_teacher);

if ($result_teacher->num_rows > 0) {
    $teacher = $result_teacher->fetch_assoc();
    $teacher_id = $teacher['teacher_id'];
} else {
    $message = "Error: Teacher not found.";
}

// Handle Add Course
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_course'])) {
    $subject_name = $conn->real_escape_string($_POST['subject_name']);

    // Insert into courses table
    $sql = "INSERT INTO courses (subject_name, teacher_id, created_at, updated_at) 
            VALUES ('$subject_name', '$teacher_id', NOW(), NOW())";

    if ($conn->query($sql) === TRUE) {
        $message = "New course added successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle Delete Course
if (isset($_GET['delete'])) {
    $course_id = $_GET['delete'];

    // Check if any students are enrolled in the course
    $sql_check_enrollment = "SELECT COUNT(*) as student_count FROM student WHERE course_id = $course_id";
    $result_check_enrollment = $conn->query($sql_check_enrollment);
    $row = $result_check_enrollment->fetch_assoc();

    if ($row['student_count'] > 0) {
        $message = "Cannot delete course. Students are enrolled in this course.";
    } else {
        $sql = "DELETE FROM courses WHERE id = $course_id";
        if ($conn->query($sql) === TRUE) {
            $message = "Course deleted successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Fetch Courses for Listing
$sql_courses = "SELECT * FROM courses WHERE teacher_id = '$teacher_id'";
$result_courses = $conn->query($sql_courses);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PROJECT/vendor/bootstrap/css/bootstrap.min.css">
    <title>Manage Courses | SonicScholar</title>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Manage Courses</h1>

    <!-- Back to Dashboard Button -->
    <div class="text-right mb-3">
        <a href="teacher_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Form for adding course -->
    <div class="card mb-4">
        <div class="card-header">
            Add New Course
        </div>
        <div class="card-body">
            <form method="post" action="">
                <div class="form-group">
                    <label for="subject_name">Subject Name</label>
                    <input type="text" class="form-control" name="subject_name" required>
                </div>

                <button type="submit" name="add_course" class="btn btn-primary">Add Course</button>
            </form>
        </div>
    </div>

    <!-- List of Courses -->
    <h2>Courses List</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Subject</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = $result_courses->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['subject_name']; ?></td>
                <td>
                    <a href="manage_courses.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="/PROJECT/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
