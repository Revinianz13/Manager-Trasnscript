<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); 

include 'db_connection.php';


if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    
    header('Location: login.php');
    exit();
}

$studentId = $_GET['id']; 
$conn = OpenCon();

$queryStudent = "SELECT * FROM Students WHERE StudentID = ?";
$stmtStudent = $conn->prepare($queryStudent);
$stmtStudent->bind_param("i", $studentId);
$stmtStudent->execute();
$resultStudent = $stmtStudent->get_result();
$student = $resultStudent->fetch_assoc();
$stmtStudent->close();


$teacherId = $_SESSION['teacher_id'];
$queryCourses = "SELECT CourseID, CourseName FROM Courses WHERE TeacherID = ?";
$stmtCourses = $conn->prepare($queryCourses);
$stmtCourses->bind_param("i", $teacherId);
$stmtCourses->execute();
$resultCourses = $stmtCourses->get_result();
$courses = $resultCourses->fetch_all(MYSQLI_ASSOC);
$stmtCourses->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $courseId = $_POST['course'];
    $score = $_POST['score'];

   
    $queryCheckGraded = "SELECT * FROM StudentCourses WHERE StudentID = ? AND CourseID = ?";
    $stmtCheckGraded = $conn->prepare($queryCheckGraded);
    $stmtCheckGraded->bind_param("ii", $studentId, $courseId);
    $stmtCheckGraded->execute();
    $resultCheckGraded = $stmtCheckGraded->get_result();
    $isCourseGraded = $resultCheckGraded->num_rows > 0; 
    $stmtCheckGraded->close();

    $queryCheckGradedScores = "SELECT * FROM Scores WHERE StudentID = ? AND CourseID = ?";
    $stmtCheckGradedScores = $conn->prepare($queryCheckGradedScores);
    $stmtCheckGradedScores->bind_param("ii", $studentId, $courseId);
    $stmtCheckGradedScores->execute();
    $resultCheckGradedScores = $stmtCheckGradedScores->get_result();
    $isCourseGradedScores = $resultCheckGradedScores->num_rows > 0; 
    $stmtCheckGradedScores->close();


if ($isCourseGraded) {
    $queryUpdate = "UPDATE StudentCourses SET Score = ? WHERE StudentID = ? AND CourseID = ?";
    $stmtUpdate = $conn->prepare($queryUpdate);
    $stmtUpdate->bind_param("iii", $score, $studentId, $courseId);
    $stmtUpdate->execute();
    $isInsertSuccess = $stmtUpdate->execute(); 
    $stmtUpdate->close();
} else {
    $queryInsert = "INSERT INTO StudentCourses ( StudentID,TeacherID,CourseID,Score) VALUES (?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($queryInsert);
    $stmtInsert->bind_param("iiii", $studentId,$teacherId, $courseId, $score);
    $isInsertSuccess = $stmtInsert->execute(); 
    $stmtInsert->close();
}

if ($isCourseGradedScores) {
    $queryUpdateScores = "UPDATE Scores SET Score = ? WHERE StudentID = ? AND CourseID = ?";
    $stmtUpdateScores = $conn->prepare($queryUpdateScores);
    $stmtUpdateScores->bind_param("iii",$studentId,$courseId,$score);
    $stmtUpdateScores->execute();
    $isInsertScoresSuccess = $stmtUpdateScores->execute(); 
    $stmtUpdateScores->close();
} else {
    $queryInsertScores = "INSERT INTO Scores (StudentID, CourseID, Score) VALUES (?, ?, ?)";
    $stmtInsertScores = $conn->prepare($queryInsertScores);
    $stmtInsertScores->bind_param("iii", $studentId, $courseId, $score);
    $isInsertScoresSuccess = $stmtInsertScores->execute(); 
    $stmtInsertScores->close();
}


    if ($isInsertSuccess || $isInsertScoresSuccess) {
       
        $_SESSION['add_course_status'] = 'success'; 
        $_SESSION['add_course_message'] = 'Score and Course assigned successfully'; 
    }
    
        header('Location: display_students.php');
        exit();
    
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course for <?php echo $student['FirstName'] . ' ' . $student['LastName']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./main.css">
</head>

<body>
    <?php include './nav.php' ?>

    <div class="container mt-4 cont position-absolute top-50 start-50 translate-middle border border border-light">
        <h2 class="mb-4" style="color: white;">Add Course for <?php echo $student['FirstName'] . ' ' . $student['LastName']; ?></h2>

        <form method="POST">
            <div class="mb-3">
                <label for="course" class="form-label" style="color: white;">Select Course:</label>
                <select class="form-select" id="course" name="course" required>
                    <option value="" disabled selected>Select a course</option>
                    <?php foreach ($courses as $course) : ?>
                        <option value="<?php echo $course['CourseID']; ?>"><?php echo $course['CourseName']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="score" class="form-label" style="color: white;">Enter Score (0-100):</label>
                <input type="number" class="form-control" id="score" name="score" min="0" max="100" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Course</button>
        </form>

    </div>
    
</body>

</html>

<?php
CloseCon($conn);
?>
