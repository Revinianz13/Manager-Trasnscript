<?php

$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];

$homePageLink = $loggedIn ? './menu.php' : './index.php';
?>

<link rel="stylesheet" href="nav.css">

<nav class="navbar navbar-expand-lg bg-secondary-subtle">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo $homePageLink; ?>"><img class="main-nav-img" src="./professor.png"></a>

        <?php
        
        if ($loggedIn) {
            echo '<div class="collapse navbar-collapse" id="navbarNav">';
            echo '<ul class="navbar-nav">';
            echo '<li class="nav-item">';
            echo '<a class="nav-link active text-to-change" href="./courses.php">Courses</a>';
            echo '</li>';
            echo '<li class="nav-item">';
            echo '<a class="nav-link active text-to-change" href="./display_students.php">Students</a>';
            echo '</li>';
            echo '<li class="nav-item">';
            echo '<a class="nav-link active text-to-change" href="gpa_display.php">GPA</a>';
            echo '</li>';
            echo '</ul>';


            echo '<button class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#logoutModal">';
            echo '<i class="bi bi-lock-fill"></i> Log Out';
            echo '</button>';

            echo '</div>';

            echo '<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
            echo '<div class="modal-dialog modal-dialog-centered">';
            echo '<div class="modal-content" style="background-color: #343a40; color: white;">';
            echo '<div class="modal-header border-0">';
            echo '<h5 class="modal-title" id="exampleModalLabel">Logout</h5>';
            echo '<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo 'Are you sure you want to log out?';
            echo '</div>';
            echo '<div class="modal-footer border-0">';
            echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>';
            echo '<a href="logout.php" class="btn btn-danger">Yes, Logout</a>'; 
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</nav>
