<?php
include("session.php");
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movie_id = $_POST['movie_id'];
    $nick = mysqli_real_escape_string($conn, $_POST['nick']);
    $rating = intval($_POST['rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // Update query to include user_id
    $user_id = $_SESSION['id'];
    $insert_query = "INSERT INTO comments (nick, info, rating, movie_id, user_id, date) VALUES ('$nick', '$comment', '$rating', '$movie_id', '$user_id', NOW())";

    if (mysqli_query($conn, $insert_query)) {
        header("Location: details.php?id=$movie_id");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: index.php");
    exit();
}
?>
