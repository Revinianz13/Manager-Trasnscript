<?php
session_start(); 


if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

include 'db_connection.php';

function getCourses($conn, $teacherId)
{
    $query = "SELECT * FROM Courses WHERE TeacherID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $teacherId);
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


$teacherId = $_SESSION['teacher_id'];
$conn = OpenCon();


$courses = getCourses($conn, $teacherId);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./main.css">
</head>

<body>
    <?php include './nav.php' ?>

    <div class="container mt-4 cont position-absolute top-50 start-50 translate-middle border border border-light">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 style="color: white;">Existing Courses</h2>
            <a href="addcourse.php" class="btn btn-success btn-sm">Add Course <i class="bi bi-plus"></i></a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Course ID</th>
                    <th>Course Name</th>
                    <th>Course Credits</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course) : ?>
                    <tr>
                        <td><?php echo $course['CourseID']; ?></td>
                        <td><?php echo $course['CourseName']; ?></td>
                        <td><?php echo $course['CourseCredits']; ?></td>
                        
                        <td>
                            
                            <a href="edit_course.php?id=<?php echo $course['CourseID']; ?>" class="btn btn-primary btn-sm">Edit</a>

                            
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $course['CourseID']; ?>">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    
    <?php foreach ($courses as $course) : ?>
        <div class="modal fade" id="deleteModal<?php echo $course['CourseID']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content" style="background-color: #343a40; color: white;">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Course</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete the course: <strong><?php echo $course['CourseName']; ?></strong>?
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <a href="delete_course.php?id=<?php echo $course['CourseID']; ?>" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</body>

</html>

<?php
CloseCon($conn);
?>
