<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// User is logged in, you can proceed with displaying dashboard content
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SonicScholar</title>
    <!-- Include your CSS files here -->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css"> <!-- Add your custom CSS for dashboard styling -->
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light">
                <div class="position-sticky">
                    <h4 class="text-center my-4">SonicScholar</h4>
                    <div class="d-grid gap-2">
                        <a class="btn btn-primary btn-block mb-2" href="tutorials.php">
                            <i class="fa fa-book"></i> Course Materials
                        </a>
                        <a class="btn btn-warning btn-block mb-2" href="virtualkeyboard/index.php">
                            <i class="fa fa-music"></i> Musical Keyboard
                        </a>
                        <a class="btn btn-warning btn-block mb-2" href="profile.php">
                            <i class="fa fa-music"></i> Profile
                        </a>
                        <a class="btn btn-info btn-block mb-2" href="ebooks.php">
                            <i class="fa fa-book"></i> e-Books
                        </a>
                        <a class="btn btn-success btn-block mb-2" href="feedback.php">
                            <i class="fa fa-comments"></i> Feedback
                        </a>
                        <a class="btn btn-primary btn-block mb-2" href="song_search.php">
                            <i class="fa fa-search"></i> Song Search
                        </a>
                        <a class="btn btn-danger btn-block mb-2" href="logout.php">
                            <i class="fa fa-sign-out"></i> Logout
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                    </div>
                </div>

                <div class="row">
                    <!-- Welcome Card -->
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h3 class="card-title">Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h3>
                                <p class="card-text">Explore your courses, manage your schedule, and update your settings here.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Activities -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4>Recent Activities</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item">Completed "Introduction to Music Theory" quiz</li>
                                    <li class="list-group-item">Uploaded assignment for "Advanced Musical Keyboard"</li>
                                    <li class="list-group-item">Viewed lecture notes for "Digital Audio Workstation Basics"</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Deadlines -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4>Upcoming Deadlines</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item">Assignment due for "Digital Audio Workstation Basics" - Sep 10, 2024</li>
                                    <li class="list-group-item">Final project submission for "Advanced Musical Keyboard" - Sep 15, 2024</li>
                                    <li class="list-group-item">Quiz for "Introduction to Music Theory" - Sep 12, 2024</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Course Progress -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4>Course Progress</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        Introduction to Music Theory 
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        Advanced Musical Keyboard 
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%</div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        Digital Audio Workstation Basics 
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 90%;" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">90%</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Announcements -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4>Announcements</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item">New module released for "Digital Audio Workstation Basics"</li>
                                    <li class="list-group-item">Office hours updated for "Advanced Musical Keyboard"</li>
                                    <li class="list-group-item">Webinar on music theory scheduled for Sep 20, 2024</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <!-- Include your JS files here -->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>