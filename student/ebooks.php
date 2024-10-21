<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-Books | SonicScholar</title>
    <!-- Include your CSS files here -->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/ebooks.css"> <!-- Add your custom CSS for ebooks page styling -->
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
                        <a class="btn btn-info btn-block mb-2" href="ebooks.php">
                            <i class="fa fa-book"></i> e-Books
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
                    <h1 class="h2">e-Books</h1>
                </div>

                <div class="row">
                    <!-- e-Books List -->
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4>Available e-Books</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <a href="KeyboardBook1.pdf" target="_blank" class="btn btn-outline-primary btn-block">
                                            Keyboard Book 1
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="KeyboardBook2.pdf" target="_blank" class="btn btn-outline-primary btn-block">
                                            Keyboard Book 2
                                        </a>
                                    </li>
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
