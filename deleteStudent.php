<?php
session_start(); 


include 'db_connection.php';


if (isset($_GET['id'])) {
    $studentId = $_GET['id'];

    
    function deleteStudent($conn, $studentId)
    {
        $query = "DELETE FROM Students WHERE StudentID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $stmt->close();
    }

    
    $conn = OpenCon();
    deleteStudent($conn, $studentId);
    CloseCon($conn);

    
    $_SESSION['delete_status'] = 'success'; 
    $_SESSION['delete_message'] = 'Student deleted successfully.';
} else {
   
    $_SESSION['delete_status'] = 'error'; 
    $_SESSION['delete_message'] = 'Error: No student ID provided.';
}

header('Location: display_students.php');
exit();
?>


        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Delete Student</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        </head>

        <body>
            <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this student?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <a href="delete_student_confirm.php?id=<?php echo $studentId; ?>" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function () {
                    $('#confirmationModal').modal('show');
                });
            </script>
        </body>

        </html>
        <?php
    CloseCon($conn);
?>
