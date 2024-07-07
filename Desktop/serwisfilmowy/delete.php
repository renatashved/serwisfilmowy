<?php
include("config.php");

if (isset($_POST['movie_id'])) {
    $movie_id = $_POST['movie_id'];

    $deleteCommentsQuery = "DELETE FROM comments WHERE movie_id = $movie_id";
    mysqli_query($conn, $deleteCommentsQuery);

    $deleteLikesQuery = "DELETE FROM users_likes WHERE movie_id = $movie_id";
    mysqli_query($conn, $deleteLikesQuery);

    $deleteMovieCategoriesQuery = "DELETE FROM movie_categories WHERE movies_id = $movie_id";
    mysqli_query($conn, $deleteMovieCategoriesQuery);

    $deleteMovieQuery = "DELETE FROM movies WHERE id = $movie_id";
    if (mysqli_query($conn, $deleteMovieQuery)) {
        echo "Success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Error id";
}
?>
