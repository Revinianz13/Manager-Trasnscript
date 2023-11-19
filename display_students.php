<?php
session_start(); 


if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    
    header('Location: login.php');
    exit();
}


include 'db_connection.php';

function getStudentsWithCourses($conn, $teacherId)
{
    
    $query = "SELECT Students.StudentID, Students.FirstName, Students.LastName, StudentCourses.CourseID, Courses.CourseName, StudentCourses.Score
              FROM Students
              LEFT JOIN StudentCourses ON Students.StudentID = StudentCourses.StudentID
              LEFT JOIN Courses ON StudentCourses.CourseID = Courses.CourseID
              WHERE Students.TeacherID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $teacherId);
    $stmt->execute();

    $result = $stmt->get_result();

    $students = array();

    while ($row = $result->fetch_assoc()) {
        $studentID = $row['StudentID'];

        
        if (!isset($students[$studentID])) {
            $students[$studentID] = array(
                'StudentID' => $studentID,
                'FirstName' => $row['FirstName'],
                'LastName' => $row['LastName'],
                'Courses' => array(),
            );
        }

        
        if ($row['CourseID'] !== null) {
            $students[$studentID]['Courses'][] = array(
                'CourseID' => $row['CourseID'],
                'CourseName' => $row['CourseName'],
                'Score' => $row['Score'],
            );
        }
    }

    $stmt->close();

    return $students;
}

$teacherId = $_SESSION['teacher_id'];
$conn = OpenCon();


$students = getStudentsWithCourses($conn, $teacherId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./main.css">
</head>

<body>
    <?php include './nav.php' ?>

    <div class="container mt-4 cont position-absolute start-50  translate-middle border border-light" style="margin-top: 400px!important;margin-bottom: 300px !important; width:100%; height: 75%;">
        <?php
        
        if (isset($_SESSION['delete_status']) && isset($_SESSION['delete_message'])) {
            $delete_status = $_SESSION['delete_status'];
            $delete_message = $_SESSION['delete_message'];

            
            echo '<div id="message" class="alert message-cont ' . ($delete_status == 'success' ? 'alert-success' : 'alert-danger') . ' mt-4" style="margin:0px !important">' . $delete_message . '</div>';


            unset($_SESSION['delete_status']);
            unset($_SESSION['delete_message']);
        } elseif (isset($_SESSION['add_student_status']) && isset($_SESSION['add_student_message'])) {
            $add_student_status = $_SESSION['add_student_status'];
            $add_student_message = $_SESSION['add_student_message'];


            echo '<div id="message" class="alert message-cont ' . ($add_student_status == 'success' ? 'alert-success' : 'alert-danger') . ' mt-4" style="margin:0px !important">' . $add_student_message . '</div>';


            unset($_SESSION['add_student_status']);
            unset($_SESSION['add_student_message']);
        } elseif (isset($_SESSION['add_course_status']) && isset($_SESSION['add_course_message'])) {
            $add_course_status = $_SESSION['add_course_status'];
            $add_course_message = $_SESSION['add_course_message'];


            echo '<div id="message" class="alert message-cont ' . ($add_course_status == 'success' ? 'alert-success' : 'alert-danger') . ' mt-4" style="margin:0px !important">' . $add_course_message . '</div>';


            unset($_SESSION['add_course_status']);
            unset($_SESSION['add_course_message']);
        } elseif (isset($_SESSION['update_student_status']) && isset($_SESSION['update_student_message'])) {
            $update_student_status = $_SESSION['update_student_status'];
            $update_student_message = $_SESSION['update_student_message'];


            echo '<div id="message" class="alert message-cont ' . ($update_student_status == 'success' ? 'alert-success' : 'alert-danger') . ' mt-4" style="margin:0px !important">' . $update_student_message . '</div>';


            unset($_SESSION['update_student_status']);
            unset($_SESSION['update_student_message']);
        }
        ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 style="color: white;">Students</h2>
            <a href="addstudent.php" class="btn btn-success btn-sm">Add Student <i class="bi bi-plus"></i></a>
        </div>

        <div class="table-responsive">
            
            <table class="table">

                <thead>
                    <tr>
                        
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Courses</th>
                        
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php foreach ($students as $student) : ?>
                        <tr>
                            <td><?php echo $student['FirstName']; ?></td>
                            <td><?php echo $student['LastName']; ?></td>
                            <td>
                                <?php foreach ($student['Courses'] as $course) : ?>
                                    <?php echo $course['CourseName'] . " (Score: " . $course['Score'] . ")<br>"; ?>
                                <?php endforeach; ?>
                            </td>
                            
                            <td>
                                <a href="addCourseStudent.php?id=<?php echo $student['StudentID']; ?>" class="btn btn-success btn-sm">Add Course</a>
                                <a href="editStudent.php?id=<?php echo $student['StudentID']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $student['StudentID']; ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>


    <!-- Delete Modal -->
    <?php foreach ($students as $student) : ?>
        <div class="modal fade" id="deleteModal<?php echo $student['StudentID']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content" style="background-color: #343a40; color: white;">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Student</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete the student: <strong><?php echo $student['FirstName'] . ' ' . $student['LastName']; ?></strong>?
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <a href="deleteStudent.php?id=<?php echo $student['StudentID']; ?>" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <script>

        setTimeout(function () {
            document.getElementById('message').style.display = 'none';
        }, 5000);
    </script>

</body>

</html>
