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
    $admin_id = $conn->real_escape_string($_POST['admin_id']);
    $password = $_POST['pass'];

    // Prepare SQL statement to fetch admin data
    $sql = "SELECT password FROM admin WHERE admin_id = '$admin_id'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Fetch the admin data
        $row = $result->fetch_assoc();
        $D_password = $row['password'];
        
        // Verify the password
        if ($password == $D_password) {
            // Start session and redirect to the admin dashboard
            session_start();
            $_SESSION['admin_id'] = $admin_id;
            header("Location: admin_dashboard.php"); // Redirect to the admin dashboard
            exit(); // Ensure no further code is executed
        } else {
            $message = "Invalid password for Admin ID.";
        }
    } else {
        $message = "No admin found with this Admin ID.";
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login | SonicScholar</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/PROJECT/images/icons/music-note.png"/>
    <link rel="stylesheet" type="text/css" href="/PROJECT/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/PROJECT/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/PROJECT/fonts/iconic/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" type="text/css" href="/PROJECT/vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="/PROJECT/vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="/PROJECT/vendor/animsition/css/animsition.min.css">
    <link rel="stylesheet" type="text/css" href="/PROJECT/vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="/PROJECT/vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="/PROJECT/css/util.css">
    <link rel="stylesheet" type="text/css" href="/PROJECT/css/main.css">
</head>
<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form validate-form" method="post" action="">
                    <span class="login100-form-title p-b-26">
                        Admin Login
                    </span>

                    <?php if ($message): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>

                    <div class="wrap-input100 validate-input" data-validate="Enter Admin ID">
                        <input class="input100" type="text" name="admin_id" required>
                        <span class="focus-input100" data-placeholder="Admin ID"></span>
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
                </form>
            </div>
        </div>
    </div>

    <div id="dropDownSelect1"></div>

    <script src="/PROJECT/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="/PROJECT/vendor/animsition/js/animsition.min.js"></script>
    <script src="/PROJECT/vendor/bootstrap/js/popper.js"></script>
    <script src="/PROJECT/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="/PROJECT/vendor/select2/select2.min.js"></script>
    <script src="/PROJECT/vendor/daterangepicker/moment.min.js"></script>
    <script src="/PROJECT/vendor/daterangepicker/daterangepicker.js"></script>
    <script src="/PROJECT/vendor/countdowntime/countdowntime.js"></script>
    <script src="/PROJECT/js/main.js"></script>

</body>
</html>
