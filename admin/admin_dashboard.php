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

// Assuming you have the admin_id in session
$admin_id = $_SESSION['admin_id'];

// Fetch total teachers
$sql_teachers = "SELECT COUNT(*) AS total_teachers FROM teacher";
$result_teachers = $conn->query($sql_teachers);
$total_teachers = $result_teachers->fetch_assoc()['total_teachers'];

// Fetch total students
$sql_students = "SELECT COUNT(*) AS total_students FROM student";
$result_students = $conn->query($sql_students);
$total_students = $result_students->fetch_assoc()['total_students'];

// Fetch total active courses
$sql_courses = "SELECT COUNT(*) AS total_courses FROM courses";
$result_courses = $conn->query($sql_courses);
$total_courses = $result_courses->fetch_assoc()['total_courses'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icons/music-note.png"/>
    <link rel="stylesheet" href="/PROJECT/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/PROJECT/css/main.css">
    <title>Admin Dashboard | SonicScholar</title>
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
        <h2 class="text-white text-center">Admin Dashboard</h2>
        <a href="#">Dashboard</a>
        <a href="manage_teachers.php">Manage Teachers</a>
        <a href="manage_students.php">Manage Students</a>
        <a href="#">Reports</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <h1>Welcome, Admin</h1>
        <p>You are logged in as <?php echo htmlspecialchars($admin_id); ?>.</p>
        
        <div class="row">
            <!-- Total Teachers Card -->
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Total Teachers</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $total_teachers; ?></h5>
                        <p class="card-text">Number of registered teachers.</p>
                    </div>
                </div>
            </div>

            <!-- Total Students Card -->
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Total Students</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $total_students; ?></h5>
                        <p class="card-text">Number of registered students.</p>
                    </div>
                </div>
            </div>

            <!-- Total Active Courses Card -->
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Active Courses</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $total_courses; ?></h5>
                        <p class="card-text">Number of active courses.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities Section -->
        <div class="mt-4">
            <h2>Recent Activities</h2>
            <ul class="list-group">
                <li class="list-group-item">Teacher John Doe added a new course.</li>
                <li class="list-group-item">Student Jane Smith enrolled in a course.</li>
                <li class="list-group-item">Teacher Alice Brown updated her profile.</li>
            </ul>
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
