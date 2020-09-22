<?php
include 'funciones.php';
if (logeado()) {
    if (esEmpleado($_SESSION['user'])) {
        header("Location:areapersonalempleado.php");
    } else {
        header("Location:areapersonalcliente.php");
    }
} else {
    session_start();
    $_SESSION['history'] = $_SERVER['PHP_SELF'];
    header("Location:login.php");
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html lang="es">
    <head>
        <title>AdBai</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="./Material/img/adbailogo.png">
        <link rel="stylesheet" href="css/estilo.css" type="text/css"/>
    </head>
    <body>

    </body>
</html>