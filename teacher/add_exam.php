<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'teacher') {
    header("Location: index.php"); // Redirect to login if not logged in as teacher
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "sonicscholar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve course_id from URL
if (!isset($_GET['course_id'])) {
    echo "Course ID is missing.";
    exit();
}

$course_id = intval($_GET['course_id']); // Make sure to sanitize the course_id

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exam_title = $_POST['exam_title'];

    // Insert the exam into the 'exams' table
    $stmt = $conn->prepare("INSERT INTO exams (course_id, exam_title, created_by) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $course_id, $exam_title, $_SESSION['user_id']);
    $stmt->execute();
    $exam_id = $stmt->insert_id;

    // Insert questions and answers dynamically
    $questions = $_POST['questions'];
    foreach ($questions as $question) {
        $question_text = $question['text'];
        
        // Insert the question into the 'questions' table
        $stmt = $conn->prepare("INSERT INTO questions (exam_id, question_text) VALUES (?, ?)");
        $stmt->bind_param("is", $exam_id, $question_text);
        $stmt->execute();
        $question_id = $stmt->insert_id;

        // Insert answers for each question
        foreach ($question['answers'] as $answer) {
            $answer_text = $answer['text'];
            $is_correct = isset($answer['is_correct']) ? 1 : 0;

            // Insert the answer into the 'answers' table
            $stmt = $conn->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $question_id, $answer_text, $is_correct);
            $stmt->execute();
        }
    }

    echo "Exam added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Exam</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }

        .container {
            margin-top: 50px;
            max-width: 800px; /* Increased width for better layout */
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Increased shadow for better depth */
            transition: transform 0.3s ease; /* Added smooth hover effect */
        }

        .container:hover {
            transform: scale(1.02); /* Slight scale on hover */
        }

        h1 {
            color: #343a40;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            font-size: 2em; /* Larger font size */
        }

        h3, h4 {
            color: #495057;
            margin-bottom: 10px;
        }

        .form-group label {
            font-weight: bold;
            color: #495057;
            font-size: 1.1em; /* Larger labels */
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 10px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease; /* Smooth focus effect */
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.5); /* Green glow on focus */
        }

        .btn {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth hover effect */
        }

        .btn-primary {
            background-color: #28a745;
            border: none;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth hover effect */
        }

        .btn-primary:hover {
            background-color: #218838;
            transform: translateY(-2px); /* Slight lift on hover */
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px); /* Slight lift on hover */
        }

        .btn-success {
            background-color: #17a2b8;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth hover effect */
        }

        .btn-success:hover {
            background-color: #138496;
            transform: translateY(-2px); /* Slight lift on hover */
        }

        .question-block {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .question-block:hover {
            transform: scale(1.01); /* Slight scale on hover */
        }

        .answers-container h5 {
            color: #6c757d;
            margin-bottom: 10px;
        }

        .form-check-input {
            margin-right: 10px;
        }

        .form-check-label {
            font-weight: 500;
            color: #495057;
        }

        h4 {
            margin-top: 0;
            color: #495057;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 1.5em;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <a href="teacher_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    <h1>Add Exam for Course ID: <?php echo $course_id; ?></h1>

    <form method="POST" action="">
        <div class="form-group">
            <label for="exam_title">Exam Title</label>
            <input type="text" class="form-control" name="exam_title" required>
        </div>

        <div id="questions-container">
            <h3>Questions</h3>
            <!-- Dynamic questions will be appended here -->
        </div>
        <button type="button" class="btn btn-success mb-3" id="add-question-btn">Add Question</button>

        <button type="submit" class="btn btn-primary">Add Exam</button>
    </form>
</div>

<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

<script>
    let questionCount = 0;

    document.getElementById('add-question-btn').addEventListener('click', function() {
        questionCount++;
        const questionHTML = `
            <div class="question-block mb-4" id="question-${questionCount}">
                <h4>Question ${questionCount}</h4>
                <div class="form-group">
                    <label for="questions[${questionCount}][text]">Question Text</label>
                    <input type="text" class="form-control" name="questions[${questionCount}][text]" required>
                </div>
                <div class="answers-container">
                    <h5>Answers</h5>
                    <div class="form-group">
                        <input type="text" class="form-control" name="questions[${questionCount}][answers][0][text]" placeholder="Answer 1" required>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="questions[${questionCount}][answers][0][is_correct]" value="1">
                            <label class="form-check-label">Mark as Correct</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="questions[${questionCount}][answers][1][text]" placeholder="Answer 2" required>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="questions[${questionCount}][answers][1][is_correct]" value="1">
                            <label class="form-check-label">Mark as Correct</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="questions[${questionCount}][answers][2][text]" placeholder="Answer 3" required>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="questions[${questionCount}][answers][2][is_correct]" value="1">
                            <label class="form-check-label">Mark as Correct</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="questions[${questionCount}][answers][3][text]" placeholder="Answer 4" required>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="questions[${questionCount}][answers][3][is_correct]" value="1">
                            <label class="form-check-label">Mark as Correct</label>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('questions-container').insertAdjacentHTML('beforeend', questionHTML);
    });
</script>
</body>
</html>
