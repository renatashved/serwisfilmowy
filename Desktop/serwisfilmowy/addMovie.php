<?php
include "config.php";

register_shutdown_function(function(){
	if (error_get_last()) {
		var_export(error_get_last());
	}
});

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $year = $_POST["year"];
    $country = $_POST["country"];
    $categories = $_POST["categories"];

    if (isset($_FILES['img'])) {
        $file = $_FILES['img'];
        $fileName = $_FILES['img']['name'];
        $fileTmpName = $_FILES['img']['tmp_name'];
        $fileDestination = 'uploads/' . $fileName;
        move_uploaded_file($fileTmpName, $fileDestination);

        $insertMovieQuery = "INSERT INTO movies (name, description, img, year, country) VALUES ('$name', '$description', '$fileDestination', '$year', '$country')";

        if (mysqli_query($conn, $insertMovieQuery)) {
            $movieId = mysqli_insert_id($conn);

            foreach ($categories as $categoryId) {
                $insertCategoryQuery = "INSERT INTO movie_categories (movies_id, categories_id) VALUES ('$movieId', '$categoryId')";
                mysqli_query($conn, $insertCategoryQuery);
            }

            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    if (isset($_POST['actors'])) {
        $actors = $_POST['actors'];
        foreach ($actors as $actorId) {
            $insertActorQuery = "INSERT INTO movie_actors (movie_id, actor_id) VALUES ('$movieId', '$actorId')";
            mysqli_query($conn, $insertActorQuery);
        }
    }
}
?>
