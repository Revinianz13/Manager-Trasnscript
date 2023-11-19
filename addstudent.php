<?php
session_start(); 

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    
    header('Location: login.php');
    exit();
}


include 'db_connection.php';


function getStudentsForTeacher($conn, $teacherID)
{
    $query = "SELECT * FROM Students WHERE TeacherID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $teacherID);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }

    return $students;
}


$conn = OpenCon();

$teacherID = $_SESSION['teacher_id'];


$students = getStudentsForTeacher($conn, $teacherID);
$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $dateOfBirth = $_POST['date_of_birth'];

        foreach ($students as $student) {
            if (
                $student['FirstName'] === $firstName &&
                $student['LastName'] === $lastName &&
                $student['DateOfBirth'] === $dateOfBirth
            ) {
                $hasErrors = true;
                array_push($errors, "Student with the same name and date of birth already exists (ID: {$student['StudentID']})");
                break;
            }
        }


    if (count($errors) == 0) {
        
        
        $query = "INSERT INTO Students (TeacherID, FirstName, LastName, DateOfBirth) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('isss', $teacherID, $firstName, $lastName, $dateOfBirth);


        if ($stmt->execute()) {
            $_SESSION['add_student_status'] = 'success';
            $_SESSION['add_student_message'] = 'Student added successfully';
            header('Location: display_students.php');
            exit();
        } else {
            $_SESSION['add_student_status'] = 'error';
            $_SESSION['add_student_message'] = 'Something went wrong. Please try again.';
            echo "Error adding student";
        }

        
        $stmt->close();
    }
}

CloseCon($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./main.css">
</head>

<body>
    <?php include 'nav.php' ?>

    <div class="container mt-4 cont position-absolute top-50 start-50 translate-middle border border border-light" style=" color: white; width: 50%;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Add Student</h2>
            <a href="display_students.php" class="btn btn-secondary btn-sm">Back to Students</a>
        </div>

        
        <?php
        if (count($errors) > 0) {
            echo '<div class="alert alert-danger">';
            foreach ($errors as $error) {
                echo '<p>' . $error . '</p>';
            }
            echo '</div>';
        }
        ?>


        <form method="post">
            <div class="mb-3">
                <label for="first_name" class="form-label" style="color: white;">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label" style="color: white;">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="mb-3">
                <label for="date_of_birth" class="form-label" style="color: white;">Date of Birth</label>
                
                <input type="date" class="form-control datepicker" id="date_of_birth" name="date_of_birth" required>
            </div>
            <button type="submit" class="btn btn-primary" id="submitBtn">Add Student</button>
        </form>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
</body>

</html>
