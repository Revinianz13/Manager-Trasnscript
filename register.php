<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connection.php';
$conn = OpenCon();


$firstName = $lastName = $username = $password = $email = "";
$errors = [];


if (isset($_POST['register'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    
    $checkUsernameQuery = "SELECT * FROM Teachers WHERE Username = ?";
    $checkUsernameStmt = $conn->prepare($checkUsernameQuery);
    $checkUsernameStmt->bind_param("s", $username);
    $checkUsernameStmt->execute();
    $checkUsernameResult = $checkUsernameStmt->get_result();

    if ($checkUsernameResult->num_rows > 0) {
        array_push($errors, "Username is already in use");
    }

    
    $checkEmailQuery = "SELECT * FROM Teachers WHERE Email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailQuery);
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();

    if ($checkEmailResult->num_rows > 0) {
        array_push($errors, "Email is already in use");
    }

    if (empty($firstName)) {
        array_push($errors, "First name is required");
    }
    if (empty($lastName)) {
        array_push($errors, "Last name is required");
    }
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Invalid email format");
    }

    if (count($errors) == 0) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        
        $stmt = $conn->prepare("INSERT INTO Teachers (FirstName, LastName, Username, Email, Password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstName, $lastName, $username, $email, $hashed_password);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            
            echo "<script>
                    alert('User successfully registered');
                    window.location.href = './login.php';
                  </script>";
            exit;
        } else {
            echo "Database Error: " . $stmt->error;
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./main.css">
</head>
<body>
<?php include './nav.php'?>

<div class="cont position-absolute top-50 start-50 translate-middle border border border-light">
    <h2>Register</h2>

    <?php
        
        if (count($errors) > 0) {
            $firstError = $errors[0];
            echo '<div id="error-message" class="alert alert-danger">' . $firstError . '</div>';
            
            $errors = [];
        }
    ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="registration-form">
        <div class="d-grid gap-2">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo htmlspecialchars($firstName); ?>">
                <small class="text-danger" id="first_name_error"></small>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo htmlspecialchars($lastName); ?>">
                <small class="text-danger" id="last_name_error"></small>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>">
                <small class="text-danger" id="username_error"></small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" value="<?php echo htmlspecialchars($password); ?>">
                <small class="text-danger" id="password_error"></small>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
                <small class="text-danger" id="email_error"></small>
                <small class="text-danger" id="email_used_error"></small>
            </div>
        </div>
        <button type="submit" name="register" class="btn btn-primary mt-3">Register</button>
        <p>
            Already have an account? <a href="./login.php">Login</a>
        </p>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script>
        const form = document.getElementById('registration-form');
        const errorMessage = document.getElementById('error-message');

        form.addEventListener('submit', function (e) {
            
            if (errorMessage) {
                errorMessage.remove();
            }

            let hasErrors = false;


            function displayError(inputElement, errorMessageElement, errorMessage) {
                hasErrors = true;
                errorMessageElement.textContent = errorMessage;
                inputElement.classList.add('is-invalid');
            }


            function clearError(inputElement, errorMessageElement) {
                errorMessageElement.textContent = '';
                inputElement.classList.remove('is-invalid');
            }



            const email = document.getElementById('email');
            const emailError = document.getElementById('email_error');
            const emailUsedError = document.getElementById('email_used_error');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            clearError(email, emailError);
            clearError(email, emailUsedError);

            if (email.value.trim() === '') {
                displayError(email, emailError, 'Email is required');
            } else if (!emailPattern.test(email.value)) {
                displayError(email, emailError, 'Invalid email format');
            } else {
                
                <?php
                if (isset($errors) && in_array("Email is already in use", $errors)) {
                    echo 'displayError(email, emailUsedError, "Email is already in use");';
                    echo 'hasErrors = true;';
                }
                ?>
            }

            
            if (!hasErrors) {
                clearError(email, emailError);
                clearError(email, emailUsedError);
            } else {
                e.preventDefault(); 
            }
            
            hasErrors = false;

            
            setTimeout(function () {
                if (errorMessage) {
                    errorMessage.remove();
                }
            }, 30000);
        });
    </script>
</body>
</html>
<?php 
CloseCon($conn);
?>


