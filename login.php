<?php
session_start(); 

include 'db_connection.php';
$conn = OpenCon();
$errors = [];
$usernameValue = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        array_push($errors, "Both username and password are required");
    }

    if (count($errors) == 0) {
        $stmt = $conn->prepare("SELECT TeacherID, Password FROM Teachers WHERE Username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($userId, $hashed_password);
        $stmt->fetch();
        $stmt->close();

        if ($userId) {
            if (password_verify($password, $hashed_password)) {
                
                $_SESSION['teacher_id'] = $userId;
                $_SESSION['username'] = $username; 
                $_SESSION['logged_in'] = true;
                header('Location: menu.php');
                exit(); 
            } else {
                array_push($errors, "Wrong password, please try again");
                $usernameValue = $username; 
            }
        } else {
            array_push($errors, "User not found");
        }
    }
}
CloseCon($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./main.css">
</head>
<body>
    <?php include './nav.php'?>

    <div class="cont position-absolute top-50 start-50 translate-middle border border border-light">
        <h2>Login</h2>
        <?php
            
            if (count($errors) > 0) {
                echo '<div class="alert alert-danger">';
                foreach ($errors as $error) {
                    echo '<p>' . $error . '</p>';
                }
                echo '</div>';
            }
        ?>
        <form method="post" action="login.php" id="login-form">
            
            <div class="d-grid gap-2">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" id="username" value="<?php echo $usernameValue; ?>" >
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password" >
                </div>
            </div>
            <button type="submit" name="login" class="btn btn-primary mt-3">Login</button>
            <p>
                Don't have an account? <a href="./register.php">Register</a>
            </p>
        </form>
    </div>
    <script>

        const form = document.getElementById('login-form');

        form.addEventListener('submit', function (e) {

            document.getElementById('error_messages').textContent = '';

            fetch('validate_login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    username: document.getElementById('username').value,
                    password: document.getElementById('password').value,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success && data.errors.password) {

                    document.getElementById('error_messages').textContent += data.errors.password + '<br>';
                    e.preventDefault(); 
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</body>

</html>
