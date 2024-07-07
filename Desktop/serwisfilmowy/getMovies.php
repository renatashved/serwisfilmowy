<?php
require_once "config.php";

$moviesPerPage = 4;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $moviesPerPage;
$output = '';

$searchQuery = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

if (isset($_GET['category']) && $_GET['category'] !== '') {
    $category_id = $_GET['category'];
    $data = "SELECT m.* FROM movies m
             INNER JOIN movie_categories mc ON m.id = mc.movies_id
             WHERE mc.categories_id = $category_id
             AND m.name LIKE '%$searchQuery%'
             LIMIT $moviesPerPage OFFSET $offset";
} else if (isset($_GET['sort']) && $_GET['sort'] !== '') {
    $sort = $_GET['sort'];
    $data = "SELECT m.*, AVG(c.rating) AS avg_rating
             FROM movies m
             LEFT JOIN comments c ON m.id = c.movie_id
             WHERE m.name LIKE '%$searchQuery%'
             GROUP BY m.id
             ORDER BY avg_rating $sort
             LIMIT $moviesPerPage OFFSET $offset";
} else {
    $data = "SELECT * FROM movies WHERE name LIKE '%$searchQuery%' LIMIT $moviesPerPage OFFSET $offset";
}

if ($result = mysqli_query($conn, $data)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $movieId = $row['id'];
            $avgRatingQuery = "SELECT AVG(rating) AS avg_rating FROM comments WHERE movie_id = $movieId";
            $avgRatingResult = mysqli_query($conn, $avgRatingQuery);
            $avgRatingRow = mysqli_fetch_assoc($avgRatingResult);
            $avgRating = $avgRatingRow['avg_rating'];

            $output .= '<div class="column is-one-quarter movie-column">';
            $output .= '<a href="details.php?id=' . $row['id'] . '">';
            $output .= '<img src="' . $row['img'] . '" alt="' . $row['name'] . '" class="img-responsive poster">';
            $output .= '<p class="movie-title">' . $row['name'] . ' | ' . number_format($avgRating, 2) . ' <img src="uploads/A_star.png" alt="star" style="width: 13px; height: 13px; margin-bottom: 3px;"></p>';
            $output .= '</a>';
            $output .= '</div>';
        }
        mysqli_free_result($result);
    } else {
        $output = '<div class="column is-full text-center">No movies found.</div>';
    }
} else {
    $output = "ERROR: Could not able to execute $data. " . mysqli_error($conn);
}

echo $output;
mysqli_close($conn);
?>
