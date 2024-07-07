<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css"></head>
<body>
<div class="container mt-5">
    <?php
    require("config.php");
    if (isset($_POST["login"])) {
        $login = $_POST["login"];
        $pass = $_POST["pass"];
        $email = $_POST["email"];
        $sql = "INSERT INTO users (login, pass, email, role) VALUES ('$login', '" . md5($pass) . "', '$email', 'user')";
        $result = $conn->query($sql);
        if ($result) {
            ?>
            <div class="alert alert-success" role="alert">
                You have been successfully registered. <a href='login.php' class='alert-link'>Click here to log in</a>
            </div>
            <?php
        } else {
            ?>
            <div class="alert alert-danger" role="alert">
                You did not fill in the required fields. <a href='registration.php' class='alert-link'>Click here to try again</a>.
            </div>
            <?php
        }
    } else {
        ?>
        <form class="form" action="" method="post">
            <h1 class="login-title">Registration</h1>
            <input type="text" class="form-control mb-2" name="login" placeholder="Login" required maxlength="30"/>
            <input type="password" class="form-control mb-2" name="pass" placeholder="Password" required/>
            <input type="text" class="form-control mb-2" name="email" placeholder="Adres email" required/>
            <button type="submit" name="submit" class="btn btn-primary btn-block">Zarejestruj się</button>
            <p class="mt-3">Do you have an account? <a href="login.php">Log in</a></p>
        </form>
        <?php
    }
    ?>
</div>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
