<?php
include("session.php");
require("config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Serwis Filmowy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <style>
        .movie-column {
            padding: 10px;
        }
        .movie-column img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .movie-title {
            margin-top: 10px;
            font-size: 1.1rem;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            color: #fff;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .no-movies {
            display: none;
        }
    </style>
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container">
        <div class="field">
            <label class="label" for="categoryFilter">Filter by Category:</label>
            <div class="control">
                <div class="select">
                    <select id="categoryFilter">
                        <option value="">All</option>
                        <?php
                        $categories_query = "SELECT * FROM categories";
                        $categories_result = mysqli_query($conn, $categories_query);

                        if ($categories_result && mysqli_num_rows($categories_result) > 0) {
                            while ($category = mysqli_fetch_assoc($categories_result)) {
                                echo "<option value='{$category['id']}'>{$category['name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <label class="label" for="sortRating">Sort by Rating:</label>
            <div class="control">
                <div class="select">
                    <select id="sortRating">
                        <option value="">All</option>
                        <option value="asc">Low rated first</option>
                        <option value="desc">Highly rated first</option>
                    </select>
                </div>
            </div>
            <label class="label" for="searchMovie">Search by Name:</label>
            <div class="control">
                <input type="text" class="input" id="searchMovie" placeholder="Search movie">
            </div>
        </div>
        <div class="columns is-multiline movies-container">
            <!-- Movies will be loaded here -->
        </div>
        <div class="no-movies column is-full text-center">
            No more movies to load.
        </div>
    </div>
    <div class="footer">
        <!-- Content for the footer -->
    </div>
    <script>
        $(document).ready(function () {
            var page = 1;
            var isLoading = false;
            var selectedCategory = '';
            var sortDirection = '';
            var searchQuery = '';

            function loadMovies(category = '', sort = '', search = '') {
                $.ajax({
                    url: 'getMovies.php?page=' + page + '&category=' + category + '&sort=' + sort + '&search=' + search,
                    type: 'get',
                    success: function (response) {
                        if (page === 1) {
                            $('.movies-container').html(response);
                        } else {
                            $('.movies-container').append(response);
                        }

                        if ($.trim(response).length === 0 || response.indexOf("No movies found.") >= 0) {
                            $('.no-movies').show();
                            isLoading = true; // stop further loading
                        } else {
                            page++;
                            isLoading = false;
                        }
                    }
                });
            }

            loadMovies();

            $('#categoryFilter').change(function () {
                var category = $(this).val();
                $('.movies-container').empty();
                page = 1;
                selectedCategory = category;
                loadMovies(selectedCategory, sortDirection, searchQuery);
            });

            $('#sortRating').change(function () {
                sortDirection = $(this).val();
                $('.movies-container').empty();
                page = 1;
                loadMovies(selectedCategory, sortDirection, searchQuery);
            });

            $('#searchMovie').on('input', function () {
                searchQuery = $(this).val();
                $('.movies-container').empty();
                page = 1;
                loadMovies(selectedCategory, sortDirection, searchQuery);
            });

            $(window).scroll(function () {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100 && !isLoading) {
                    isLoading = true;
                    loadMovies(selectedCategory, sortDirection, searchQuery);
                }
            });
        });
    </script>
</body>
</html>
