<?php
include("session.php");
include("config.php");

// Fetch user data
$user_id = $_SESSION["id"];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <?php include("menu.php"); ?>
    <div class="container mt-5">
        <div class="columns">
            <div class="column is-half is-offset-one-quarter">
                <div class="box">
                    <h1 class="title">User Profile</h1>
                    <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
                    <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                    <p><strong>Role:</strong> <?php echo $user['role']; ?></p>
                    <!-- Add more user information as needed -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>
