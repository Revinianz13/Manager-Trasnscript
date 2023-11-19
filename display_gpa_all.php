<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); 


if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    
    header('Location: login.php');
    exit();
}

include 'db_connection.php';

$conn = OpenCon();


include 'gpa_functions.php';


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


calculateAndUpdateAllGPAs($conn);


$gpaQuery = "SELECT StudentID, CourseID, GPA FROM StudentAverages";
$gpaResult = $conn->query($gpaQuery);
if (!$gpaResult) {
    die("Query failed: " . $conn->error);
}
$allGPA = array();

while ($row = $gpaResult->fetch_assoc()) {
    $allGPA[$row['StudentID']][$row['CourseID']] = $row['GPA'];
}


$studentsQuery = "SELECT * FROM Students";
$studentsResult = $conn->query($studentsQuery);
if (!$studentsResult) {
    die("Query failed: " . $conn->error);
}
$allStudents = $studentsResult->fetch_all(MYSQLI_ASSOC);


$coursesQuery = "SELECT * FROM Courses";
$coursesResult = $conn->query($coursesQuery);
if (!$coursesResult) {
    die("Query failed: " . $conn->error);
}
$allCourses = $coursesResult->fetch_all(MYSQLI_ASSOC);



$studentGPA = array();
$courseAverages = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $studentID = $_POST['student_id'];

    
    $studentQuery = "SELECT * FROM Students WHERE StudentID = '{$studentID}'";
    $studentResult = $conn->query($studentQuery);
    $student = $studentResult->fetch_assoc();

    if ($student) {
        
        foreach ($allCourses as $course) {
            
            $gpaQuery = "SELECT AVG(Score) AS GPA FROM StudentCourses WHERE StudentID = '{$student['StudentID']}' AND CourseID = '{$course['CourseID']}'";
            $gpaResult = $conn->query($gpaQuery);
            $gpaRow = $gpaResult->fetch_assoc();
            $gpa = $gpaRow['GPA'];

            
            $updateGpaQuery = "INSERT INTO StudentAverages (StudentID, CourseID, GPA) VALUES ('{$student['StudentID']}', '{$course['CourseID']}', $gpa) ON DUPLICATE KEY UPDATE GPA = $gpa";
            $conn->query($updateGpaQuery);
        }

        
        $gpaQuery = "SELECT CourseID, GPA FROM StudentAverages WHERE StudentID = '{$studentID}'";
        $gpaResult = $conn->query($gpaQuery);

        while ($row = $gpaResult->fetch_assoc()) {
            $studentGPA[$row['CourseID']] = $row['GPA'];
        }

        
        $avgGpaQuery = "SELECT CourseID, AVG_GPA FROM CourseAverages";
        $avgGpaResult = $conn->query($avgGpaQuery);

        while ($row = $avgGpaResult->fetch_assoc()) {
            $courseAverages[$row['CourseID']] = $row['AVG_GPA'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display GPAs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.js"></script>
    <link rel="stylesheet" href="./main.css">
</head>

<body>
    <?php include 'nav.php' ?>

    <div class="container mt-4 cont position-absolute start-50  translate-middle border border border-light" style="margin-top: 440px !important;">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 style="color: white;">Student GPA Statistics</h2>
            <button id="exportPdfButton" class="btn btn-success btn-sm">Export Data</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <?php
                    
                    foreach ($allCourses as $course) {
                        echo "<th>{$course['CourseName']} GPA</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                
                foreach ($allStudents as $student) {
                    echo "<tr>";
                    echo "<td>{$student['StudentID']}</td>";
                    echo "<td>{$student['FirstName']} {$student['LastName']}</td>";

                    
                    foreach ($allCourses as $course) {
                        echo "<td>";

                        
                        if (isset($allGPA[$student['StudentID']][$course['CourseID']])) {
                            
                            echo number_format($allGPA[$student['StudentID']][$course['CourseID']], 2);
                        } else {
                            
                            echo "N/A";
                        }

                        echo "</td>";
                    }

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        document.getElementById('exportPdfButton').addEventListener('click', function () {
            var element = document.querySelector('.table');
            
            
            var options = {
                filename: 'student_gpa_statistics.pdf',
            };

            
            html2pdf(element, options);
        });
    </script>
</body>

</html>
