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

// Fetch the course_id from the student table using email
$sql_student = "SELECT course_id FROM student WHERE email_id = '$email'";
$result_student = $conn->query($sql_student);

// Check if a course is assigned to the student
if ($result_student->num_rows > 0) {
    $student_row = $result_student->fetch_assoc();
    $course_id = $student_row['course_id'];

    if (!$course_id) {
        echo "No course assigned to this student.";
        exit();
    }
} else {
    echo "Student not found.";
    exit();
}

// Fetch course materials
$sql = "SELECT * FROM materials WHERE course_id = '$course_id' ORDER BY uploaded_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Materials | SonicScholar</title>
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .card-header {
            background-color: #343a40;
            color: #ffffff;
            border-bottom: 1px solid #6c757d;
        }
        .card-body {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 0.375rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        .card {
            border: none;
            border-radius: 0.375rem;
        }
        .btn-outline-primary {
            color: #007bff;
            border-color: #007bff;
        }
        .btn-outline-primary:hover {
            background-color: #007bff;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light">
                <div class="position-sticky">
                    <h4 class="text-center my-4">SonicScholar</h4>
                    <div class="d-grid gap-2">
                    <a class="btn btn-secondary btn-block mb-2" href="dashboard.php">
                            <i class="fa fa-list"></i> Dashboard
                        </a>
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

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Uploaded Materials</h1>
                </div>

                <div class="row">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h4>TITLE: <?php echo htmlspecialchars($row['description']); ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($row['type'] == 'youtube'): ?>
                                            <!-- Custom description for YouTube links -->
                                            <p class="card-text">Get acquainted with the basics of using a Digital Audio Workstation (DAW).</p>
                                            <!-- Convert the YouTube link to embeddable format -->
                                            <?php
                                                $youtube_url = htmlspecialchars($row['content']);
                                                // Replace 'watch?v=' with 'embed/' in YouTube URL
                                                if (strpos($youtube_url, 'watch?v=') !== false) {
                                                    $embed_url = str_replace('watch?v=', 'embed/', $youtube_url);
                                                } else {
                                                    $embed_url = $youtube_url; // Already in correct format
                                                }
                                            ?>
                                            <iframe width="100%" height="315" src="<?php echo $embed_url; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        <?php else: ?>
                                            <p class="card-text"><strong>Type:</strong> <?php echo htmlspecialchars($row['type']); ?></p>
                                            <p class="card-text"><strong>Uploaded At:</strong> <?php echo htmlspecialchars($row['uploaded_at']); ?></p>
                                            <a href="download.php?material_id=<?php echo urlencode($row['id']); ?>&action=show" class="btn btn-outline-success">Show File</a>
                                            <a href="download.php?material_id=<?php echo urlencode($row['id']); ?>&action=download" class="btn btn-outline-primary">Download File</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No materials uploaded yet.</p>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Include your JS files here -->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
