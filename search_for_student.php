<?php
session_start(); 


if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    
    header('Location: login.php');
    exit();
}


include 'db_connection.php';


$conn = OpenCon();


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$studentsQuery = "SELECT * FROM Students";
$studentsResult = $conn->query($studentsQuery);
$allStudents = $studentsResult->fetch_all(MYSQLI_ASSOC);


CloseCon($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search for Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./main.css">
</head>

<body>
    <?php include 'nav.php' ?>

    <div class="container mt-4 cont position-absolute start-50 translate-middle border border border-light" style="margin-top: 300px !important;">
        <h2 class="mb-4" style="color: white;">Search for Student</h2>
        <form method="post" action="display_student_gpa.php">
            <div class="mb-3">
                <label for="studentDropdown" class="form-label">Select Student:</label>
                <select class="form-select" id="studentDropdown" name="student_id" required>
                    <option value="" selected disabled>Select a student</option>
                    <?php foreach ($allStudents as $student): ?>
                        <option value="<?php echo $student['StudentID']; ?>">
                            <?php echo $student['FirstName'] . ' ' . $student['LastName']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</body>

</html>
