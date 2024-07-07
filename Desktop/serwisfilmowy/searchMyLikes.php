<?php
include("menu.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Liked Movies</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  <style>
    .wrapper {
      width: 1200px;
      margin: 0 auto;
    }
    .poster {
      width: 320px;
      height: 410px;
      margin-bottom: 20px;
      transition: transform 0.3s ease-in-out;
    }
    .poster:hover {
      transform: scale(1.05);
    }
    .movie-column {
      width: 25%;
      padding: 0 15px;
    }
    .movie-title {
      margin-top: 5px;
      font-size: 16px;
      font-weight: bold;
      color: black;
      transition: color 0.3s ease-in-out;
    }
    .movie-column:hover .movie-title {
      color: black;
    }
  </style>
</head>
<body>
<div class="wrapper">
  <div class="container-fluid">
    <div class="row">
      <h2 class="text-center">My Liked Movies</h2>
      <?php
      include("session.php");
      require_once "config.php";

      // Check if user is logged in
      if (isset($_SESSION['login'])) {
        $userId = $_SESSION['id'];

        // Query to fetch liked movies for a specific user from users_likes table
        $sql = "SELECT m.id, m.name, m.img FROM movies m
                INNER JOIN users_likes ul ON m.id = ul.movie_id
                WHERE ul.user_id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
          mysqli_stmt_bind_param($stmt, "i", $userId);

          if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_array($result)) {
                ?>
                <div class="col-md-3 text-center movie-column">
                  <a href="details.php?id=<?php echo $row['id']; ?>">
                    <img src="<?php echo $row['img']; ?>" alt="<?php echo $row['name']; ?>" class="img-responsive poster">
                    <p class="movie-title"><?php echo $row['name']; ?></p>
                  </a>
                </div>
                <?php
              }
            } else {
              echo "<p class='lead'><em>No liked movies found.</em></p>";
            }
          } else {
            echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
          }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
      } else {
        echo "Please log in to view your liked movies.";
      }
      ?>

    </div>
          <br>
          <br>
          <br>
          <a href="index.php" class="btn btn-primary">Back to Main page</a>
  </div>
</div>
</body>
</html>
