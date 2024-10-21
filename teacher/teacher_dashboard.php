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

// Fetch all courses created by the teacher
$sql_courses = "SELECT * FROM courses WHERE teacher_id = '{$teacher['teacher_id']}'";
$result_courses = $conn->query($sql_courses);
$courses = $result_courses->fetch_all(MYSQLI_ASSOC); // Fetch all courses as an associative array

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icons/music-note.png"/>
    <link rel="stylesheet" href="/PROJECT/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/PROJECT/css/main.css">
    <title>Teacher Dashboard | SonicScholar</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #ffffff;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 260px; /* Sidebar width + some margin */
            padding: 20px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2 class="text-white text-center">Teacher Dashboard</h2>
        <a href="#">Dashboard</a>
        <a href="manage_courses.php">Manage Courses</a>
        <a href="view_students.php">View Students</a>
        <a href="teacher_profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <h1>Welcome, <?php echo htmlspecialchars($teacher['name']); ?></h1>
        <p>You are logged in as <?php echo htmlspecialchars($email); ?>.</p>
        
        <div class="row">
            <?php if (count($courses) > 0): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="col-md-4">
                        <!-- Wrap the card in an anchor tag that links to upload_materials.php with course ID as parameter -->
                        <a href="upload_materials.php?course_id=<?php echo htmlspecialchars($course['id']); ?>" style="text-decoration: none;">
                            <div class="card text-white bg-info mb-3">
                                <div class="card-header">Course: <?php echo htmlspecialchars($course['subject_name']); ?></div>
                                <div class="card-body">
                                    <h5 class="card-title">Course ID: <?php echo htmlspecialchars($course['id']); ?></h5>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No courses found for this teacher.</p>
            <?php endif; ?>
        </div>

    </div>

    <script src="/PROJECT/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="/PROJECT/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
