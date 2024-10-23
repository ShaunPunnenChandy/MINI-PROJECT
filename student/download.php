<?php
session_start();

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

// Check if material_id and action are provided
if (isset($_GET['material_id']) && isset($_GET['action'])) {
    $material_id = intval($_GET['material_id']);
    $action = $_GET['action'];

    // Fetch file path and type from the database based on material_id
    $sql = "SELECT content, type FROM materials WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $material_id);
    $stmt->execute();
    $stmt->bind_result($content, $type);
    $stmt->fetch();
    $stmt->close();

    // Check if material exists
    if ($content) {
        // If it's a YouTube link, redirect to it regardless of the action
        if ($type === 'youtube') {
            header('Location: ' . $content);
            exit();
        }

        // Handle file download or show (PDF, images, etc.)
        $file = basename($content); // Prevent directory traversal
        $fileFullPath = '../uploads/' . $file; // Adjust the path as necessary

        // Check if the file exists on the server
        if (file_exists($fileFullPath)) {
            $mimeType = mime_content_type($fileFullPath);

            if ($action === 'show') {
                // Set headers to display the file in the browser
                header('Content-Type: ' . $mimeType);
                header('Content-Disposition: inline; filename="' . $file . '"'); // Show in browser
            } elseif ($action === 'download') {
                // Set headers to download the file
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $file . '"'); // Download
            }

            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileFullPath));
            flush(); // Flush system output buffer
            readfile($fileFullPath);
            exit;
        } else {
            echo "File not found on the server.";
        }
    } else {
        echo "Material not found in the database.";
    }
} else {
    echo "No material specified or invalid action.";
    exit();
}

// Close the database connection
$conn->close();
?>
