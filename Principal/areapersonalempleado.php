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
            if (esEmpleado($_SESSION['user'])) {
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
                                <?php
                                $id = obtenerIDUsuario($_SESSION['user']);
                                if (consultaDepartamentoSegunID($id) == 1 || consultaDepartamentoSegunID($id) == 3) {
                                    ?>
                                    <li><a class="color3" ><input type='submit' class='btnsIndice' name='btnAdminCategorias' value="Administrar categorias" /></a></li>
                                    <li><a class="color3" ><input type='submit' class='btnsIndice' name='btnAdminEmpleados' value="Administrar empleados" /></a></li>
                                    <?php
                                }
                                if (consultaDepartamentoSegunID($id) == 1 || consultaDepartamentoSegunID($id) == 2) {
                                    ?>
                                    <li><a class="color3" ><input type='submit' class='btnsIndice' name='btnAdminClientes' value="Administrar clientes" /></a></li>
                                    <li><a class="color3" ><input type='submit' class='btnsIndice' name='btnAdminTodosAnuncios' value="Administrar todos los anuncios" /></a></li>
                                    <?php
                                }
                                if (consultaDepartamentoSegunID($id) == 1) {
                                    ?>
                                    <li><a class="color3" ><input type='submit' class='btnsIndice' name='btnAdminDepartamentos' value="Administrar departamentos" /></a></li>
                                    <?php
                                }
                                ?>
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
                        $exSoloNumeros = "/^([0-9])+$/";
                        $exDNI = "/^([0-9]){8}$/";
                        $exCP = "/^([0-9]){5}$/";
                        $exTelf = "/^([0-9]){9}$/";
                        $exDireccion = "/^([ a-zA-Z0-9.,])+$/";
                        $exCorreo = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9])+[.]([a-zA-Z0-9\._-])*([a-zA-Z0-9])+$/";


                        if (!preg_match($exSoloLetras, $_POST['nombre']))
                            $errores[] = 'Error: Formato incorrecto en el nombre. Ejemplo: Pepe';
                        if (!preg_match($exSoloLetras, $_POST['apellido1']))
                            $errores[] = 'Error: Formato incorrecto en el primer apellido. Ejemplo: Carvajal';
                        if ($_POST['apellido2'] != "") {
                            if (!preg_match($exSoloLetras, $_POST['apellido2']))
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

                            modificarDatosEmpleado($_POST['userid'], strtoupper($_POST['nombre']), strtoupper($_POST['apellido1']), strtoupper($_POST['apellido2']), $_POST['numdni'], strtoupper($_POST['letradni']), $gender, $_POST['fec_nac'], strtoupper($_POST['direccion']), $_POST['codpostal'], $_POST['telefono'], strtoupper($_POST['correo']));
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
                        <section>
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

                        <?php
                    }

                    if (isset($_POST['btnInsertar2Categoria'])) {
                        $exCategoria = "/^([ a-zA-Z,])+$/";


                        if (!preg_match($exCategoria, $_POST['categoria']))
                            $errores[] = 'Error: Formato incorrecto en la categoria. Ejemplo: TV, Audio y Foto';


                        if (!isset($errores)) {

                            insertaCategoria($_POST['categoria']);
                        } else {
                            echo '<p style="color:red">Errores cometidos:</p>';
                            echo '<ul style="color:red">';
                            foreach ($errores as $e)
                                echo "<li>$e</li>";
                            echo '</ul>';
                        }
                    }

                    if (isset($_POST['btnInsertarCategoria'])) {
                        ?>

                        <section>
                            <h2>Inserte nueva categoria</h2>

                            <form action='#' method='POST'>
                                <input type="hidden" name="btnAdminCategorias" />  
                                <table border="0">
                                    <tr><td>Categoria:</td><td><input type="text" name="categoria" value="" maxlength="60" onkeypress="return esCategoria(event)" required/></td></tr>
                                </table>
                                <input type="submit" name="btnInsertar2Categoria" value="Insertar categoria"/>
                                <input type="reset" name="btnCancelar" value="Cancelar"/> <br><br><br>  
                            </form>
                        </section>


                        <?php
                    }

                    if (isset($_POST['btnModificar2Cats'])) {
                        $exCategoria = "/^([ a-zA-Z,])+$/";


                        if (!preg_match($exCategoria, $_POST['categoria']))
                            $errores[] = 'Error: Formato incorrecto en la categoria. Ejemplo: TV, Audio y Foto';


                        if (!isset($errores)) {

                            modificarCategoria($_POST['catid'], $_POST['categoria']);
                        } else {
                            echo '<p style="color:red">Errores cometidos:</p>';
                            echo '<ul style="color:red">';
                            foreach ($errores as $e)
                                echo "<li>$e</li>";
                            echo '</ul>';
                        }
                    }

                    if (isset($_POST['btnModificarCats'])) {
                        $categoria = consultaCategoriaSegunID($_POST['catid']);
                        ?>

                        <section>
                            <h2>Modifique categoria</h2>

                            <form action='#' method='POST'>
                                <input type="hidden" name="catid" value="<?php echo $_POST['catid']; ?>" />
                                <input type="hidden" name="btnAdminCategorias" />
                                <table border="0">
                                    <tr><td>Categoria:</td><td><input type="text" name="categoria" value="<?php echo $categoria; ?>" maxlength="60" onkeypress="return esCategoria(event)" required/></td></tr>
                                </table>
                                <input type="submit" name="btnModificar2Cats" value="Modificar categoria"/>
                                <input type="reset" name="btnCancelar" value="Cancelar"/> <br><br><br>  
                            </form>
                        </section>

                        <?php
                    }

                    if (isset($_POST['btnEliminarCats'])) {

                        eliminarCategoria($_POST['catid']);
                    }


                    if (isset($_POST['btnInsertar2Departamento'])) {
                        $exNombre = "/^([ a-zA-Z])+$/";


                        if (!preg_match($exNombre, $_POST['nombre']))
                            $errores[] = 'Error: Formato incorrecto en el nombre. Ejemplo: Tecnicos';


                        if (!isset($errores)) {

                            insertaDepartamento($_POST['nombre'], $_POST['descripcion']);
                        } else {
                            echo '<p style="color:red">Errores cometidos:</p>';
                            echo '<ul style="color:red">';
                            foreach ($errores as $e)
                                echo "<li>$e</li>";
                            echo '</ul>';
                        }
                    }

                    if (isset($_POST['btnInsertarDepartamento'])) {
                        ?>

                        <section>
                            <h2>Inserte nuevo departamento</h2>

                            <form action='#' method='POST'>
                                <input type="hidden" name="btnAdminDepartamentos" />  
                                <table border="0">
                                    <tr><td>Nombre:</td><td><input type="text" name="nombre" value="" maxlength="60" onkeypress="return esLetraEspacio(event)" required/></td></tr>
                                    <tr><td>Descripcion:</td><td><textarea name="descripcion" cols="50" rows="10" maxlength="500" required/></textarea></td></tr>                           
                                </table>
                                <input type="submit" name="btnInsertar2Departamento" value="Insertar departamento"/>
                                <input type="reset" name="btnCancelar" value="Cancelar"/> <br><br><br>  
                            </form>
                        </section>


                        <?php
                    }

                    if (isset($_POST['btnModificar2Departamento'])) {
                        $exNombre = "/^([ a-zA-Z])+$/";


                        if (!preg_match($exNombre, $_POST['nombre']))
                            $errores[] = 'Error: Formato incorrecto en el nombre. Ejemplo: Tecnicos';


                        if (!isset($errores)) {

                            modificarDepartamento($_POST['departid'], $_POST['nombre'], $_POST['descripcion']);
                        } else {
                            echo '<p style="color:red">Errores cometidos:</p>';
                            echo '<ul style="color:red">';
                            foreach ($errores as $e)
                                echo "<li>$e</li>";
                            echo '</ul>';
                        }
                    }

                    if (isset($_POST['btnModificarDepartamento'])) {
                        $result = consultaDepartamentoTodoSegunDeptNo($_POST['departid']);
                        $row = mysqli_fetch_array($result);
                        ?>

                        <section>
                            <h2>Modifique departamento</h2>

                            <form action='#' method='POST'>
                                <input type="hidden" name="departid" value="<?php echo $_POST['departid']; ?>" />
                                <input type="hidden" name="btnAdminDepartamentos" />
                                <table border="0">
                                    <tr><td>Nombre:</td><td><input type="text" name="nombre" value="<?php echo $row['nombre']; ?>" maxlength="60" onkeypress="return esCategoria(event)" required/></td></tr>
                                    <tr><td>Descripcion:</td><td><textarea name="descripcion" cols="50" rows="10" maxlength="500" required/><?php echo $row['descripcion']; ?></textarea></td></tr>                                                 
                                </table>
                                <input type="submit" name="btnModificar2Departamento" value="Modificar departamento"/>
                                <input type="reset" name="btnCancelar" value="Cancelar"/> <br><br><br>  
                            </form>
                        </section>

                        <?php
                    }

                    if (isset($_POST['btnEliminarDepartamento'])) {

                        eliminarDepartamento($_POST['departid']);
                    }


                    if (isset($_POST['btnInsertar2Empleado'])) {
                        $exUsuario = "/^([a-zA-Z0-9._@!-])+$/";
                        $exNombre = "/^([ a-zA-Z])+$/";
                        $exSoloLetras = "/^([a-zA-Z])+$/";
                        $exSoloNumeros = "/^([0-9])+$/";
                        $exDNI = "/^([0-9]){8}$/";
                        $exCP = "/^([0-9]){5}$/";
                        $exTelf = "/^([0-9]){9}$/";
                        $exDireccion = "/^([ a-zA-Z0-9.,])+$/";
                        $exCorreo = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9])+[.]([a-zA-Z0-9\._-])*([a-zA-Z0-9])+$/";
                        $exReal = "/^[0-9]+([.][0-9]+)?$/";

                        if (!preg_match($exUsuario, $_POST['usuario']))
                            $errores[] = 'Error: Formato incorrecto en el usuario. Ejemplo: Pepe92';
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
                            $errores[] = 'Error: El empleado introducido es menor de edad';
                        if (!isset($_POST['gender']))
                            $gender = "";
                        else
                            $gender = $_POST['gender'];
                        if (!preg_match($exReal, $_POST['salario']))
                            $errores[] = 'Error: Formato incorrecto en el precio. Ejemplo: 23.48';

                        if (!isset($errores)) {

                            insertaUsuarioEmpleado($_POST['usuario'], $_POST['clave'], strtoupper($_POST['nombre']), strtoupper($_POST['apellido1']), strtoupper($_POST['apellido2']), $_POST['numdni'], strtoupper($_POST['letradni']), $gender, $_POST['fec_nac'], strtoupper($_POST['direccion']), $_POST['codpostal'], $_POST['telefono'], strtoupper($_POST['correo']), $_POST['salario'], $_POST['sdepartamento']);
                        } else {
                            echo '<p style="color:red">Errores cometidos:</p>';
                            echo '<ul style="color:red">';
                            foreach ($errores as $e)
                                echo "<li>$e</li>";
                            echo '</ul>';
                        }
                    }

                    if (isset($_POST['btnInsertarEmpleado'])) {
                        ?>

                        <section>
                            <h2>Inserte nuevo empleado</h2>
                            <table border="0">
                                <form action='#' method='POST'>
                                    <input type="hidden" name="btnAdminEmpleados" />  
                                    <tr><td>Usuario:</td><td><input type="text" name="usuario" value="" maxlength="60" onkeypress="return esUsuario(event)" required/></td></tr>
                                    <tr><td>Clave:</td><td><input type="password" name="clave" maxlength="60" required/></td></tr>
                            </table>
                            <hr>
                            <table border="0">
                                <tr><td>Nombre:</td><td><input type="text" name="nombre" value="" maxlength="60" onkeypress="return esLetraEspacio(event)" required/></td></tr>
                                <tr><td>Primer apellido:</td><td><input type="text" name="apellido1" value="" maxlength="60" onkeypress="return esLetraEspacio(event)" required/></td></tr>
                                <tr><td>Segundo apellido:</td><td><input type="text" name="apellido2" value="" onkeypress="return esLetraEspacio(event)" maxlength="60"/></td></tr>
                                <tr><td>DNI:</td><td><input type="text" name="numdni" value="" maxlength="8" size="12" onkeypress="return esNumero(event)" required/> &nbsp;&nbsp;<input type="text" name="letradni" value="" maxlength="1" onkeypress="return esLetra(event)" size="1" required/></td></tr>
                                <tr><td>Sexo:</td><td>Masculino<input type="radio" name="gender" value="M"> <br>Femenino
                                        <input type="radio" name="gender" value="F"></td></tr>
                                <tr><td>Fecha de nacimiento:</td><td><input type="date" name="fec_nac" value="" required/></td></tr>
                                <tr><td>Dirección:</td><td><input type="text" name="direccion" value="" maxlength="60" onkeypress="return esDireccion(event)" required/></td></tr>
                                <tr><td>Codigo postal:</td><td><input type="text" name="codpostal" value="" maxlength="5" onkeypress="return esNumero(event)" required/></td></tr>
                                <tr><td>Teléfono:</td><td><input type="text" name="telefono" value="" maxlength="9" onkeypress="return esNumero(event)" required/></td></tr>
                                <tr><td>Correo:</td><td><input type="email" name="correo" value="" maxlength="60" onkeypress="return esCorreo(event)" required/></td></tr>
                                <tr><td>Salario:</td><td><input type="text" name="salario" value="" onkeypress="return esReal(event)" required/> € </td></tr>
                                <tr><td>Departamento:</td><td><select name="sdepartamento">
                                            <?php
                                            $result = consultaDepartamentos();
                                            $id = obtenerIDUsuario($_SESSION['user']);
                                            $deptuser = consultaDepartamentoSegunID($id);
                                            for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                                                $row = mysqli_fetch_array($result);
                                                if ($row['dept_no'] == 1 && $deptuser != 1) {
                                                    
                                                } else {
                                                    echo "<option value='" . $row['dept_no'] . "'>" . $row['nombre'] . "</option>";
                                                }
                                            }
                                            ?>

                                        </select></td></tr>
                            </table>
                            <input type="submit" name="btnInsertar2Empleado" value="Insertar empleado"/>
                            <input type="reset" name="btnCancelar" value="Cancelar"/> <br><br><br>  
                            </form>
                        </section>


                        <?php
                    }

                    if (isset($_POST['btnModificar2Empleado'])) {

                        $exSoloLetras = "/^([a-zA-Z])+$/";
                        $exNombre = "/^([ a-zA-Z])+$/";
                        $exSoloNumeros = "/^([0-9])+$/";
                        $exDNI = "/^([0-9]){8}$/";
                        $exCP = "/^([0-9]){5}$/";
                        $exTelf = "/^([0-9]){9}$/";
                        $exDireccion = "/^([ a-zA-Z0-9.,])+$/";
                        $exCorreo = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9])+[.]([a-zA-Z0-9\._-])*([a-zA-Z0-9])+$/";
                        $exReal = "/^[0-9]+([.][0-9]+)?$/";


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
                            $errores[] = 'Error: El empleado introducido es menor de edad';
                        if (!isset($_POST['gender']))
                            $gender = "";
                        else
                            $gender = $_POST['gender'];
                        if (!preg_match($exReal, $_POST['salario']))
                            $errores[] = 'Error: Formato incorrecto en el precio. Ejemplo: 23.48';

                        if (!isset($errores)) {

                            modificarEmpleado($_POST['empid'], strtoupper($_POST['nombre']), strtoupper($_POST['apellido1']), strtoupper($_POST['apellido2']), $_POST['numdni'], strtoupper($_POST['letradni']), $gender, $_POST['fec_nac'], strtoupper($_POST['direccion']), $_POST['codpostal'], $_POST['telefono'], strtoupper($_POST['correo']), $_POST['salario'], $_POST['sdepartamento']);
                        } else {
                            echo '<p style="color:red">Errores cometidos:</p>';
                            echo '<ul style="color:red">';
                            foreach ($errores as $e)
                                echo "<li>$e</li>";
                            echo '</ul>';
                        }
                    }

                    if (isset($_POST['btnModificarEmpleado'])) {
                        $result = consultaEmpleado($_POST['empid']);
                        $row = mysqli_fetch_array($result);
                        $letra = substr($row['dni'], -1);
                        $numdni = substr($row['dni'], 0, -1);
                        ?>

                        <section>
                            <h2>Modifique empleado</h2>

                            <form action='#' method='POST'>
                                <input type="hidden" name="empid" value="<?php echo $_POST['empid']; ?>" />
                                <input type="hidden" name="btnAdminEmpleados" />
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
                                    <tr><td>Salario:</td><td><input type="text" name="salario" value="<?php echo $row['salario']; ?>" onkeypress="return esReal(event)" required/> € </td></tr>
                                    <tr><td>Departamento:</td><td><select name="sdepartamento">
                                                <?php
                                                $departamento = $row['departamento'];
                                                $result = consultaDepartamentos();
                                                $id = obtenerIDUsuario($_SESSION['user']);
                                                $deptuser = consultaDepartamentoSegunID($id);
                                                for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                                                    $row = mysqli_fetch_array($result);
                                                    if ($departamento == $row['dept_no']) {
                                                        echo "<option value='" . $row['dept_no'] . "' selected>" . $row['nombre'] . "</option>";
                                                    } else if ($row['dept_no'] == 1 && $deptuser != 1) {
                                                        
                                                    } else {
                                                        echo "<option value='" . $row['dept_no'] . "'>" . $row['nombre'] . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td></tr>
                                </table>
                                <input type="submit" name="btnModificar2Empleado" value="Modificar empleado"/>
                                <input type="reset" name="btnCancelar" value="Cancelar"/> <br><br><br>  
                            </form>
                        </section>

                        <?php
                    }

                    if (isset($_POST['btnEliminarEmpleado'])) {

                        if (eliminarEmpleado($_POST['empid'])) {
                            eliminarUsuario($_POST['empid']);
                        }
                    }


                    if (isset($_POST['btnInsertar2Cliente'])) {
                        $exUsuario = "/^([a-zA-Z0-9._@!-])+$/";
                        $exSoloLetras = "/^([a-zA-Z])+$/";
                        $exSoloNumeros = "/^([0-9])+$/";
                        $exDNI = "/^([0-9]){8}$/";
                        $exCP = "/^([0-9]){5}$/";
                        $exTelf = "/^([0-9]){9}$/";
                        $exDireccion = "/^([ a-zA-Z0-9.,])+$/";
                        $exCorreo = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9])+[.]([a-zA-Z0-9\._-])*([a-zA-Z0-9])+$/";

                        if (!preg_match($exUsuario, $_POST['usuario']))
                            $errores[] = 'Error: Formato incorrecto en el usuario. Ejemplo: Pepe92';
                        if (!preg_match($exSoloLetras, $_POST['nombre']))
                            $errores[] = 'Error: Formato incorrecto en el nombre. Ejemplo: Pepe';
                        if (!preg_match($exSoloLetras, $_POST['apellido1']))
                            $errores[] = 'Error: Formato incorrecto en el primer apellido. Ejemplo: Carvajal';
                        if ($_POST['apellido2'] != "") {
                            if (!preg_match($exSoloLetras, $_POST['apellido2']))
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

                            insertaUsuarioCliente($_POST['usuario'], $_POST['clave'], strtoupper($_POST['nombre']), strtoupper($_POST['apellido1']), strtoupper($_POST['apellido2']), $_POST['numdni'], strtoupper($_POST['letradni']), $gender, $_POST['fec_nac'], strtoupper($_POST['direccion']), $_POST['codpostal'], $_POST['telefono'], strtoupper($_POST['correo']));
                        } else {
                            echo '<p style="color:red">Errores cometidos:</p>';
                            echo '<ul style="color:red">';
                            foreach ($errores as $e)
                                echo "<li>$e</li>";
                            echo '</ul>';
                        }
                    }

                    if (isset($_POST['btnInsertarCliente'])) {
                        ?>

                        <section>
                            <h2>Inserte nuevo cliente</h2>
                            <table border="0">
                                <form action='#' method='POST'>
                                    <input type="hidden" name="btnAdminClientes" />  
                                    <tr><td>Usuario:</td><td><input type="text" name="usuario" value="" maxlength="60" onkeypress="return esUsuario(event)" required/></td></tr>
                                    <tr><td>Clave:</td><td><input type="password" name="clave" maxlength="60" required/></td></tr>
                            </table>
                            <hr>
                            <table border="0">
                                <tr><td>Nombre:</td><td><input type="text" name="nombre" value="" maxlength="60" onkeypress="return esLetraEspacio(event)" required/></td></tr>
                                <tr><td>Primer apellido:</td><td><input type="text" name="apellido1" value="" maxlength="60" onkeypress="return esLetraEspacio(event)" required/></td></tr>
                                <tr><td>Segundo apellido:</td><td><input type="text" name="apellido2" value="" onkeypress="return esLetraEspacio(event)" maxlength="60"/></td></tr>
                                <tr><td>DNI:</td><td><input type="text" name="numdni" value="" maxlength="8" size="12" onkeypress="return esNumero(event)" required/> &nbsp;&nbsp;<input type="text" name="letradni" value="" maxlength="1" onkeypress="return esLetra(event)" size="1" required/></td></tr>
                                <tr><td>Sexo:</td><td>Masculino<input type="radio" name="gender" value="M"> <br>Femenino
                                        <input type="radio" name="gender" value="F"></td></tr>
                                <tr><td>Fecha de nacimiento:</td><td><input type="date" name="fec_nac" value="" required/></td></tr>
                                <tr><td>Dirección:</td><td><input type="text" name="direccion" value="" maxlength="60" onkeypress="return esDireccion(event)" required/></td></tr>
                                <tr><td>Codigo postal:</td><td><input type="text" name="codpostal" value="" maxlength="5" onkeypress="return esNumero(event)" required/></td></tr>
                                <tr><td>Teléfono:</td><td><input type="text" name="telefono" value="" maxlength="9" onkeypress="return esNumero(event)" required/></td></tr>
                                <tr><td>Correo:</td><td><input type="email" name="correo" value="" maxlength="60" onkeypress="return esCorreo(event)" required/></td></tr>

                            </table>
                            <input type="submit" name="btnInsertar2Cliente" value="Insertar cliente"/>
                            <input type="reset" name="btnCancelar" value="Cancelar"/> <br><br><br>  
                            </form>
                        </section>


                        <?php
                    }

                    if (isset($_POST['btnModificar2Cliente'])) {

                        $exSoloLetras = "/^([a-zA-Z])+$/";
                        $exSoloNumeros = "/^([0-9])+$/";
                        $exDNI = "/^([0-9]){8}$/";
                        $exCP = "/^([0-9]){5}$/";
                        $exTelf = "/^([0-9]){9}$/";
                        $exDireccion = "/^([ a-zA-Z0-9.,])+$/";
                        $exCorreo = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9])+[.]([a-zA-Z0-9\._-])*([a-zA-Z0-9])+$/";



                        if (!preg_match($exSoloLetras, $_POST['nombre']))
                            $errores[] = 'Error: Formato incorrecto en el nombre. Ejemplo: Pepe';
                        if (!preg_match($exSoloLetras, $_POST['apellido1']))
                            $errores[] = 'Error: Formato incorrecto en el primer apellido. Ejemplo: Carvajal';
                        if ($_POST['apellido2'] != "") {
                            if (!preg_match($exSoloLetras, $_POST['apellido2']))
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
                            $errores[] = 'Error: El empleado introducido es menor de edad';
                        if (!isset($_POST['gender']))
                            $gender = "";
                        else
                            $gender = $_POST['gender'];


                        if (!isset($errores)) {

                            modificarCliente($_POST['clienteid'], strtoupper($_POST['nombre']), strtoupper($_POST['apellido1']), strtoupper($_POST['apellido2']), $_POST['numdni'], strtoupper($_POST['letradni']), $gender, $_POST['fec_nac'], strtoupper($_POST['direccion']), $_POST['codpostal'], $_POST['telefono'], strtoupper($_POST['correo']));
                        } else {
                            echo '<p style="color:red">Errores cometidos:</p>';
                            echo '<ul style="color:red">';
                            foreach ($errores as $e)
                                echo "<li>$e</li>";
                            echo '</ul>';
                        }
                    }

                    if (isset($_POST['btnModificarCliente'])) {
                        $result = consultaCliente($_POST['clienteid']);
                        $row = mysqli_fetch_array($result);
                        $letra = substr($row['dni'], -1);
                        $numdni = substr($row['dni'], 0, -1);
                        ?>

                        <section>
                            <h2>Modifique cliente</h2>

                            <form action='#' method='POST'>
                                <input type="hidden" name="clienteid" value="<?php echo $_POST['clienteid']; ?>" />
                                <input type="hidden" name="btnAdminClientes" />
                                <table border="0">
                                    <tr><td>Nombre:</td><td><input type="text" name="nombre" maxlength="60" onkeypress="return esLetra(event)" value="<?php echo $row['nombre']; ?>" required/></td></tr>
                                    <tr><td>Primer apellido:</td><td><input type="text" name="apellido1"  maxlength="60" onkeypress="return esLetra(event)" value="<?php echo $row['apellido1']; ?>" required/></td></tr>
                                    <tr><td>Segundo apellido:</td><td><input type="text" name="apellido2"  onkeypress="return esLetra(event)" value="<?php echo $row['apellido2']; ?>" maxlength="60"/></td></tr>
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

                                </table>
                                <input type="submit" name="btnModificar2Cliente" value="Modificar cliente"/>
                                <input type="reset" name="btnCancelar" value="Cancelar"/> <br><br><br>  
                            </form>
                        </section>

                        <?php
                    }

                    if (isset($_POST['btnEliminarCliente'])) {

                        if (eliminarCliente($_POST['clienteid'])) {
                            eliminarUsuario($_POST['clienteid']);
                        }
                    }

                    if (isset($_POST['btnInsertar2TAnuncio'])) {
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

                            insertaAnuncio($_POST['titulo'], $_POST['descripcion'], $imagen, $_POST['precio'], $_POST['telefono'], strtoupper($_POST['correo']), $_POST['susuario'], $_POST['scategoria']);
                        } else {
                            echo '<p style="color:red">Errores cometidos:</p>';
                            echo '<ul style="color:red">';
                            foreach ($errores as $e)
                                echo "<li>$e</li>";
                            echo '</ul>';
                        }
                    }

                    if (isset($_POST['btnInsertarTAnuncio'])) {
                        ?>

                        <section>
                            <h2>Inserte nuevo anuncio</h2>
                            <form action='#' method='POST' enctype="multipart/form-data">
                                <input type="hidden" name="btnAdminTodosAnuncios" /> 
                                <table border="0">
                                    <tr><td>Titulo:</td><td><input type="text" name="titulo" value="" maxlength="60" onkeypress="return esLetraEspacioNums(event)" required/></td></tr>
                                    <tr><td>Descripcion:</td><td><textarea name="descripcion" cols="50" rows="10" value="" maxlength="500" required/> </textarea></td></tr>
                                    <tr><td>Imagen:</td><td><input type="file" name="userfile" /></td></tr>
                                    <tr><td>Precio:</td><td><input type="text" name="precio" value="" onkeypress="return esReal(event)" required/> € </td></tr>
                                    <tr><td>Teléfono:</td><td><input type="text" name="telefono" value="" maxlength="9" onkeypress="return esNumero(event)" required/></td></tr>
                                    <tr><td>Correo:</td><td><input type="email" name="correo" value="" maxlength="60" onkeypress="return esCorreo(event)" required/></td></tr>
                                    <tr><td>Categoria:</td><td><select name="scategoria">
                                                <?php
                                                $result = consultaCategorias();

                                                for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                                                    $row = mysqli_fetch_array($result);
                                                    echo "<option value='" . $row['id'] . "'>" . $row['categoria'] . "</option>";
                                                }
                                                ?>

                                            </select></td></tr>
                                    <tr><td>Usuario asociado:</td><td><select name="susuario">
                                                <?php
                                                $result = consultaUsuarios();

                                                for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                                                    $row = mysqli_fetch_array($result);
                                                    echo "<option value='" . $row['usuario'] . "'>" . $row['usuario'] . "</option>";
                                                }
                                                ?>

                                            </select></td></tr>


                                </table>
                                <input type="submit" name="btnInsertar2TAnuncio" value="Insertar anuncio"/>
                                <input type="reset" name="btnCancelar" value="Cancelar"/> <br><br><br>  
                            </form>
                        </section>


                        <?php
                    }

                    if (isset($_POST['btnModificar2TAnuncio'])) {

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

                            modificarAnuncioUsu($_POST['anuncioid'], $_POST['titulo'], $_POST['descripcion'], $imagen, $_POST['precio'], $_POST['telefono'], strtoupper($_POST['correo']), $_POST['scategoria'], $_POST['susuario']);
                        } else {
                            echo '<p style="color:red">Errores cometidos:</p>';
                            echo '<ul style="color:red">';
                            foreach ($errores as $e)
                                echo "<li>$e</li>";
                            echo '</ul>';
                        }
                    }

                    if (isset($_POST['btnModificarTAnuncio'])) {
                        $result = consultaAnuncioSegunID($_POST['anuncioid']);
                        $row = mysqli_fetch_array($result);
                        ?>

                        <section>
                            <h2>Modifique anuncio</h2>

                            <form action='#' method='POST' enctype="multipart/form-data">
                                <input type="hidden" name="anuncioid" value="<?php echo $_POST['anuncioid']; ?>" />
                                <input type="hidden" name="btnAdminTodosAnuncios" />
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
                                                $userid = $row['userid'];
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
                                    <tr><td>Usuario asociado:</td><td><select name="susuario">
                                                <?php
                                                $result = consultaUsuarios();

                                                for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                                                    $row = mysqli_fetch_array($result);
                                                    if ($userid == $row['id']) {
                                                        echo "<option value='" . $row['id'] . "' selected>" . $row['usuario'] . "</option>";
                                                    } else {
                                                        echo "<option value='" . $row['id'] . "'>" . $row['usuario'] . "</option>";
                                                    }
                                                }
                                                ?>

                                            </select></td></tr>


                                </table>
                                <input type="submit" name="btnModificar2TAnuncio" value="Modificar anuncio"/>
                                <input type="reset" name="btnCancelar" value="Cancelar"/> <br><br><br>  
                            </form>
                        </section>

                        <?php
                    }

                    if (isset($_POST['btnEliminarTAnuncio'])) {

                        finalizarAnuncio($_POST['anuncioid']);
                    }
                    ?>

                    <?php
                    if (isset($_POST['btnAdminCuenta'])) {
                        ?>
                        <section class="publicarAnuncio">
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
                                </table>
                                <input type="submit" name="btnCambiarUsuClave" value="Cambiar usuario y clave"/>
                                <input type="reset" name="btnCancelar" value="Cancelar"/> <br><br> 
                            </form>
                            <hr>
                            <br><br>
                            <form action='#' method='POST'>
                                <?php
                                $id = obtenerIDUsuario($_SESSION['user']);
                                echo "<input type='hidden' name='userid' value='" . $id . "' />";
                                echo "<input type='hidden' name='btnAdminCuenta'/>";
                                $result = consultaEmpleado($id);


                                $row = mysqli_fetch_array($result);
                                $letra = substr($row['dni'], -1);
                                $numdni = substr($row['dni'], 0, -1);
                                ?>
                                <table border="0">
                                    <tr><td>Nombre:</td><td><input type="text" name="nombre" maxlength="60" onkeypress="return esLetra(event)" value="<?php echo $row['nombre']; ?>" required/></td></tr>
                                    <tr><td>Primer apellido:</td><td><input type="text" name="apellido1"  maxlength="60" onkeypress="return esLetra(event)" value="<?php echo $row['apellido1']; ?>" required/></td></tr>
                                    <tr><td>Segundo apellido:</td><td><input type="text" name="apellido2"  onkeypress="return esLetra(event)" value="<?php echo $row['apellido2']; ?>" maxlength="60"/></td></tr>
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

                                </table>
                                <input type="submit" name="btnCambiarDatosPersonales" value="Cambiar datos personales"/>
                                <input type="reset" name="btnCancelar" value="Cancelar"/> <br><br><br>  
                            </form>


                        </section>
                        <?php
                    } else if (isset($_POST['btnAdminCategorias'])) {
                        ?>
                        <section>
                            <h2>Administrar Categorias</h2>

                            <form action='#' method='POST'>
                                <input type="hidden" name="btnAdminCategorias" />                               
                                <input type="submit" name="btnInsertarCategoria" value="Insertar nueva categoria"/>
                            </form>
                            <hr>

                            <table border="0" class="categoriaTabla">
                                <tr><th>Id</th><th>Categoria</th></tr>
                                <?php
                                $result = consultaCategorias();
                                for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                                    $row = mysqli_fetch_array($result);
                                    echo "<form action='#' method='POST'>";
                                    echo "<tr><td>" . $row['id'] . "</td><td>" . $row['categoria'] . "</td><td><input type='submit' name='btnModificarCats' value='Modificar'/></td><td><input type='submit' name='btnEliminarCats' value='Eliminar'/></td></tr>";
                                    echo "<input type='hidden' name='catid' value='" . $row['id'] . "' />";
                                    echo '<input type="hidden" name="btnAdminCategorias" />';
                                    echo "</form>";
                                }
                                ?>
                            </table>

                            <hr>
                            <br><br>



                        </section>
                        <?php
                    } else if (isset($_POST['btnAdminEmpleados'])) {
                        ?>
                        <section>
                            <h2>Administrar Empleados</h2>

                            <form action='#' method='POST'>
                                <input type="hidden" name="btnAdminEmpleados" />
                                <input type="submit" name="btnInsertarEmpleado" value="Insertar nuevo empleado"/>
                            </form>
                            <hr>

                            <table border="0">
                                <tr><th>Id</th><th>DNI</th><th>Nombre</th><th>Primer Apellido</th><th>Segundo Apellido</th><th>Sexo</th><th>Fecha nacimiento</th><th>Direccion</th><th>Codigo postal</th><th>Telefono</th><th>Correo</th><th>Salario</th><th>Departamento</th></tr>
                                <?php
                                $result = consultaEmpleados();
                                for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                                    $id = obtenerIDUsuario($_SESSION['user']);
                                    $deptuser = consultaDepartamentoSegunID($id);
                                    $row = mysqli_fetch_array($result);

                                    echo "<form action='#' method='POST'>";
                                    echo "<tr><td>" . $row['id'] . "</td><td>" . $row['dni'] . "</td><td>" . $row['nombre'] . "</td><td>" . $row['apellido1'] . "</td><td>" . $row['apellido2'] . "</td><td>" . $row['sexo'] . "</td><td>" . $row['fecha_nac'] . "</td><td>" . $row['direccion'] . "</td><td>" . $row['codpostal'] . "</td><td>" . $row['telefono'] . "</td><td>" . $row['correo'] . "</td><td>" . $row['salario'] . "</td><td>" . consultaDepartamentoSegunDeptNo($row['departamento']) . "</td>";
                                    if ($row['departamento'] == 1 && $deptuser != 1 || $row['id'] == $id && $deptuser != 1) {
                                        echo "</tr>";
                                    } else {
                                        echo "<td><input type='submit' name='btnModificarEmpleado' value='Modificar'/></td><td><input type='submit' name='btnEliminarEmpleado' value='Eliminar'/></td></tr>";
                                    }
                                    echo "<input type='hidden' name='empid' value='" . $row['id'] . "' />";
                                    echo '<input type="hidden" name="btnAdminEmpleados" />';
                                    echo "</form>";
                                }
                                ?>
                            </table>

                            <hr>
                            <br><br>

                        </section>
                        <?php
                    } else if (isset($_POST['btnAdminClientes'])) {
                        ?>
                        <section>
                            <h2>Administrar Clientes</h2>

                            <form action='#' method='POST'>
                                <input type="hidden" name="btnAdminClientes" />
                                <input type="submit" name="btnInsertarCliente" value="Insertar nuevo cliente"/>
                            </form>
                            <hr>

                            <table border="0" class="usuarios">
                                <tr><th>Id</th><th>Usuario</th><th>DNI</th><th>Nombre</th><th>Primer Apellido</th><th>Segundo Apellido</th><th>Sexo</th><th>Fecha nacimiento</th><th>Direccion</th><th>Codigo postal</th><th>Telefono</th><th>Correo</th></th></tr>
                                <?php
                                $result = consultaClientes();
                                for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                                    $row = mysqli_fetch_array($result);
                                    echo "<form action='#' method='POST'>";
                                    echo "<tr><td>" . $row['id'] . "</td><td>" . consultaUsuarioSegunID($row['id']) . "</td><td>" . $row['dni'] . "</td><td>" . $row['nombre'] . "</td><td>" . $row['apellido1'] . "</td><td>" . $row['apellido2'] . "</td><td>" . $row['sexo'] . "</td><td>" . $row['fecha_nac'] . "</td><td>" . $row['direccion'] . "</td><td>" . $row['codpostal'] . "</td><td>" . $row['telefono'] . "</td><td>" . $row['correo'] . "</td><td><input type='submit' name='btnModificarCliente' value='Modificar'/></td><td><input type='submit' name='btnEliminarCliente' value='Eliminar'/></td></tr>";
                                    echo "<input type='hidden' name='clienteid' value='" . $row['id'] . "' />";
                                    echo '<input type="hidden" name="btnAdminClientes" />';
                                    echo "</form>";
                                }
                                ?>
                            </table>

                            <hr>
                            <br><br>

                        </section>
                        <?php
                    } else if (isset($_POST['btnAdminTodosAnuncios'])) {
                        ?>
                        <section>
                            <h2>Administrar Anuncios</h2>

                            <form action='#' method='POST'>
                                <input type="hidden" name="btnAdminTodosAnuncios" />
                                <input type="submit" name="btnInsertarTAnuncio" value="Insertar anuncio"/>
                            </form>
                            <hr>

                            <table border="0">
                                <tr><th>Id</th><th>Titulo</th><th>Descripcion</th><th>Imagen</th><th>Precio</th><th>Telefono</th><th>Correo</th><th>Fecha inicio</th><th>Fecha fin</th><th>Renovaciones</th><th>Usuario</th><th>Categoria</th></th></tr>
                                <?php
                                $result = consultaAnuncios();
                                for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                                    $row = mysqli_fetch_array($result);
                                    if ($row['imagen'] != "") {
                                        $imagen = 'SI';
                                    } else {
                                        $imagen = 'NO';
                                    }
                                    echo "<form action='#' method='POST'>";
                                    echo "<tr><td>" . $row['id'] . "</td><td>" . $row['titulo'] . "</td><td>" . $row['descripcion'] . "</td><td>" . $imagen . "</td><td>" . $row['precio'] . "</td><td>" . $row['telefono'] . "</td><td>" . $row['correo'] . "</td><td>" . $row['fecha_ini'] . "</td><td>" . $row['fecha_fin'] . "</td><td>" . $row['renovaciones'] . "</td><td>" . consultaUsuarioSegunID($row['userid']) . "</td><td>" . consultaCategoriaSegunID($row['categoria']) . "</td><td><input type='submit' name='btnModificarTAnuncio' value='Modificar'/></td><td><input type='submit' name='btnEliminarTAnuncio' value='Eliminar'/></td></tr>";
                                    echo "<input type='hidden' name='anuncioid' value='" . $row['id'] . "' />";
                                    echo '<input type="hidden" name="btnAdminTodosAnuncios" />';
                                    echo "</form>";
                                }
                                ?>
                            </table>

                            <hr>
                            <br><br>

                        </section>
                        <?php
                    } else if (isset($_POST['btnAdminDepartamentos'])) {
                        ?>
                        <section>
                            <h2>Administrar Departamentos</h2>

                            <form action='#' method='POST'>
                                <input type="hidden" name="btnAdminDepartamentos" />
                                <input type="submit" name="btnInsertarDepartamento" value="Insertar nuevo departamento"/>
                            </form>
                            <hr>

                            <table border="0">
                                <tr><th>Nº Departamento</th><th>Nombre</th><th>Descripcion</th></tr>
                                <?php
                                $result = consultaDepartamentos();
                                for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                                    $row = mysqli_fetch_array($result);
                                    echo "<form action='#' method='POST'>";
                                    if($row['dept_no'] == 1){
                                        echo "<tr><td>" . $row['dept_no'] . "</td><td>" . $row['nombre'] . "</td><td>" . $row['descripcion'] . "</td><td><input type='submit' name='btnModificarDepartamento' value='Modificar'/></td><td></td></tr>";
                                    
                                    } else {
                                       echo "<tr><td>" . $row['dept_no'] . "</td><td>" . $row['nombre'] . "</td><td>" . $row['descripcion'] . "</td><td><input type='submit' name='btnModificarDepartamento' value='Modificar'/></td><td><input type='submit' name='btnEliminarDepartamento' value='Eliminar'/></td></tr>";
                                     
                                    }
                                    echo "<input type='hidden' name='departid' value='" . $row['dept_no'] . "' />";
                                    echo '<input type="hidden" name="btnAdminDepartamentos" />';
                                    echo "</form>";
                                }
                                ?>
                            </table>

                            <hr>
                            <br><br>



                        </section>
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
                                        <tr><td colspan="2"><?php echo $row['descripcion'] ?></td> <td colspan="2"><img src="./Material/fotos_anuncios/<?php echo $row['imagen'] ?>" alt="<?php echo $row['titulo'] ?>" width="300px" height="300px"/></td></tr>
                                        <tr><td>Precio: <?php echo $row['precio'] ?> €</td><td>Correo: <?php echo $row['correo'] ?> </td><td> Telefono: <?php echo $row['telefono'] ?> </td></tr>
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
                header("Location:areapersonalcliente.php");
            }
        } else {
            $_SESSION['history'] = $_SERVER['PHP_SELF'];
            header("Location:login.php");
        }
        ?>
    </body>
</html>

