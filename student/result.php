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

// Fetch the score based on user ID and exam ID
$exam_id = $_GET['exam_id'];
$user_id = $_GET['user_id'];

$result_check = $conn->prepare("SELECT score FROM exam_results WHERE exam_id = ? AND student_id = ?");
$result_check->bind_param("ii", $exam_id, $user_id);
$result_check->execute();
$existing_result = $result_check->get_result()->fetch_assoc();

if (!$existing_result) {
    echo "No results found.";
    exit();
}

$score = htmlspecialchars($existing_result['score']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light background color */
            font-family: Arial, sans-serif; /* Use a clean font */
        }

        .container {
            background-color: #ffffff; /* White background for container */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            padding: 20px; /* Padding inside the container */
            margin-top: 50px; /* Space from the top */
        }

        h1 {
            color: #343a40; /* Dark color for headings */
            margin-bottom: 20px; /* Space below the heading */
            text-align: center; /* Center align heading */
        }

        .score {
            font-size: 24px; /* Larger font for score */
            color: #28a745; /* Green color for score */
            text-align: center; /* Center align score */
            margin-bottom: 20px; /* Space below score */
        }

        .back-button {
            display: block; /* Make the button a block */
            margin: 0 auto; /* Center align the button */
            width: 200px; /* Set a fixed width */
        }

        .btn-custom {
            background-color: #b5b7dc ; /* Bootstrap primary color */
            border: none; /* No border */
            transition: background-color 0.3s; /* Smooth transition for hover */
        }

        .btn-custom:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
    </style>
</head>
<body>
    
<div class="container">
    <h1>Exam Result</h1>
    <div class="score">Your Score: <?php echo $score; ?></div>
    <p class="text-center">Thank you for participating in the exam!</p>
    <a href="take_exam.php" class="btn btn-custom back-button">Back to Exams</a>
</div>

<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
