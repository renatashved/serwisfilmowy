<?php
include("session.php");
require("config.php");
include("menu.php");

$login = $_SESSION['login'];

$comments_query = "SELECT * FROM comments WHERE nick = '$login'";
$comments_result = mysqli_query($conn, $comments_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Comments</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">My Comments</h2>
        <ul class="list-group mt-3">
            <?php
            if ($comments_result && mysqli_num_rows($comments_result) > 0) {
                while ($comment = mysqli_fetch_assoc($comments_result)) {
                    ?>
                     <a href="details.php?id=<?php echo $comment['movie_id']; ?>" class="list-group-item">
                        <p><strong>Nickname:</strong> <?php echo $comment['nick']; ?></p>
                        <p><strong>Rating:</strong> <?php echo $comment['rating']; ?>/10</p>
                        <p><strong>Comment:</strong> <?php echo $comment['info']; ?></p>
                        <p><em>Date:</em> <?php echo $comment['date']; ?></p>
                    </a>
                    <?php
                }
            } else {
                ?>
                <li class="list-group-item">No comments found.</li>
                <?php
            }
            ?>
        </ul>
        <a href="index.php" class="btn btn-primary mt-3">Back to Main page</a>
    </div>
</body>
</html>
