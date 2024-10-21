<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];

    if ($role == 'student') {
        header("Location: dashboard.php");
    } elseif ($role == 'teacher') {
        header("Location: teacher_dashboard.php");
    } else {
        echo "Invalid role selected.";
    }
    exit();
} else {
    echo "Invalid request.";
}
?>
