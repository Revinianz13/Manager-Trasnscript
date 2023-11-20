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

$studentGPA = array();
$courseAverages = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $studentID = $_POST['student_id'];

    
    $student = getStudentDetails($conn, $studentID);

    if ($student) {
        
        calculateAndUpdateStudentGPAs($conn, $studentID);

        
        $studentGPA = getStudentGPAs($conn, $studentID);

        
        $courseAverages = getCourseAverages($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Student GPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.js"></script>
    <link rel="stylesheet" href="./main.css">
</head>

<body>
    <?php include 'nav.php' ?>

    <div class="container mt-4 cont position-absolute start-50 translate-middle border border border-light" style="margin-top: 300px !important;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-4" style="color: white;"><?php echo $student['FirstName'] . ' ' . $student['LastName']; ?> GPA</h2>
            <button id="exportPdfButton" class="btn btn-success">Export Data to PDF</button>
        </div>
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && $student): ?>
            <div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Student GPA</th>
                            <th>Course AVG GPA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courseAverages as $courseID => $avg): ?>
                            <tr>
                                <td><?php echo getCourseName($conn, $courseID); ?></td>
                                <?php if (isset($studentGPA[$courseID])): ?>
                                    <td><?php echo number_format($studentGPA[$courseID], 2); ?></td>
                                <?php else: ?>
                                    <td>Not Attended</td>
                                <?php endif; ?>
                                <td><?php echo number_format($avg, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            
            <script>
                document.getElementById('exportPdfButton').addEventListener('click', function () {
                    var element = document.querySelector('.table');

                    
                    var options = {
                        filename: '<?php echo $student['FirstName'] . '_GPA_Statistics.pdf'; ?>',
                    };

                    
                    html2pdf(element, options);
                });
            </script>
        <?php endif; ?>
    </div>
</body>

</html>
<?php

CloseCon($conn);
?>