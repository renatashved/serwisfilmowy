<?php
include("session.php");
include("menu.php");
require("config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results : Serwer Filmowy</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .movie-column {
            width: 25%;
            padding: 0 15px;
        }

        .movie-hidden {
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }

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
                    transform: scale(1.05); /* Increase size on hover */
                }

                .movie-column {
                    width: 25%;
                    padding: 0 15px;
                }

                .movie-title {
                    margin-top: 5px;
                    font-size: 16px;
                    font-weight: bold;
                    color: black; /* Set text color to black */
                    transition: color 0.3s ease-in-out;
                }

                .movie-column:hover .movie-title {
                    color: black; /* Change text color on hover */
                }

               .footer {
                   position: fixed;
                   bottom: 10px;
                   right: 10px;
                   color: black;
                   font-size: 14px;
               }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <?php
                if (isset($_POST['search'])) {
                    $search = mysqli_real_escape_string($conn, $_POST['search']);

                        if (!empty($search)) {
                            $query = "SELECT * FROM movies WHERE name LIKE '%$search%'";
                            $result = mysqli_query($conn, $query);

                            if ($result && mysqli_num_rows($result) > 0) {
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
                                mysqli_free_result($result);
                            } else {
                                echo "<p class='lead'><em>No records found.</em></p>";
                            }
                        } else {
                            header("Location: index.php");
                            exit;
                        }
                } else {
                    echo "<p class='lead'><em>No search term provided.</em></p>";
                }
                mysqli_close($conn);
                ?>
            </div>
        </div>
    </div>
</body>
</html>