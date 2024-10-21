<?php
// Start session
session_start();

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'teacher') {
    header("Location: index.php"); // Redirect to login if not logged in as teacher
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

// Check if course_id is passed
if (!isset($_GET['course_id'])) {
    echo "No course selected.";
    exit();
}

$course_id = intval($_GET['course_id']);

// Initialize message
$message = "";

// Handle file uploads and links
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle YouTube link
    if (!empty($_POST['youtube_link'])) {
        $youtube_link = $conn->real_escape_string($_POST['youtube_link']);
        $sql = "INSERT INTO materials (course_id, type, content, uploaded_at) 
                VALUES ('$course_id', 'youtube', '$youtube_link', NOW())";
        if ($conn->query($sql) === TRUE) {
            $message = "YouTube link added successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }

    // Handle file upload (PDF, images)
    if (isset($_FILES['material_file']) && $_FILES['material_file']['error'] == 0) {
        $file_name = basename($_FILES['material_file']['name']);
        $file_tmp = $_FILES['material_file']['tmp_name'];
        $upload_dir = 'uploads/';
        
        // Create directory if not exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $upload_file = $upload_dir . $file_name;

        if (move_uploaded_file($file_tmp, $upload_file)) {
            $sql = "INSERT INTO materials (course_id, type, content, uploaded_at) 
                    VALUES ('$course_id', 'file', '$upload_file', NOW())";
            if ($conn->query($sql) === TRUE) {
                $message = "File uploaded successfully!";
            } else {
                $message = "Error: " . $conn->error;
            }
        } else {
            $message = "Failed to upload file.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PROJECT/vendor/bootstrap/css/bootstrap.min.css">
    <title>Upload Materials | SonicScholar</title>
</head>
<body>

<div class="container mt-5">
    <h1>Upload Materials for Course ID: <?php echo $course_id; ?></h1>

    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Form for uploading materials -->
    <form action="upload_materials.php?course_id=<?php echo $course_id; ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="youtube_link">YouTube Link</label>
            <input type="url" class="form-control" name="youtube_link" placeholder="Enter YouTube link">
        </div>

        <div class="form-group">
            <label for="material_file">Upload PDF/Images</label>
            <input type="file" class="form-control-file" name="material_file" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <button type="submit" class="btn btn-primary">Upload Material</button>
    </form>

    <a href="teacher_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<script src="/PROJECT/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
