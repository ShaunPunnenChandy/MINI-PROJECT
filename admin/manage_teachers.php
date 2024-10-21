<?php
// Start session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit();
}

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

// Initialize variables for messages
$message = "";

// Add Teacher
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_teacher'])) {
    $teacher_name = $conn->real_escape_string($_POST['teacher_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $theory_level = (int)$_POST['theory_level'];
    $practical_level = (int)$_POST['practical_level'];

    $sql = "INSERT INTO teacher (name, email_id, password, theory_level, practical_level) VALUES ('$teacher_name', '$email', '$password', '$theory_level', '$practical_level')";

    if ($conn->query($sql) === TRUE) {
        $message = "New teacher added successfully.";
    } else {
        $message = "Error adding teacher: " . $conn->error;
    }
}

// Delete Teacher
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $sql = "DELETE FROM teacher WHERE teacher_id = '$delete_id'";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Teacher deleted successfully.";
    } else {
        $message = "Error deleting teacher: " . $conn->error;
    }
}

// Fetch Teachers
$sql = "SELECT * FROM teacher";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icons/music-note.png"/>
    <link rel="stylesheet" href="/PROJECT/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/PROJECT/css/main.css">
    <title>Manage Teachers | SonicScholar</title>
</head>
<body>

<div class="container">
    <!-- Back to Dashboard Button -->
    <div class="mt-4 mb-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <h1 class="mt-4">Manage Teachers</h1>

    <?php if ($message): ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="card mt-4">
        <div class="card-header">Add Teacher</div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="teacher_name">Name</label>
                    <input type="text" class="form-control" name="teacher_name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="form-group">
                    <label for="theory_level">Theory Level (1-5)</label>
                    <input type="number" class="form-control" name="theory_level" min="1" max="5" required>
                </div>
                <div class="form-group">
                    <label for="practical_level">Practical Level (1-5)</label>
                    <input type="number" class="form-control" name="practical_level" min="1" max="5" required>
                </div>
                <button type="submit" class="btn btn-primary" name="add_teacher">Add Teacher</button>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Existing Teachers</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Theory Level</th>
                        <th>Practical Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['teacher_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email_id']); ?></td>
                                <td><?php echo $row['theory_level']; ?></td>
                                <td><?php echo $row['practical_level']; ?></td>
                                <td>
                                    <a href="?delete_id=<?php echo $row['teacher_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this teacher?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No teachers found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="/PROJECT/vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="/PROJECT/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
