<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
<div class="container mt-5">
    <?php
    require('config.php');
    session_start();
    if (isset($_POST["login"])) {
        $login = $_POST["login"];
        $pass = $_POST["pass"];
        $sql = "SELECT * FROM users WHERE login='$login' AND pass='" . md5($pass) . "'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $row = $result->fetch_object();
            $_SESSION["login"] = $login;
            $_SESSION["id"] = $row->id;  
            $_SESSION["role"] = $row->role;
            header("Location: index.php");
        } else {
            ?>
            <div class="alert alert-danger" role="alert">
                Invalid login or password. <a href='login.php' class='alert-link'>Login again</a>.
            </div>
            <?php
        }
    } else {
        ?>
        <form class="form" method="post" name="login">
            <h1 class="login-title">Login</h1>
            <input type="text" class="form-control mb-2" name="login" placeholder="Login" autofocus="true"/>
            <input type="password" class="form-control mb-2" name="pass" placeholder="Hasło"/>
            <button type="submit" name="submit" class="btn btn-primary btn-block">login</button>
            <p class="mt-3">Don't have an account yet? <a href="registration.php">registration</a></p>
        </form>
        <?php
    }
    ?>
</div>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
