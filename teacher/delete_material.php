<?php
session_start();

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'teacher') {
    header("Location: index.php");
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

// Check if material_id is set
if (!isset($_POST['material_id'])) {
    header("Location: teacher_dashboard.php"); // Redirect to the dashboard if no material ID is provided
    exit();
}

$material_id = intval($_POST['material_id']);

// Fetch the material to check its type and content
$sql = "SELECT * FROM materials WHERE id = '$material_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $material = $result->fetch_assoc();

    // If it's a file, delete it from the server
    if ($material['type'] == 'file') {
        $file_path = $material['content'];
        if (file_exists($file_path)) {
            unlink($file_path); // Delete file from the server
        }
    }

    // Delete the material from the database
    $sql = "DELETE FROM materials WHERE id = '$material_id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: upload_materials.php?course_id=" . $material['course_id']); // Redirect back to the course materials page
        exit();
    } else {
        echo "Error deleting material: " . $conn->error;
    }
} else {
    echo "Material not found.";
}

$conn->close();
?>
