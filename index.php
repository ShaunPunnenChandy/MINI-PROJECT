<?php
// Initialize variables for error messages
$message = "";

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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['pass'];

    // Check if the user exists in teacher table
    $sql_teacher = "SELECT password FROM teacher WHERE email_id = '$email'";
    $result_teacher = $conn->query($sql_teacher);
    
    if ($result_teacher->num_rows > 0) {
        // Fetch the teacher data
        $row = $result_teacher->fetch_assoc();
        $D_password = $row['password'];
        
        // Verify the password
        if ($password == $D_password) {
            // Start session and redirect to the teacher dashboard
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['role'] = 'teacher';
            header("Location: teacher/teacher_dashboard.php");
            exit(); // Ensure no further code is executed
        } else {
            $message = "Invalid password for teacher.";
        }
    }

    // Check if the user exists in student table
    $sql_student = "SELECT password FROM student WHERE email_id = '$email'";
    $result_student = $conn->query($sql_student);
    
    if ($result_student->num_rows > 0) {
        // Fetch the student data
        $row = $result_student->fetch_assoc();
        $D_password = $row['password'];
        
        // Verify the password
        if ($password == $D_password) {
            // Start session and redirect to the student dashboard
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['role'] = 'student';
            header("Location: student/dashboard.php");
            exit(); // Ensure no further code is executed
        } else {
            $message = "Invalid password for student.";
        }
    }

    // If no match found in any table
    if (empty($message)) {
        $message = "No user found with this email.";
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>SonicScholar | Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="images/icons/music-note.png"/>
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form validate-form" method="post" action="">
                    <span class="login100-form-title p-b-26">
                        SonicScholar
                    </span>

                    <?php if ($message): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>

                    <div class="wrap-input100 validate-input" data-validate="Enter email">
                        <input class="input100" type="text" name="email" required>
                        <span class="focus-input100" data-placeholder="Email"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <span class="btn-show-pass">
                            <i class="zmdi zmdi-eye"></i>
                        </span>
                        <input class="input100" type="password" name="pass" required>
                        <span class="focus-input100" data-placeholder="Password"></span>
                    </div>

                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn">
                                Login
                            </button>
                        </div>
                    </div>

                    <div class="text-center p-t-115">
                        <span class="txt1">
                            Don’t have an account?
                        </span>

                        <a class="txt2" href="student_registration.php">
                            Sign Up
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="dropDownSelect1"></div>

    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="vendor/animsition/js/animsition.min.js"></script>
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/daterangepicker/moment.min.js"></script>
    <script src="vendor/daterangepicker/daterangepicker.js"></script>
    <script src="vendor/countdowntime/countdowntime.js"></script>
    <script src="js/main.js"></script>

</body>
</html>
