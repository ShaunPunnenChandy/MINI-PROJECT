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

// Fetch the teacher_id based on the session email
$email = $_SESSION['email'];
$sql = "SELECT teacher_id FROM teacher WHERE email_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$teacher_id_result = $stmt->get_result();

if ($teacher_id_result->num_rows > 0) {
    $teacher_row = $teacher_id_result->fetch_assoc();
    $teacher_id = $teacher_row['teacher_id'];
} else {
    echo "Teacher not found.";
    exit();
}

// Fetch all exams that the teacher has created
$exam_sql = "SELECT exam_id, exam_title FROM exams WHERE created_by = ?";
$exam_stmt = $conn->prepare($exam_sql);
$exam_stmt->bind_param("i", $teacher_id);
$exam_stmt->execute();
$exams = $exam_stmt->get_result();

$selected_exam_id = isset($_GET['exam_id']) ? $_GET['exam_id'] : null;
$results = [];

if ($selected_exam_id) {
    // Fetch student results for the selected exam
    $results_sql = "
        SELECT s.registration_id, s.first_name, s.last_name, er.score 
        FROM exam_results er 
        JOIN student s ON er.student_id = s.registration_id 
        WHERE er.exam_id = ?";
    
    $result_stmt = $conn->prepare($results_sql);
    $result_stmt->bind_param("i", $selected_exam_id);
    $result_stmt->execute();
    $results = $result_stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student Results</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f4f8; /* Soft background color */
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin-top: 40px;
            max-width: 800px;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
        }

        h1 {
            color: #007bff;
            font-size: 2.5em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
            transition: transform 0.3s;
        }

        h1:hover {
            transform: scale(1.05); /* Scale effect on hover */
        }

        .form-group label {
            font-weight: 600;
            color: #495057;
            font-size: 1.1em;
        }

        select.form-control {
            font-size: 1em;
            padding: 10px;
            border-radius: 5px;
            border: 2px solid #007bff;
            transition: border-color 0.3s ease-in-out;
        }

        select.form-control:focus {
            border-color: #0056b3;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }


        .btn-back {
        background-color: #6c757d; /* Grey background */
        color: white; /* White text */
        padding: 10px 20px;
        font-weight: bold;
        font-size: 16px;
        border-radius: 5px;
        transition: all 0.3s ease-in-out;
        text-transform: uppercase;
        display: block; /* Make it a block-level element */
        margin: 20px auto; /* Center the button */
        /* Remove default border */
        cursor: pointer; /* Show pointer cursor */
    }

    .btn-back:hover {
        background-color: #5a6268; /* Darker grey on hover */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Add shadow on hover */
        transform: translateY(-2px); /* Lift effect on hover */
    }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 10px 25px;
            font-weight: bold;
            font-size: 16px;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
            text-transform: uppercase;
            display: block;
            margin: 20px auto; /* Center the button */
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            box-shadow: 0 8px 16px rgba(0, 91, 187, 0.3);
            transform: translateY(-2px);
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #dee2e6;
            padding: 15px; /* Increased padding for better readability */
            text-align: center;
            font-size: 16px;
            transition: background-color 0.2s;
        }

        table th {
            background-color: #007bff;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
        }

        table tbody tr td {
            color: #495057;
        }

        .no-results {
            text-align: center;
            font-style: italic;
            color: #6c757d;
            margin-top: 20px;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin-top: 30px;
            }

            h1 {
                font-size: 2em;
            }

            table th, table td {
                font-size: 14px;
                padding: 10px;
            }

            .btn-primary {
                font-size: 14px;
                padding: 10px 20px;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 15px;
                margin-top: 20px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            h1 {
                font-size: 1.8em;
            }

            .btn-primary {
                font-size: 12px;
                padding: 8px 12px;
            }

            
        }
    </style>
</head>
<body>
    
<div class="container">
    <h1>View Student Results</h1>
    <a href="teacher_dashboard.php" class="btn btn-back">Back to Dashboard</a>
    
    <!-- Exam selection form -->
    <form method="GET" class="exam-select">
        <div class="form-group">
            <label for="exam_id">Select Exam:</label>
            <select name="exam_id" id="exam_id" class="form-control" required>
                <option value="">-- Select an Exam --</option>
                <?php while ($exam = $exams->fetch_assoc()): ?>
                    <option value="<?php echo $exam['exam_id']; ?>" <?php if ($selected_exam_id == $exam['exam_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($exam['exam_title']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="text-center">
            <button type="submit" class="btn btn-primary">View Results</button>
        </div>
    </form>

    <!-- Display student results if an exam is selected -->
    <?php if ($selected_exam_id && $results->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $results->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['registration_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['score']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif ($selected_exam_id): ?>
        <p class="no-results">No results found for the selected exam.</p>
    <?php endif; ?>
</div>

<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
