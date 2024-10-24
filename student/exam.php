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

// Fetch the user ID based on the logged-in user's email
$sql = "SELECT registration_id FROM student WHERE email_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['email']); // Use the session email
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['registration_id']; // Get the user ID

// Fetch the exam by ID
$exam_id = $_GET['exam_id'];

// Check if the student has already submitted this exam
$result_check = $conn->prepare("SELECT score FROM exam_results WHERE exam_id = ? AND student_id = ?");
$result_check->bind_param("ii", $exam_id, $user_id); // Use the fetched user ID
$result_check->execute();
$existing_result = $result_check->get_result()->fetch_assoc();

if ($existing_result) {
    // If a result exists, show the score and disable further actions
    header("Location: result.php?exam_id=" . $exam_id . "&user_id=" . $user_id);
    exit();
    
}

// Fetch questions for the exam
$sql = "SELECT * FROM questions WHERE exam_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$questions = $stmt->get_result();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $score = 0;

    // Fetch questions again for processing the answers
    $questions->data_seek(0); // Reset pointer to the beginning

    while ($question = $questions->fetch_assoc()) {
        $question_id = $question['question_id'];
        if (isset($_POST['question_' . $question_id])) {
            $answer_id = $_POST['question_' . $question_id];
            $answer_sql = "SELECT is_correct FROM answers WHERE answer_id = ?";
            $answer_stmt = $conn->prepare($answer_sql);
            $answer_stmt->bind_param("i", $answer_id);
            $answer_stmt->execute();
            $answer_result = $answer_stmt->get_result();
            if ($answer_result->num_rows > 0) {
                $answer = $answer_result->fetch_assoc();
                if ($answer['is_correct']) {
                    $score++;
                }
            }
        }
    }

    // Save the result
    $stmt = $conn->prepare("INSERT INTO exam_results (exam_id, student_id, score) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $exam_id, $user_id, $score); // Use the fetched user ID
    $stmt->execute();

    // Redirect to the results page
    header("Location: result.php?exam_id=" . $exam_id . "&user_id=" . $user_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Exam</title>
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
            margin-top: 20px; /* Space from the top */
        }

        h1 {
            color: #343a40; /* Dark color for headings */
            margin-bottom: 20px; /* Space below the heading */
        }

        .mb-3 {
            margin-bottom: 1.5rem; /* More space for questions */
        }

        .form-group {
            border: 1px solid #dee2e6; /* Border for question blocks */
            border-radius: 5px; /* Rounded corners for question blocks */
            padding: 10px; /* Padding inside question blocks */
            background-color: #f9f9f9; /* Light gray background for questions */
        }

        input[type="radio"] {
            margin-right: 10px; /* Space between radio and label */
        }

        .btn-primary {
            background-color: #007bff; /* Bootstrap primary color */
            border: none; /* No border */
            transition: background-color 0.3s; /* Smooth transition for hover */
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
    </style>
</head>
<body>
    
<div class="container mt-4">
    <h1>Exam</h1>
    <form method="POST" action="">
        <?php while ($question = $questions->fetch_assoc()): ?>
            <div class="form-group mb-3">
                <p><?php echo htmlspecialchars($question['question_text']); ?></p>
                <?php
                $answers_sql = "SELECT * FROM answers WHERE question_id = ?";
                $answers_stmt = $conn->prepare($answers_sql);
                $answers_stmt->bind_param("i", $question['question_id']);
                $answers_stmt->execute();
                $answers = $answers_stmt->get_result();
                while ($answer = $answers->fetch_assoc()): ?>
                    <div>
                        <input type="radio" name="question_<?php echo $question['question_id']; ?>" value="<?php echo $answer['answer_id']; ?>" required>
                        <?php echo htmlspecialchars($answer['answer_text']); ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endwhile; ?>
        <button type="submit" class="btn btn-primary">Submit Exam</button>
    </form>
</div>
<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
