<?php

include 'db_connection.php';


if (isset($_GET['id'])) {
    $conn = OpenCon();


    $courseID = $_GET['id'];


    $query = "DELETE FROM Courses WHERE CourseID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $courseID);

    if ($stmt->execute()) {
        header('Location: courses.php');
        exit();
    } else {
        echo "Error deleting course";
    }

    $stmt->close();
    CloseCon($conn);
} else {
    header('Location: courses.php');
    exit();
}
?>
