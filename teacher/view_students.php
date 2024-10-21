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

// Fetch courses taught by the teacher
$sql_courses = "SELECT id, subject_name FROM courses WHERE teacher_id = {$teacher['teacher_id']}";
$result_courses = $conn->query($sql_courses);

// Check if a course is selected
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$students = [];

// Fetch students enrolled in the selected course
if ($course_id) {
    $sql_students = "SELECT registration_id, first_name, last_name, email_id 
                     FROM student 
                     WHERE course_id = $course_id";
    $result_students = $conn->query($sql_students);

    if ($result_students->num_rows > 0) {
        while ($row = $result_students->fetch_assoc()) {
            $students[] = $row;
        }
    }
}

// Get course name
$course_name = "";
if ($course_id) {
    $sql_course_name = "SELECT subject_name FROM courses WHERE id = $course_id";
    $result_course_name = $conn->query($sql_course_name);
    if ($result_course_name->num_rows > 0) {
        $course_name = $result_course_name->fetch_assoc()['subject_name'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icons/music-note.png"/>
    <link rel="stylesheet" href="/PROJECT/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/PROJECT/css/main.css">
    <title>View Students | SonicScholar</title>
</head>
<body>

<div class="container mt-4">
    <h1>View Students in Course</h1>
    
    <!-- Course Selection Dropdown -->
    <form method="GET" action="">
        <div class="form-group">
            <label for="course_id">Select a Course</label>
            <select name="course_id" id="course_id" class="form-control" onchange="this.form.submit()">
                <option value="">Select a course</option>
                <?php while ($course = $result_courses->fetch_assoc()): ?>
                    <option value="<?php echo $course['id']; ?>" 
                        <?php echo ($course_id == $course['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($course['subject_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </form>

    <h3>Course: 
        <?php 
        if ($course_id) {
            echo htmlspecialchars($course_name ?? "Unknown Course");
        }
        ?>
    </h3>

    <h4>Students Enrolled:</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($students)): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['registration_id']); ?></td>
                        <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($student['email_id']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No students enrolled in this course.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="teacher_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
</div>

<script src="/PROJECT/vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="/PROJECT/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
