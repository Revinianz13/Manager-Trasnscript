<?php


session_start(); 
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    
    header('Location: login.php');
    exit();
}

include 'db_connection.php';


function getCourses($conn, $teacherID)
{
    $query = "SELECT * FROM Courses WHERE TeacherID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $teacherID);
    $stmt->execute();

    $result = $stmt->get_result();

    $courses = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }

    $stmt->close();

    return $courses;
}


function getTeacherID()
{
        return $_SESSION['teacher_id'] ?? null;
}


$conn = OpenCon();


$teacherID = getTeacherID();
$courses = getCourses($conn, $teacherID);

$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $courseName = $_POST['course_name'];
    $courseCredits = $_POST['course_credits'];
    

    $teacherID = getTeacherID();
    var_dump($courseName, $courseCredits, $teacherID);
    if ($teacherID === null) {

        $errors[] = "Teacher not authenticated. Please log in.";
    } else {

        foreach ($courses as $course) {
            if ($course['CourseName'] === $courseName) {
                array_push($errors, "Course with the same name already exists");
                break;
            }
        }

        if (count($errors) == 0) {
            $query = "INSERT INTO Courses (CourseName, CourseCredits, TeacherID) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sid', $courseName, $courseCredits, $teacherID);

            if ($stmt->execute()) {

                header('Location: courses.php');
                exit();
            } else {

                $errors[] = "Error adding course";
            }

            $stmt->close();
        }
    }
}

CloseCon($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./main.css">
</head>

<body>
    <?php include 'nav.php' ?>

    <div class="container mt-4 cont position-absolute top-50 start-50 translate-middle border border border-light" style="margin-top: -160px !important; color: white; width: 50%;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Add Course</h2>
            <a href="courses.php" class="btn btn-secondary btn-sm">Back to Courses</a>
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
                <label for="course_name" class="form-label" style="color: white;">Course Name</label>
                <input type="text" class="form-control" id="course_name" name="course_name" required>
            </div>
            <div class="mb-3">
                <label for="course_credits" class="form-label" style="color: white;">Course Credits</label>
                <input type="number" class="form-control" id="course_credits" name="course_credits" required>
            </div>
            <button type="submit" class="btn btn-primary" id="submitBtn">Add Course</button>
        </form>

        <script>

            document.getElementById('submitBtn').addEventListener('click', function (e) {
                var courseName = document.getElementById('course_name').value;

                <?php
                $courseNames = array_map(function ($course) {
                    return "'" . $course['CourseName'] . "'";
                }, $courses);
                ?>
                var existingCourseNames = [<?php echo implode(', ', $courseNames); ?>];

                if (existingCourseNames.includes(courseName)) {

                    document.getElementById('error_messages').innerHTML = '<div class="alert alert-danger">Course with the name "' + courseName + '" already exists</div>';
                    e.preventDefault(); 
                }
            });
        </script>

    </div>
</body>

</html>
