<?php
session_start(); // Start the session

if (!isset($_SESSION['email'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sonicscholar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch exams for the student
$sql = "SELECT e.* FROM exams e 
        WHERE e.course_id IN (
            SELECT sc.course_id 
            FROM student sc 
            WHERE sc.email_id = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['email']); // Use "s" for string
$stmt->execute();
$exams = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Exams</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
</head>
<body>
    
<div class="container-fluid">
<div class="row">
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
    <h1>Available Exams</h1>
    <ul class="list-group">
        <?php if ($exams->num_rows > 0): ?>
            <?php while ($exam = $exams->fetch_assoc()): ?>
                <li class="list-group-item">
                    <a href="exam.php?exam_id=<?php echo $exam['exam_id']; ?>"><?php echo htmlspecialchars($exam['exam_title']); ?></a>
                </li>
            <?php endwhile; ?>
        <?php else: ?>
            <li class="list-group-item">No available exams at this time.</li>
        <?php endif; ?>
    </ul>
</div>
<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
