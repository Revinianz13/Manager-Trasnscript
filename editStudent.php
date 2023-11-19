<?php
session_start();


if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {

    header('Location: login.php');
    exit();
}


include 'db_connection.php';


function getStudentById($conn, $studentID)
{
    $query = "SELECT * FROM Students WHERE StudentID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $studentID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

$conn = OpenCon();

$studentID = isset($_GET['id']) ? $_GET['id'] : null;

$student = getStudentById($conn, $studentID);

if (!$student) {

    header('Location: error.php');
    exit();
}

$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $dateOfBirth = $_POST['date_of_birth'];



    if (count($errors) == 0) {

        $query = "UPDATE Students SET FirstName = ?, LastName = ?, DateOfBirth = ? WHERE StudentID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssi', $firstName, $lastName, $dateOfBirth, $studentID);

        if ($stmt->execute()) {
            
            $_SESSION['update_student_status'] = 'success';
            $_SESSION['update_student_message'] = 'Student updated successfully';


            header('Location: display_students.php');
            exit();
        } else {

            $_SESSION['update_student_status'] = 'error';
            $_SESSION['update_student_message'] = 'Something went wrong. Please try again.';
            echo "Error updating student";
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
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./main.css">
</head>

<body>
    <?php include 'nav.php' ?>

    <div class="container mt-4 cont position-absolute top-50 start-50 translate-middle border border border-light" style=" color: white; width: 50%;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Edit Student</h2>
            <a href="display_students.php" class="btn btn-secondary btn-sm">Back to Students</a>
        </div>

        <!-- Display error messages -->
        <?php
        if (count($errors) > 0) {
            echo '<div class="alert alert-danger">';
            foreach ($errors as $error) {
                echo '<p>' . $error . '</p>';
            }
            echo '</div>';
        }
        ?>

        <!-- Student Edit Form -->
        <form method="post">
            <div class="mb-3">
                <label for="first_name" class="form-label" style="color: white;">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" value="<?php echo htmlspecialchars($student['FirstName']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label" style="color: white;">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" value="<?php echo htmlspecialchars($student['LastName']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="date_of_birth" class="form-label" style="color: white;">Date of Birth</label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" placeholder="Enter Date of Birth" value="<?php echo htmlspecialchars($student['DateOfBirth']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" id="submitBtn">Update Student</button>
        </form>
    </div>
</body>

</html>
