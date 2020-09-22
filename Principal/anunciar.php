<!DOCTYPE html>

<html lang="es">
    <head>
        <title>AdBai</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="./Material/img/adbailogo.png">
        
        
        

        <!-- ARCHIVO PHP --> 
        <?php include 'funciones.php' ?>
        
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
        <?php
        if (logeado()) {
            ?>
            <!-- MENU SUPERIOR -->
            <header class="superior">

                <div class="logo">
                    <a href="index.php"><img width="35%" height="35%"src="./Material/img/adbailogo.png" alt="adbailogo"/></a>
                </div>
                <div class="menuSuperior">
                    <ul>

                        <li> <a href="perfil.php">Area Personal</a></li>
                        <li><a href="logout.php">Logout</a></li>


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
                <section class="publicarAnuncio">
                    <h2>Publicar anuncio</h2>
                    <?php
                    if (isset($_POST['btnSubirAnuncio'])) {

                        $exTitulo = "/^([ a-zA-Z0-9])+$/";

                        $exReal = "/^[0-9]+([.][0-9]+)?$/";

                        $exTelf = "/^([0-9]){9}$/";

                        $exCorreo = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9])+[.]([a-zA-Z0-9\._-])*([a-zA-Z0-9])+$/";

                        if (!preg_match($exTitulo, $_POST['titulo']))
                            $errores[] = 'Error: Formato incorrecto en el titulo. Ejemplo: Vendo Moto Yamaha';
                        if (!preg_match($exReal, $_POST['precio']))
                            $errores[] = 'Error: Formato incorrecto en el precio. Ejemplo: 23.48';
                        if (!preg_match($exTelf, $_POST['telefono']))
                            $errores[] = 'Error: Formato incorrecto en el telefono. Ejemplo: 687654789';
                        if (!preg_match($exCorreo, $_POST['correo']))
                            $errores[] = 'Error: Formato incorrecto en el correo. Ejemplo: Pepe@gmail.com';

                        if (!isset($errores)) {
                            if (($_FILES['userfile']['error']) > 0 && ($_FILES['userfile']['error']) != 4) {
                                $errores[] = 'Error: Archivo error:' . $_FILES['userfile']['error'];
                            } else if (($_FILES['userfile']['error']) == 4) {
                                $imagen = "";
                            } else {
                                $tipo_archivo = $_FILES['userfile']['type'];
                                $extension = explode("/", $tipo_archivo);
                                $ext = $extension[1];

                                $hoy = getdate();
                                $fecha = " " . $hoy['year'] . "-" . $hoy['mon'] . "-" . $hoy['mday'] . " " . $hoy['hours'] . "-" . $hoy['minutes'] . "-" . $hoy['seconds'];

                                $nombre_archivo = md5_file($_FILES['userfile']['tmp_name']) . "" . $fecha . "." . $ext;

                                $tipo_archivo = $_FILES['userfile']['type'];
                                $tamano_archivo = $_FILES['userfile']['size'];

                                if (!((strpos($tipo_archivo, "jpeg") || strpos($tipo_archivo, "jpg") || strpos($tipo_archivo, "png")) && ($tamano_archivo < "200000" ))) {
                                    $errores[] = "La extensión o el tamaño de los archivos no es correcta. <br><br><table><tr><td><li>Se permiten archivos .jpg o .jpeg o .png<br><li>se permiten archivos de 200Kb máximo.</td></tr></table>";
                                } else {

                                    if (copy($_FILES['userfile']['tmp_name'], ("./Material/fotos_anuncios/" . $nombre_archivo))) {
                                        $imagen = $nombre_archivo;
                                    } else {
                                        $errores[] = "Ocurrió algún error al subir el fichero. No pudo guardarse.";
                                    }
                                }
                            }
                        }



                        if (!isset($errores)) {

                            insertaAnuncio($_POST['titulo'], $_POST['descripcion'], $imagen, $_POST['precio'], $_POST['telefono'], strtoupper($_POST['correo']), $_SESSION['user'], $_POST['scategoria']);
                        } else {
                            echo '<p style="color:red">Errores cometidos:</p>';
                            echo '<ul style="color:red">';
                            foreach ($errores as $e)
                                echo "<li>$e</li>";
                            echo '</ul>';
                        }
                    }
                    ?>
                    <?php
                    $result = obtenerTelefonoCorreoCliente($_SESSION['user']);
                    $row = mysqli_fetch_array($result);
                    ?>
                    <form action='#' method='POST' enctype="multipart/form-data">
                        <table border="0">
                            <tr><td>Titulo:</td><td><input type="text" name="titulo" value="" maxlength="60" onkeypress="return esLetraEspacioNums(event)" required/></td></tr>
                            <tr><td>Descripcion:</td></tr>
                            <tr><td colspan="4"><textarea class="textarea"name="descripcion" cols="30" rows="5" value="" maxlength="500" required/> </textarea></td></tr>
                            <tr><td>Imagen:</td><td><input type="file" name="userfile" /></td></tr>
                            <tr><td>Precio:</td><td><input type="text" name="precio" value="" onkeypress="return esReal(event)" required/> € </td></tr>
                            <tr><td>Teléfono:</td><td><input type="text" name="telefono" value="<?php echo $row['telefono']; ?>" maxlength="9" onkeypress="return esNumero(event)" required/></td></tr>
                            <tr><td>Correo:</td><td><input type="email" name="correo" value="<?php echo $row['correo']; ?>" maxlength="60" onkeypress="return esCorreo(event)" required/></td></tr>
                            <tr><td>Categoria:</td></tr>
                            <tr> <td colspan="4"><select name="scategoria">
                                        <?php
                                        $result = consultaCategorias();

                                        for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                                            $row = mysqli_fetch_array($result);
                                            echo "<option value='" . $row['id'] . "'>" . $row['categoria'] . "</option>";
                                        }
                                        ?>

                                    </select></td></tr>


                        </table>
                        <input type="submit" name="btnSubirAnuncio" value="Subir anuncio"/>
                        <input type="reset" name="btnCancelar" value="Cancelar"/> <br><br><br>  
                    </form>
                </section>

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
            <?php
        } else {
            $_SESSION['history'] = $_SERVER['PHP_SELF'];
            header("Location:login.php");
        }
        ?>
    </body>
</html>