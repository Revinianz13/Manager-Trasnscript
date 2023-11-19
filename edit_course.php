<?php
session_start(); 
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    
    header('Location: login.php');
    exit();
}

include 'db_connection.php';


$conn = OpenCon();

function getCourse($conn, $courseID)
{
    $query = "SELECT * FROM Courses WHERE CourseID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $courseID);
    $stmt->execute();
    $result = $stmt->get_result();

    $course = $result->fetch_assoc();

    $stmt->close();

    return $course;
}

function updateCourse($conn, $courseID, $courseName, $courseCredits)
{
    $query = "UPDATE Courses SET CourseName = ?, CourseCredits = ? WHERE CourseID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sii', $courseName, $courseCredits, $courseID);
    $stmt->execute();
    $stmt->close();
}

function isDuplicateCourseName($conn, $courseID, $courseName)
{
    $query = "SELECT CourseID FROM Courses WHERE CourseName = ? AND CourseID != ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $courseName, $courseID);
    $stmt->execute();
    $result = $stmt->get_result();
    $existingCourse = $result->fetch_assoc();
    $stmt->close();

    return !empty($existingCourse);
}

$courseID = isset($_GET['id']) ? $_GET['id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $courseName = $_POST['course_name'];
    $courseCredits = $_POST['course_credits'];

    if (isDuplicateCourseName($conn, $courseID, $courseName)) {
        $errors[] = "Course with the same name already exists";
    }

    if (empty($errors)) {

        updateCourse($conn, $courseID, $courseName, $courseCredits);

        header('Location: courses.php');
        exit();
    }
}


$course = getCourse($conn, $courseID);

CloseCon($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./main.css">
</head>
<body>
    <?php include './nav.php'?>
    

    <div class="container mt-4 cont position-absolute top-50 start-50 translate-middle border border border-light" style="margin-top: -160px !important; color: white; width: 50%;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Edit Course</h2>
            <a href="courses.php" class="btn btn-secondary btn-sm">Back to Courses</a>
        </div>

        <?php
        if (!empty($errors)) {
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
                <input type="text" class="form-control" id="course_name" name="course_name" value="<?php echo $course['CourseName']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="course_credits" class="form-label" style="color: white;">Course Credits</label>
                <input type="number" class="form-control" id="course_credits" name="course_credits" value="<?php echo $course['CourseCredits']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" id="submitBtn">Save Changes</button>
        </form>
    </div>
</body>

</html>
