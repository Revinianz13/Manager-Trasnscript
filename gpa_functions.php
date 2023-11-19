<?php



function calculateGPA($conn, $studentID, $courseID) {
    // Calculate GPA for the student and course
    $gpaQuery = "SELECT AVG(Score) AS GPA FROM StudentCourses WHERE StudentID = '{$studentID}' AND CourseID = '{$courseID}'";
    $gpaResult = $conn->query($gpaQuery);
    $gpaRow = $gpaResult->fetch_assoc();
    $gpa = $gpaRow['GPA'];

    return $gpa;
}

function updateCourseAverages($conn, $courseID) {
    // Calculate and update average GPA for the course
    $avgGpaQuery = "SELECT AVG(GPA) AS AVG_GPA FROM StudentAverages WHERE CourseID = '{$courseID}'";
    $avgGpaResult = $conn->query($avgGpaQuery);
    $avgGpaRow = $avgGpaResult->fetch_assoc();
    $avgGpa = $avgGpaRow['AVG_GPA'];

    // Update or insert average GPA into the CourseAverages table
    $updateAvgGpaQuery = "INSERT INTO CourseAverages (CourseID, AVG_GPA) VALUES ('{$courseID}', $avgGpa) ON DUPLICATE KEY UPDATE AVG_GPA = $avgGpa";
    $conn->query($updateAvgGpaQuery);
}

function calculateAndUpdateAllGPAs($conn) {
    // Fetch all students and courses
    $studentsQuery = "SELECT * FROM Students";
    $studentsResult = $conn->query($studentsQuery);
    $allStudents = $studentsResult->fetch_all(MYSQLI_ASSOC);

    $coursesQuery = "SELECT * FROM Courses";
    $coursesResult = $conn->query($coursesQuery);
    $allCourses = $coursesResult->fetch_all(MYSQLI_ASSOC);

    // Calculate and update GPA and average GPA for all students and courses
    foreach ($allStudents as $student) {
        foreach ($allCourses as $course) {
            // Calculate GPA for the student and course
            $gpa = calculateGPA($conn, $student['StudentID'], $course['CourseID']);

            // Update or insert GPA into the StudentAverages table
            $updateGpaQuery = "INSERT INTO `StudentAverages` (`StudentID`, `CourseID`, `GPA`) VALUES ('{$student['StudentID']}', '{$course['CourseID']}', $gpa) ON DUPLICATE KEY UPDATE `GPA` = $gpa";
            $conn->query($updateGpaQuery);

            // Calculate and update average GPA for the course
            updateCourseAverages($conn, $course['CourseID']);
        }
    }
}

// Call the function to calculate and update all GPAs
calculateAndUpdateAllGPAs($conn);
// Function to get student details
function getStudentDetails($conn, $studentID) {
    $query = "SELECT * FROM Students WHERE StudentID = '$studentID'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Function to calculate and update GPA and average GPA for the given student
function calculateAndUpdateStudentGPAs($conn, $studentID) {


    $coursesQuery = "SELECT DISTINCT CourseID FROM StudentCourses WHERE StudentID = '$studentID'";
    $coursesResult = $conn->query($coursesQuery);

    while ($courseRow = $coursesResult->fetch_assoc()) {
        $courseID = $courseRow['CourseID'];

        // Calculate GPA for the student and course
        $gpa = calculateGPA($conn, $studentID, $courseID);

        // Update or insert GPA into the StudentAverages table
        $updateGpaQuery = "INSERT INTO StudentAverages (StudentID, CourseID, GPA) 
                           VALUES ('$studentID', '$courseID', $gpa) 
                           ON DUPLICATE KEY UPDATE GPA = $gpa";
        $conn->query($updateGpaQuery);

        // Calculate and update average GPA for the course
        updateCourseAverages($conn, $courseID);
    }
}

// Function to fetch GPA for the student and courses
function getStudentGPAs($conn, $studentID) {
    $studentGPA = array();

    $query = "SELECT CourseID, GPA FROM StudentAverages WHERE StudentID = '$studentID'";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $studentGPA[$row['CourseID']] = $row['GPA'];
    }

    return $studentGPA;
}

// Function to fetch average GPA for each course
function getCourseAverages($conn) {
    $courseAverages = array();

    $query = "SELECT CourseID, AVG_GPA FROM CourseAverages";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $courseAverages[$row['CourseID']] = $row['AVG_GPA'];
    }

    return $courseAverages;
}







// Function to get the name of a course based on its ID
function getCourseName($conn, $courseID) {
    
    $query = "SELECT CourseName FROM Courses WHERE CourseID = '$courseID'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();
        return $course['CourseName'];
    } else {
        return "Course Not Found";
    }
}
?>
