<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// User is logged in, you can proceed with displaying tutorial content
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorials | SonicScholar</title>
    <!-- Include your CSS files here -->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css"> <!-- Add your custom CSS for dashboard styling -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .card-header {
            background-color: #343a40;
            color: #ffffff;
            border-bottom: 1px solid #6c757d;
        }
        .card-body {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 0.375rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        .card {
            border: none;
            border-radius: 0.375rem;
        }
        .card-title {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }
        .card-text {
            font-size: 1rem;
            color: #6c757d;
        }
        .btn-block {
            border-radius: 0.375rem;
        }
        iframe {
            border-radius: 0.375rem;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-outline-primary {
            color: #007bff;
            border-color: #007bff;
        }
        .btn-outline-primary:hover {
            background-color: #007bff;
            color: #ffffff;
        }
        .d-grid {
            gap: 0.75rem;
        }
        .list-group-item {
            border: none;
            border-radius: 0.375rem;
            background-color: #ffffff;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            padding: 0.75rem 1.25rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        .list-group-item:last-child {
            margin-bottom: 0;
        }
        .progress-bar {
            background-color: #28a745;
        }
    </style>
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
                        <a class="btn btn-secondary btn-block mb-2" href="todo_list.php">
                            <i class="fa fa-list"></i> To-Do List
                        </a>
                        <a class="btn btn-warning btn-block mb-2" href="virtualkeyboard/index.php">
                            <i class="fa fa-music"></i> Musical Keyboard
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
                    <h1 class="h2">Tutorials</h1>
                </div>

                <div class="row">
                    <!-- Module 1 -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4>Module 1: Introduction to Music Theory</h4>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Learn the basics of music theory with this introductory module.</p>
                                <iframe width="100%" height="315" src="https://www.youtube.com/embed/n2z02J4fJwg" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>

                    <!-- Module 2 -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4>Module 2: Advanced Musical Keyboard</h4>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Dive deeper into advanced techniques and concepts of playing the musical keyboard.</p>
                                <iframe width="100%" height="315" src="https://www.youtube.com/embed/__VtlxQZhXs" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>

                    <!-- Module 3 -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4>Module 3: Digital Audio Workstation Basics</h4>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Get acquainted with the basics of using a Digital Audio Workstation (DAW).</p>
                                <iframe width="100%" height="315" src="https://www.youtube.com/embed/VJfMhuQYshA" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>

                    <!-- Module 4 -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4>Module 4: Digital Audio Workstation Basics</h4>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Get acquainted with the basics of using a Digital Audio Workstation (DAW).</p>
                                <iframe width="100%" height="315" src="https://www.youtube.com/embed/VJfMhuQYshA" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                    <!-- Add more modules as needed -->
                </div>
            </main>
        </div>
    </div>

    <!-- Include your JS files here -->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
