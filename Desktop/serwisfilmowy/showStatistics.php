<?php
include("session.php");
require("config.php");
include("menu.php");

$login = $_SESSION['login'];

// Получаем статистику режиссеров с наибольшим количеством лайков
$directors_query = "SELECT CONCAT(filmmakers.name, ' ', filmmakers.surname) As name, COUNT(users_likes.id) as like_count
                   FROM filmmakers
                   INNER JOIN movie_filmmakers ON filmmakers.id = movie_filmmakers.filmmaker_id
                   INNER JOIN movies ON movie_filmmakers.movie_id = movies.id
                   INNER JOIN users_likes ON movies.id = users_likes.movie_id
                   GROUP BY filmmakers.id
                   ORDER BY like_count DESC
                   LIMIT 5";

$directors_result = mysqli_query($conn, $directors_query);



// Запрос на получение статистики пользователей с наибольшим количеством комментариев
$stat_query = "SELECT users.login, COUNT(comments.id) AS comment_count
              FROM users
              INNER JOIN comments ON users.login = comments.nick
              GROUP BY users.login
              ORDER BY comment_count DESC
              LIMIT 5";
$stat_result = mysqli_query($conn, $stat_query);




$comments_query = "SELECT * FROM comments";
$comments_result = mysqli_query($conn, $comments_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Используйте STR_TO_DATE для конвертации строки в дату
    $report_query = "SELECT COUNT(*) as comment_count FROM comments 
                    WHERE STR_TO_DATE(date, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
    $report_result = mysqli_query($conn, $report_query);

    if ($report_result) {
        $report_data = mysqli_fetch_assoc($report_result);
        $comment_count = $report_data['comment_count'];
    } else {
        $error_message = "Error retrieving report data.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Director Statistics</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">

        <h2>Top 5 Directors with Most Likes</h2>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Director</th>
                <th>Likes Count</th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($directors_result)) {
                echo "<tr>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['like_count']}</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
  

    <h2>Top 5 Users with the Most Comments</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User Login</th>
                <th>Comment Count</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($stat_result)) {
                echo "<tr>";
                echo "<td>{$row['login']}</td>";
                echo "<td>{$row['comment_count']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>


    <form method="post" class="mt-3">
            <h3>Generate Report</h3>
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate Report</button>
        </form>
        <?php
        if (isset($comment_count)) {
            ?>
            <div class="mt-3">
                <p><strong>Comments between <?php echo $start_date; ?> and <?php echo $end_date; ?>:</strong> <?php echo $comment_count; ?></p>
            </div>
            <?php
        } elseif (isset($error_message)) {
            ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?php echo $error_message; ?>
            </div>
            <?php
        }
        ?>
        
    <a href="index.php" class="btn btn-primary mt-3">Back to Main page</a>
</div>
</body>
</html>
