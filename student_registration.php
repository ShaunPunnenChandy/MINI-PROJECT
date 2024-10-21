<?php
// Start session
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sonicscholar";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$first_name = $last_name = $birthday = $gender = $email_id = $phone = $password = "";
$courses = []; // To store courses

// Fetch courses for dropdown
$sql_courses = "SELECT id, subject_name FROM courses";
$result_courses = $conn->query($sql_courses);
if ($result_courses->num_rows > 0) {
    while ($row = $result_courses->fetch_assoc()) {
        $courses[] = $row;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birthday = $_POST['birthday'];
    $gender = $_POST['gender'];
    $email_id = $_POST['email_id'];
    $phone = $_POST['phone'];
    $course_id = $_POST['course_id']; // Course ID from the dropdown
    $password = $_POST['password'];

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email_id) || empty($password) || empty($course_id)) {
        $error_message = "Please fill in all required fields.";
    } else {
        // Hash the password
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO student (first_name, last_name, birthday, gender, email_id, phone, password, course_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssiss", $first_name, $last_name, $birthday, $gender, $email_id, $phone, $password_hashed, $course_id);

        if ($stmt->execute()) {
            // Redirect to a success page or show success message
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icons/music-note.png"/>
    <link rel="stylesheet" href="/PROJECT/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/PROJECT/css/main.css">
    <style>
        /* Custom CSS to style the form */
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Arial', sans-serif;
            font-weight: 700;
            color: #343a40;
        }
        .form-label {
            font-weight: bold;
            color: #495057;
        }
        .btn-primary {
            width: 100%;
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-secondary {
            width: 100%;
            margin-top: 15px;
        }
        .alert {
            font-size: 14px;
            text-align: center;
        }
        .mb-3 input, .mb-3 select {
            font-size: 14px;
        }
        .form-select {
            padding: 8px;
        }
        /* Additional padding for mobile screens */
        @media (max-width: 576px) {
            .container {
                padding: 15px;
            }
            h1 {
                font-size: 24px;
            }
        }
    </style>
    <title>Student Registration | SonicScholar</title>
    <script>
        function updateCourseId() {
            const courseDropdown = document.getElementById('course');
            const courseIdInput = document.getElementById('course_id');
            courseIdInput.value = courseDropdown.value; // Set the course ID to the selected value
        }
    </script>
</head>
<body>

<div class="container">
    <h1>Student Registration</h1>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" name="first_name" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" name="last_name" required>
        </div>

        <div class="mb-3">
            <label for="birthday" class="form-label">Birthday</label>
            <input type="date" class="form-control" name="birthday">
        </div>

        <div class="mb-3">
            <label for="gender" class="form-label">Gender</label>
            <select class="form-select" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="email_id" class="form-label">Email ID</label>
            <input type="email" class="form-control" name="email_id" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone">
        </div>

        <div class="mb-3">
            <label for="course" class="form-label">Select Course</label>
            <select class="form-select" id="course" name="course" onchange="updateCourseId()" required>
                <option value="">Select a course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo htmlspecialchars($course['id']); ?>">
                        <?php echo htmlspecialchars($course['subject_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <input type="hidden" id="course_id" name="course_id" value="">

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>

    <a href="index.php" class="btn btn-secondary">Back to Login</a>
</div>

<script src="/PROJECT/vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="/PROJECT/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
