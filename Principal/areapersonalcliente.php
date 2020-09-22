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
                        <form action='#' method='POST'>
                            <li><a class="color3" ><input type='submit' class='btnsIndice' name='btnAdminCuenta' value="Administrar mi cuenta" /></a></li>
                            <li><a class="color3" ><input type='submit' class='btnsIndice' name='btnAdminAnuncios' value="Administrar mis anuncios" /></a></li>
                        </form>
                    </ul>
                </div>
            </nav>
            <!-- FIN MENU INFERIOR -->



            <main>
                <?php
                if (isset($_POST['btnModificarAnuncio'])) {

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

                    if (($_FILES['userfile']['error']) > 0 && ($_FILES['userfile']['error']) != 4) {
                        $errores[] = 'Error: Archivo error:' . $_FILES['userfile']['error'];
                    } else if (($_FILES['userfile']['error']) == 4) {
                        $imagen = "";
                    } else {
                        $tipo_archivo = $_FILES['userfile']['type'];
                        $extension = explode("/", $tipo_archivo);
                        $ext = $extension[1];

                        $hoy = getdate();
                        $fecha = ";" . $hoy['year'] . "-" . $hoy['mon'] . "-" . $hoy['mday'] . " " . $hoy['hours'] . "-" . $hoy['minutes'] . "-" . $hoy['seconds'];

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





                    if (!isset($errores)) {

                        modificarAnuncio($_POST['anuncioid'], $_POST['titulo'], $_POST['descripcion'], $imagen, $_POST['precio'], $_POST['telefono'], strtoupper($_POST['correo']), $_POST['scategoria']);
                    } else {
                        echo '<p style="color:red">Errores cometidos:</p>';
                        echo '<ul style="color:red">';
                        foreach ($errores as $e)
                            echo "<li>$e</li>";
                        echo '</ul>';
                    }
                }


                if (isset($_POST['btnCambiarUsuClave'])) {

                    $exUsuario = "/^([a-zA-Z0-9._@!-])+$/";
                    if (!preg_match($exUsuario, $_POST['usuario']))
                        $errores[] = 'Error: Formato incorrecto en el usuario. Ejemplo: Pepe92';

                    if (!isset($errores)) {

                        modificarUsuario($_POST['userid'], $_POST['usuario'], $_POST['clave']);
                        $_SESSION['user'] = $_POST['usuario'];
                    } else {
                        echo '<p style="color:red">Errores cometidos:</p>';
                        echo '<ul style="color:red">';
                        foreach ($errores as $e)
                            echo "<li>$e</li>";
                        echo '</ul>';
                    }
                }

                if (isset($_POST['btnCambiarDatosPersonales'])) {


                    $exSoloLetras = "/^([a-zA-Z])+$/";
                    $exNombre = "/^([ a-zA-Z])+$/";
                    $exSoloNumeros = "/^([0-9])+$/";
                    $exDNI = "/^([0-9]){8}$/";
                    $exCP = "/^([0-9]){5}$/";
                    $exTelf = "/^([0-9]){9}$/";
                    $exDireccion = "/^([ a-zA-Z0-9.,])+$/";
                    $exCorreo = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9])+[.]([a-zA-Z0-9\._-])*([a-zA-Z0-9])+$/";


                    if (!preg_match($exNombre, $_POST['nombre']))
                        $errores[] = 'Error: Formato incorrecto en el nombre. Ejemplo: Pepe';
                    if (!preg_match($exNombre, $_POST['apellido1']))
                        $errores[] = 'Error: Formato incorrecto en el primer apellido. Ejemplo: Carvajal';
                    if ($_POST['apellido2'] != "") {
                        if (!preg_match($exNombre, $_POST['apellido2']))
                            $errores[] = 'Error: Formato incorrecto en el segundo apellido. Ejemplo: Ramos';
                    }
                    if (!preg_match($exDNI, $_POST['numdni']))
                        $errores[] = 'Error: Formato incorrecto en el numero dni. Ejemplo: 98523578';
                    if (!preg_match($exSoloLetras, $_POST['letradni']))
                        $errores[] = 'Error: Formato incorrecto en la letra dni. Ejemplo: Z';
                    if (!preg_match($exDireccion, $_POST['direccion']))
                        $errores[] = 'Error: Formato incorrecto en la direccion. Ejemplo: Calle Rafael Nadal, Nº4';
                    if (!preg_match($exCP, $_POST['codpostal']))
                        $errores[] = 'Error: Formato incorrecto en el codigo postal. Ejemplo: 55400';
                    if (!preg_match($exTelf, $_POST['telefono']))
                        $errores[] = 'Error: Formato incorrecto en el telefono. Ejemplo: 687654789';
                    if (!preg_match($exCorreo, $_POST['correo']))
                        $errores[] = 'Error: Formato incorrecto en el correo. Ejemplo: Pepe@gmail.com';
                    $edad = calculaEdad($_POST['fec_nac']);
                    if ($edad < 18)
                        $errores[] = 'Error: Usted es menor de edad';
                    if (!isset($_POST['gender']))
                        $gender = "";
                    else
                        $gender = $_POST['gender'];

                    if (!isset($errores)) {

                        modificarCliente($_POST['userid'], strtoupper($_POST['nombre']), strtoupper($_POST['apellido1']), strtoupper($_POST['apellido2']), $_POST['numdni'], strtoupper($_POST['letradni']), $gender, $_POST['fec_nac'], strtoupper($_POST['direccion']), $_POST['codpostal'], $_POST['telefono'], strtoupper($_POST['correo']));
                    } else {
                        echo '<p style="color:red">Errores cometidos:</p>';
                        echo '<ul style="color:red">';
                        foreach ($errores as $e)
                            echo "<li>$e</li>";
                        echo '</ul>';
                    }
                }


                if (isset($_POST['btnRenovar'])) {

                    $result = consultaAnuncioSegunID($_POST['anuncioid']);

                    $row = mysqli_fetch_array($result);

                    if (calculaDiasFecha($row['fecha_fin']) > 3) {
                        $errores[] = 'Error: Solo puede renovar su anuncio si quedan menos de 3 dias para que este finalice.';
                    }

                    if (!isset($errores)) {

                        renovarAnuncio($_POST['anuncioid']);
                    } else {
                        echo '<p style="color:red">Errores cometidos:</p>';
                        echo '<ul style="color:red">';
                        foreach ($errores as $e)
                            echo "<li>$e</li>";
                        echo '</ul>';
                    }
                }

                if (isset($_POST['btnFinalizar'])) {
                    $result = consultaAnuncioSegunID($_POST['anuncioid']);
                    $row = mysqli_fetch_array($result);

                    finalizarAnuncio($_POST['anuncioid']);
                        
                    
                }

                if (isset($_POST['btnActualizar'])) {
                    $result = consultaAnuncioSegunID($_POST['anuncioid']);
                    $row = mysqli_fetch_array($result);
                    ?>
                
                <!-- INICIO MODIFICAR ANUNCIO -->
                    <section class="publicarAnuncio">
                        <h2>Modifique su anuncio</h2>

                        <form action='#' method='POST' enctype="multipart/form-data">
                            <?php echo "<input type='hidden' name='anuncioid' value='" . $_POST['anuncioid'] . "' />"; ?>
                            <table border="0">
                                <tr><td>Titulo:</td><td><input type="text" name="titulo" value="<?php echo $row['titulo']; ?>" maxlength="60" onkeypress="return esLetraEspacioNums(event)" required/></td></tr>
                                <tr><td>Descripcion:</td><td><textarea name="descripcion" cols="50" rows="10" maxlength="500" required/><?php echo $row['descripcion']; ?> </textarea></td></tr>
                                <tr><td>Imagen:</td><td><input type="file" name="userfile" /></td></tr>
                                <tr><td>Precio:</td><td><input type="text" name="precio" value="<?php echo $row['precio']; ?>" onkeypress="return esReal(event)" required/> € </td></tr>
                                <tr><td>Teléfono:</td><td><input type="text" name="telefono" value="<?php echo $row['telefono']; ?>" maxlength="9" onkeypress="return esNumero(event)" required/></td></tr>
                                <tr><td>Correo:</td><td><input type="email" name="correo" value="<?php echo $row['correo']; ?>" maxlength="60" onkeypress="return esCorreo(event)" required/></td></tr>
                                <tr><td>Categoria:</td><td><select name="scategoria">
                                            <?php
                                            $categoria = $row['categoria'];
                                            $result = consultaCategorias();

                                            for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                                                $row = mysqli_fetch_array($result);
                                                if ($categoria == $row['id']) {
                                                    echo "<option value='" . $row['id'] . "' selected>" . $row['categoria'] . "</option>";
                                                } else {
                                                    echo "<option value='" . $row['id'] . "'>" . $row['categoria'] . "</option>";
                                                }
                                            }
                                            ?>

                                        </select></td></tr>


                            </table>
                            <input type="submit" name="btnModificarAnuncio" value="Modificar anuncio"/>
                            <input type="reset" name="btnCancelar" value="Cancelar"/> <br><br><br>  
                        </form>
                    </section>
                    <!-- FIN MODIFICAR ANUNCIO -->
                    <?php
                }
                ?>

                <?php
                if (isset($_POST['btnAdminCuenta'])) {
                    ?>
                    <!-- INICIO ADMINISTRAR CUENTA -->
                    <section class="admCuenta">
                        <h2>Mi cuenta</h2>

                        <form action='#' method='POST'>
                            <?php
                            $id = obtenerIDUsuario($_SESSION['user']);
                            echo "<input type='hidden' name='userid' value='" . $id . "' />";
                            echo "<input type='hidden' name='btnAdminCuenta'/>";
                            ?>
                            <table border="0">

                                <tr><td>Usuario:</td><td><input type="text" name="usuario" value="<?php echo $_SESSION['user']; ?>" maxlength="60" onkeypress="return esUsuario(event)" required/></td></tr>
                                <tr><td>Clave:</td><td><input type="password" name="clave" maxlength="60" required/></td></tr>
                                <tr><td><input type="reset" name="btnCancelar" value="Cancelar"/></td></tr>
                            </table>
                            <input class="inpt" type="submit" name="btnCambiarUsuClave" value="Cambiar usuario"/>
                             <br><br> 
                        </form>
                        <hr>
                        <br><br>
                        <form action='#' method='POST'>
                            <?php
                            $id = obtenerIDUsuario($_SESSION['user']);
                            echo "<input type='hidden' name='userid' value='" . $id . "' />";
                            echo "<input type='hidden' name='btnAdminCuenta'/>";
                            $result = consultaCliente($id);


                            $row = mysqli_fetch_array($result);
                            $letra = substr($row['dni'], -1);
                            $numdni = substr($row['dni'], 0, -1);
                            ?>
                            <table border="0">
                                <tr><td>Nombre:</td><td><input type="text" name="nombre" maxlength="60" onkeypress="return esLetraEspacio(event)" value="<?php echo $row['nombre']; ?>" required/></td></tr>
                                <tr><td>Primer apellido:</td><td><input type="text" name="apellido1"  maxlength="60" onkeypress="return esLetraEspacio(event)" value="<?php echo $row['apellido1']; ?>" required/></td></tr>
                                <tr><td>Segundo apellido:</td><td><input type="text" name="apellido2"  onkeypress="return esLetraEspacio(event)" value="<?php echo $row['apellido2']; ?>" maxlength="60"/></td></tr>
                                <tr><td>DNI:</td><td><input type="text" name="numdni" maxlength="8" size="12" onkeypress="return esNumero(event)" value="<?php echo $numdni; ?>" required/> &nbsp;&nbsp;<input type="text" name="letradni" value="<?php echo $letra; ?>" maxlength="1" onkeypress="return esLetra(event)" size="1" required/></td></tr>
                                <tr><td>Sexo:</td><?php if ($row['sexo'] == 'M') { ?>
                                        <td>Masculino<input type="radio" name="gender" value="M" checked> <br>Femenino
                                            <input type="radio" name="gender" value="F"></td>
                                    <?php } else if ($row['sexo'] == 'F') { ?>
                                        <td>Masculino<input type="radio" name="gender" value="M"> <br>Femenino
                                            <input type="radio" name="gender" value="F" checked></td>
                                    <?php } else { ?>
                                        <td>Masculino<input type="radio" name="gender" value="M"> <br>Femenino
                                            <input type="radio" name="gender" value="F"></td>
                                    <?php } ?></tr>
                                <tr><td>Fecha de nacimiento:</td><td><input type="date" name="fec_nac" value="<?php echo $row['fecha_nac']; ?>" required/></td></tr>
                                <tr><td>Dirección:</td><td><input type="text" name="direccion" value="<?php echo $row['direccion']; ?>" maxlength="60" onkeypress="return esDireccion(event)" required/></td></tr>
                                <tr><td>Codigo postal:</td><td><input type="text" name="codpostal" value="<?php echo $row['codpostal']; ?>" maxlength="5" onkeypress="return esNumero(event)" required/></td></tr>
                                <tr><td>Teléfono:</td><td><input type="text" name="telefono" value="<?php echo $row['telefono']; ?>" maxlength="9" onkeypress="return esNumero(event)" required/></td></tr>
                                <tr><td>Correo:</td><td><input type="email" name="correo" value="<?php echo $row['correo']; ?>" maxlength="60" onkeypress="return esCorreo(event)" required/></td></tr>
                                <tr><td><input type="reset" name="btnCancelar" value="Cancelar"/></td></tr>
                                <tr><td></td></tr>
                            </table>
                            <input class="inpt" type="submit" name="btnCambiarDatosPersonales" value="Cambiar datos"/>
                            
                             <br><br><br>  
                        </form>


                    </section>
                    
                    <!-- FIN ADMINISTRAR CUENTA -->
                    <?php
                } else {
                    ?>
                    <section class="resultadoBusqueda">
                        <h2>Mis anuncios</h2>

                        <?php
                        $result = consultaAnunciosSegunUsuario($_SESSION['user']);
                        for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                            $row = mysqli_fetch_array($result);
                            ?>
                            <form action='#' method='POST'>
                                <?php echo "<input type='hidden' name='anuncioid' value='" . $row['id'] . "' />"; ?>
                                <table border="2">
                                    <tr><td colspan="4"><?php echo $row['titulo'] ?></td></tr>
                                    <tr><td colspan="4"><img src="./Material/fotos_anuncios/<?php echo $row['imagen'] ?>" alt="<?php echo $row['titulo'] ?>" width="300px" height="300px"/></td></tr>
                                    <tr><td colspan="4"><?php echo $row['descripcion'] ?></td> </tr>
                                    <tr><td colspan="4">Precio: <?php echo $row['precio'] ?> €</td></tr>
                                    <tr><td colspan="4">Correo: <?php echo $row['correo'] ?> </td></tr>
                                    <tr><td colspan="4"> Telefono: <?php echo $row['telefono'] ?> </td></tr>
                                    <tr><td colspan="2">Inicio: <?php echo $row['fecha_ini'] ?></td><td colspan="2">Fin: <?php echo $row['fecha_fin'] ?></td></tr>
                                    <tr><td><input type="submit" name="btnRenovar" id="btnRenovar" value="Renovar" /></td><td><input type="submit" name="btnActualizar" id="btnActualizar" value="Actualizar" /></td><td><input type="submit" name="btnFinalizar" id="btnFinalizar" value="Finalizar" /></td></tr>

                                </table>
                            </form>
                            <br><hr><br>
                            <?php
                        }
                        ?>


                    </section>

                    <?php
                }
                ?>
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

