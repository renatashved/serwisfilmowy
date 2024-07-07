<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "movie_side_db2";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Error conection db: " . mysqli_connect_error());
}
?>
