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
        $description = $conn->real_escape_string($_POST['description']);
        $sql = "INSERT INTO materials (course_id, type, content, description, uploaded_at) 
                VALUES ('$course_id', 'youtube', '$youtube_link', '$description', NOW())";
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
        $description = $conn->real_escape_string($_POST['description']);
        $upload_dir = '../uploads/';
        
        // Create directory if not exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $upload_file = $upload_dir . $file_name;

        if (move_uploaded_file($file_tmp, $upload_file)) {
            $sql = "INSERT INTO materials (course_id, type, content, description, uploaded_at) 
                    VALUES ('$course_id', 'file', '$upload_file', '$description', NOW())";
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

// Fetch uploaded materials for this course
$sql = "SELECT * FROM materials WHERE course_id = '$course_id' ORDER BY uploaded_at DESC";
$result = $conn->query($sql);
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
    <a href="teacher_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    <h1>Upload Materials for Course ID: <?php echo $course_id; ?></h1>

    <!-- Add Exam Button -->
    <a href="add_exam.php?course_id=<?php echo $course_id; ?>" class="btn btn-success mb-3">Add Exam for This Course</a>

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

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" name="description" placeholder="Enter description about the material" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Upload Material</button>
    </form>

    <!-- Display uploaded materials -->
    <h2 class="mt-5">Uploaded Materials</h2>
    <?php if ($result->num_rows > 0): ?>
        <ul class="list-group">
            <?php while ($row = $result->fetch_assoc()): ?>
                <li class="list-group-item">
                    <?php if ($row['type'] == 'youtube'): ?>
                        <strong>YouTube Link:</strong> <a href="<?php echo htmlspecialchars($row['content']); ?>" target="_blank"><?php echo htmlspecialchars($row['content']); ?></a>
                    <?php else: ?>
                        <strong>File:</strong> <a href="<?php echo htmlspecialchars($row['content']); ?>" target="_blank">Download</a>
                    <?php endif; ?>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <span class="badge badge-info"><?php echo date("F j, Y, g:i a", strtotime($row['uploaded_at'])); ?></span>

                    <!-- Delete Button -->
                    <form action="delete_material.php" method="post" class="d-inline">
                        <input type="hidden" name="material_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No materials uploaded yet.</p>
    <?php endif; ?>

</div>

<script src="/PROJECT/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
