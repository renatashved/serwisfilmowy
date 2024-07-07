<?php
include("session.php");
include("config.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Check if the movie is already in the viewing history for this user
    $user_id = $_SESSION['id'];
    $check_history_query = "SELECT * FROM historia WHERE idMovie = $id AND idUser = $user_id";
    $check_history_result = mysqli_query($conn, $check_history_query);

    if (mysqli_num_rows($check_history_result) == 0) {
        // If the movie is not in the viewing history, insert it
        $insert_history_query = "INSERT INTO historia (idMovie, idUser, viewDate) VALUES ($id, $user_id, NOW())";
        mysqli_query($conn, $insert_history_query);
    }

    $query = "SELECT * FROM movies WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }

    $row = mysqli_fetch_assoc($result);
} else {
    die("Error");
}

// Fetch categories
$current_movie_categories = [];
$categories_query = "SELECT name, c.id FROM categories c JOIN movie_categories mc ON c.id = mc.categories_id WHERE mc.movies_id = $id";
$categories_result = mysqli_query($conn, $categories_query);
while ($category = mysqli_fetch_assoc($categories_result)) {
    $current_movie_categories[] = $category['name'];
    $category_ids[] = $category['id'];
}

// Fetch actors
$current_movie_actors = [];
$actors_query = "SELECT CONCAT(a.name, ' ', a.surname) as name FROM actors a JOIN movie_actors ma ON a.id = ma.actor_id WHERE ma.movie_id = $id";
$actors_result = mysqli_query($conn, $actors_query);
while ($actor = mysqli_fetch_assoc($actors_result)) {
    $current_movie_actors[] = $actor['name'];
}

// Fetch filmmakers
$current_movie_filmmakers = [];
$filmmakers_query = "SELECT CONCAT(f.name, ' ', f.surname) as name FROM filmmakers f JOIN movie_filmmakers mf ON f.id = mf.filmmaker_id WHERE mf.movie_id = $id";
$filmmakers_result = mysqli_query($conn, $filmmakers_query);
while ($filmmaker = mysqli_fetch_assoc($filmmakers_result)) {
    $current_movie_filmmakers[] = $filmmaker['name'];
}

// Fetch average rating
$average_query = "SELECT AVG(rating) AS avg_rating FROM comments WHERE movie_id = $id";
$average_result = mysqli_query($conn, $average_query);
$avg_rating = 0;
if ($average_result && mysqli_num_rows($average_result) > 0) {
    $average_row = mysqli_fetch_assoc($average_result);
    $avg_rating = $average_row['avg_rating'];
}

// Fetch user's rating
$user_rating_query = "SELECT rating FROM comments WHERE movie_id = $id AND user_id = $user_id";
$user_rating_result = mysqli_query($conn, $user_rating_query);
$user_rating = 0;
if ($user_rating_result && mysqli_num_rows($user_rating_result) > 0) {
    $user_rating_row = mysqli_fetch_assoc($user_rating_result);
    $user_rating = $user_rating_row['rating'];
}

// Fetch comments
$comments_query = "SELECT * FROM comments WHERE movie_id = $id";
$comments_result = mysqli_query($conn, $comments_query);

// Fetch viewing history
$history_query = "
    SELECT h.viewDate, m.name AS movieName, m.img AS movieImg
    FROM historia h
    JOIN movies m ON h.idMovie = m.id
    WHERE h.idUser = $user_id
    ORDER BY h.viewDate DESC";
$history_result = mysqli_query($conn, $history_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <style>
        .img-container img {
            border-radius: 8px;
        }

        .list-group-item {
            border: none;
            background: none;
            padding: 0.5rem 0;
        }

        .list-group-item strong {
            color: #363636;
        }

        .btn {
            margin: 10px 0;
        }

        .columns {
            flex-wrap: wrap;
        }

        .column img {
            max-width: 100%;
        }

        .comments-section {
            margin-top: 20px;
        }

        .related-movies .column {
            margin-bottom: 20px;
        }

        .share-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        .table .table {
            background-color: #fff;
        }

        .history-table img {
            width: 50px;
            height: auto;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <?php include("menu.php"); ?>
    <div class="container mt-5">
        <div class="columns">
            <div class="column is-half">
                <div class="img-container">
                    <img src="<?php echo $row['img']; ?>" alt="Movie Image">
                </div>
            </div>
            <div class="column is-half">
                <h1 class="title"><?php echo $row['name'] ?></h1>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Description:</strong> <?php echo $row['description']; ?></li>
                    <li class="list-group-item"><strong>Year:</strong> <?php echo $row['year']; ?></li>
                    <li class="list-group-item">
                        <strong>Category:</strong>
                        <?php echo implode(', ', $current_movie_categories); ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Main Actors:</strong>
                        <?php echo implode(', ', $current_movie_actors); ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Filmmakers:</strong>
                        <?php echo implode(', ', $current_movie_filmmakers); ?>
                    </li>
                    <li class="list-group-item"><strong>Country:</strong> <?php echo $row['country']; ?></li>
                    <li class="list-group-item"><strong>Your Rating:</strong> <?php echo $user_rating > 0 ? $user_rating : 'Not rated yet'; ?>/10</li>
                    <?php if ($avg_rating > 0): ?>
                        <li class="list-group-item"><strong>Average Rating:</strong> <?php echo number_format($avg_rating, 2); ?>/10</li>
                    <?php else: ?>
                        <li class="list-group-item"><strong>No ratings yet.</strong></li>
                    <?php endif; ?>
                </ul>
                <div class="share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode("http://yourdomain.com/details.php?id=" . $id); ?>" class="button is-link" target="_blank">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode("http://yourdomain.com/details.php?id=" . $id); ?>&text=Check out this movie!" class="button is-info" target="_blank">
                        <i class="fab fa-twitter"></i> Share on Twitter
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode("http://yourdomain.com/details.php?id=" . $id); ?>" class="button is-primary" target="_blank">
                        <i class="fab fa-linkedin-in"></i> Share on LinkedIn
                    </a>
                </div>
                <?php
                $user_id = $_SESSION["id"];
                $sqlCheckFavorite = "SELECT id FROM users_likes WHERE movie_id = $id AND user_id = $user_id";
                $resultCheckFavorite = mysqli_query($conn, $sqlCheckFavorite);

                $added = ($resultCheckFavorite && mysqli_num_rows($resultCheckFavorite) > 0);
                $heartImage = $added ? "uploads/heart_red.png" : "uploads/heart.png";
                ?>
                <img class="fav" id="fav-icon" data-movie="<?php echo $id; ?>" src="<?php echo $heartImage; ?>" style="width: 35px; margin-top: 10px;">
                <div class="mt-4">
                    <h3 class="title is-4">Add a Comment</h3>
                    <form method="POST" action="insertComment.php">
                        <input type="hidden" name="movie_id" value="<?php echo $id; ?>">
                        <div class="field">
                            <label class="label">Nickname: <?php echo $_SESSION['login']; ?></label>
                            <input type="hidden" class="input" name="nick" value="<?php echo $_SESSION['login']; ?>" required>
                        </div>
                        <div class="field">
                            <label class="label" for="rating">Your Rating (1-10):</label>
                            <input type="number" class="input" id="rating" name="rating" min="1" max="10" required>
                        </div>
                        <div class="field">
                            <label class="label" for="comment">Your Comment:</label>
                            <textarea class="textarea" id="comment" name="comment" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="button is-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="comments-section">
            <h2 class="title is-4">Comments</h2>
            <?php if ($comments_result && mysqli_num_rows($comments_result) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nickname</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
                            <tr>
                                <td><?php echo $comment['nick']; ?></td>
                                <td><?php echo $comment['rating']; ?>/10</td>
                                <td><?php echo nl2br($comment['info']); ?></td>
                                <td><?php echo $comment['date']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No comments yet.</p>
            <?php endif; ?>
        </div>
        <div class="viewing-history">
            <h2 class="title is-4">Your Viewing History</h2>
            <?php if ($history_result && mysqli_num_rows($history_result) > 0): ?>
                <table class="table history-table">
                    <thead>
                        <tr>
                            <th>Movie Image</th>
                            <th>Movie Name</th>
                            <th>View Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($history = mysqli_fetch_assoc($history_result)): ?>
                            <tr>
                                <td><img src="<?php echo $history['movieImg']; ?>" alt="Movie Image"></td>
                                <td><?php echo $history['movieName']; ?></td>
                                <td><?php echo $history['viewDate']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No viewing history available.</p>
            <?php endif; ?>
        </div>
        <div class="related-movies">
            <h2 class="title is-4">Related Movies</h2>
            <div class="columns is-multiline">
                <?php
                if (!empty($category_ids)) {
                    $related_movies_query = "SELECT DISTINCT m.id, m.name, m.img FROM movies m
                                             INNER JOIN movie_categories mc ON m.id = mc.movies_id
                                             WHERE mc.categories_id IN (" . implode(',', $category_ids) . ")
                                             AND m.id <> $id LIMIT 4";
                    $related_movies_result = mysqli_query($conn, $related_movies_query);

                    if ($related_movies_result && mysqli_num_rows($related_movies_result) > 0) {
                        while ($related_movie = mysqli_fetch_assoc($related_movies_result)) {
                ?>
                            <div class="column is-one-quarter">
                                <div class="img-container">
                                    <a href="details.php?id=<?php echo $related_movie['id']; ?>">
                                        <img src="<?php echo $related_movie['img']; ?>" alt="<?php echo $related_movie['name']; ?>">
                                    </a>
                                    <p><?php echo $related_movie['name']; ?></p>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        echo "No related movies found.";
                    }
                } else {
                    echo "No related movies found.";
                }
                ?>
            </div>
        </div>
        <a href="index.php" class="button is-primary mt-3">Back to Main page</a>
        <?php
        if (isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
            echo '<div class="mt-4">
                <a href="updateMovieForm.php?id=' . $id . '" class="button is-primary">Update data</a>
            </div>';
        }
        if (isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
            echo '<div class="mt-4">
                <button class="button is-danger" id="deleteMovieBtn" data-bs-toggle="modal">Delete movie</button>
            </div>';
        }
        ?>
    </div>
    <script>
        $(document).ready(function() {
            $(".fav").on("click", function() {
                const img = $(this);
                const movie_id = img.data("movie");

                $.post(
                    "addLike.php", {
                        movie_id: movie_id
                    },
                    function(data) {
                        console.log(data);
                        if (data.trim().includes("Success")) {
                            const currentSrc = img.attr("src");
                            const newSrc = currentSrc.includes("heart.png") ? "uploads/heart_red.png" : "uploads/heart.png";
                            img.attr("src", newSrc);
                        }
                    }
                );
            });

            $("#deleteMovieBtn").on("click", function() {
                if (confirm("Are you sure you want to delete this movie?")) {
                    $.post(
                        "delete.php", {
                            movie_id: <?php echo $id; ?>
                        },
                        function(data) {
                            if (data.trim().includes("Success")) {
                                alert("Movie deleted successfully");
                                window.location.href = "index.php";
                            } else {
                                alert("Error deleting movie");
                            }
                        }
                    );
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
