<?php
include("session.php");
require("config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <nav class="navbar is-dark">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="index.php">
                    Serwer Filmowy
                </a>
                <div class="navbar-burger burger" data-target="navbarMenu">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div id="navbarMenu" class="navbar-menu">
                <div class="navbar-end">
                    <a href="profile.php" class="navbar-item">
                        My Profile
                    </a>
                    <a href="searchMyComments.php" class="navbar-item">
                        My Comments
                    </a>
                    <a href="searchMyLikes.php" class="navbar-item">
                        My Likes
                    </a>
                    <?php
                    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                        echo '<a href="showStatistics.php" class="navbar-item">Statistics</a>';
                        echo '<a href="addMovieForm.php" class="navbar-item">Add Movie</a>';
                    }
                    ?>
                    <a href="logout.php" class="navbar-item">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
            if ($navbarBurgers.length > 0) {
                $navbarBurgers.forEach(el => {
                    el.addEventListener('click', () => {
                        const target = el.dataset.target;
                        const $target = document.getElementById(target);
                        el.classList.toggle('is-active');
                        $target.classList.toggle('is-active');
                    });
                });
            }
        });
    </script>
</body>
</html>
