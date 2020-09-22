<?php
include 'funciones.php';
if (isset($_POST['btnLogin'])) {
    if (comprobarUsuario($_POST['usuario'], $_POST['clave'])) {
        conectaBBDD($conn, $db);
        $saneadoUsuario = filter_var($_POST['usuario'], FILTER_SANITIZE_STRING);
        $saneadoUsuario = mysqli_real_escape_string($conn, trim($saneadoUsuario));
        mysqli_close($conn);
        $_SESSION['user'] = $saneadoUsuario;
        if (isset($_SESSION['history'])) {
            header("Location:" . $_SESSION['history']);
        } else {
            header("Location:index.php");
        }
    } else {
        $errorLogin = "<p style='color:red'>Usuario o contraseña incorrecta</p>";
    }
}
?>
<!DOCTYPE html>

<html lang="es">
    <head>
        <title>AdBai</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="./Material/img/adbailogo.png">
        

        
        <!-- ARCHIVO CSS --> 
        <link rel="stylesheet" href="css/estilo.css" type="text/css"/>
        
        <!--ARCHIVO JavaScript  --> 
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script src="js/funciones.js" type="text/javascript"></script>

        <!-- JavaScript Menu Inferior -->   
        <script type="text/javascript" src="js/megamenu.js"></script>
        <script>$(document).ready(function () {
                $(".megamenu").megamenu();
            });
        </script>
        <!-- FIN JavaScript Menu Inferior -->


    </head>
    <body>
        <!-- MENU SUPERIOR -->
        <header class="superior">

            <div class="logo">
                <a href="index.php"><img width="35%" height="35%"src="./Material/img/adbailogo.png" alt="adbailogo"/></a>
            </div>
            <div class="menuSuperior">
                <ul>

                    <li><a href="registro.php">Registrate</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </div>

            <div class="clear"></div>

        </header>
        <!-- FIN MENU SUPERIOR -->



        <!-- MENU INFERIOR -->
        <nav class="menuInferior">
            <div class="anchoGeneral">
                <ul class="megamenu skyblue">
                    <li><a class="color1" href="index.php">Home</a></li>
                    <li><a class="color2" href="#">Categorias</a>
                        <div class="megapanel">
                            <div class="col1">
                                <form action='index.php' method='GET'>
                                    <div><input type='submit' class='btnsIndice' name='btnBuscarCat' value="Todas las categorias" /></div>

                                    <?php
                                    $result = consultaCategorias();

                                    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                                        $row = mysqli_fetch_array($result);

                                        echo "<div class='h_nav'><input type='submit'  class='btnsIndice' name='btnBuscarCat' value='" . $row['categoria'] . "' /></div>";
                                    }
                                    ?>
                                </form>
                            </div>
                        </div>
                    </li>
                    <li><a class="color3" href="anunciar.php">Publicar Anuncio</a></li>
                </ul>
            </div>
        </nav>
        <!-- FIN MENU INFERIOR -->


        <main>
            <br>
            <br>
            <br>
            <div class="login">
                <div class="anchoGeneral">
                    <div class="col_1_of_login span_1_of_login">
                        <h4 class="title">Nuevo Usuario</h4>
                        <h5 class="sub_title">Registrate</h5>
                        <p>Registrate en el mejor portal de españa de compra y venta de articulos semi-nuevos y nuevos</p>
                        <div class="button1">
                            <a href="registro.php"><input type="submit" name="Submit" value="Registrar"></a>
                            
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="col_1_of_login span_1_of_login">
                        <div class="login-title">
                            <h4 class="title">Registro usuarios</h4>
                            <div class="comments-area">
                                <form action='#' method='POST'>
                                    <p>
                                        <label>Nombre usuario</label>
                                        <span>*</span>
                                        <?php
                                        if (isset($_COOKIE['user'])) {
                                            ?><input type="text" name="usuario" id="usuario" value="<?php echo $_COOKIE['user'] ?>" required/> <?php
                                        } else {
                                            ?><input type="text" name="usuario" id="usuario"  required/><?php } ?>
                                    </p>
                                    <p>
                                        <label>Contraseña</label>
                                        <span>*</span>
                                        <input type="password" name="clave" id="clave" required/>
                                    </p>
                                    <p>
                                        <?php
                                        if(isset($errorLogin)){
                                            echo $errorLogin;
                                        }
                                        ?>
                                        
                                        <button class="botonBuscar" type="submit" name="btnLogin" value="Login" >Login</button>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <br>
            <br>
            <br>
        </main>

        <!-- PIE DE PAGINA -->

        <footer>
            <div class="footer_info">
                <div class="anchoGeneral">
                    <p>AdBai, ¿no lo usas? ¡Anuncialo y véndelo!</p>
                </div>
            </div>
            <div class="footer-middle">
                <div class="anchoGeneral">
                    <div class="section group">
                        <div class="col_1_of_middle span_1_of_middle">
                            <ul >
                                <li><a href="#">Aviso legal</a></li>
                                <li><a href="#">Privacidad</a></li>
                                <li><a href="#">Contactar</a></li>
                            </ul>

                        </div>
                        <div class="col_1_of_middle span_1_of_middle">
                            <ul >
                                <li><a href="index.php">Home</a></li>
                                <li><a href="login.php">Login</a></li>
                                <li><a href="registro.php">Registrarte</a></li>

                            </ul>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="footer_info">
                <div class="anchoGeneral">
                    <p>Realizado por grupo 5</p>
                </div>
            </div>
        </footer>

        <!--FIN PIE DE PAGINA -->
    </body>
</html>
