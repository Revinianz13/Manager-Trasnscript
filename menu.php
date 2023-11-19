<?php
session_start(); 


if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Management Transcript System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./main.css">
</head>
<body>
    <?php include './nav.php'?>

    <div class="cont position-absolute top-50 start-50 translate-middle border border border-light">
        <div><h2 class="text-light">Teachers Dashboard<h2></div>

        <div class="d-grid gap-2">
            <a href="./courses.php" class="btn btn-primary btn-lg mt-2">Courses</a> 
            <a href="./display_students.php" class="btn btn-primary btn-lg mt-2">Students</a> 
            <a href="./gpa_display.php" class="btn btn-primary btn-lg mt-2">GPA</a> 
        </div>
    </div>
</body>

</html>
