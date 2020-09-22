<?php
include 'funciones.php';
function logout() {
    setcookie("user", $_SESSION['user'], time()+ 30*24*60*60);
    unset($_SESSION['user']);
    session_destroy();
    header("Location:index.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>AdBai</title>
        <link rel="shortcut icon" href="./Material/img/adbailogo.png">
    </head>
    <body>
        <?php logout(); ?>
    </body>
</html> 