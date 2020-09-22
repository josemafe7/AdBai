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
            <!-- INICIO FORMULARIO REGISTRO -->
            <div class="register_account">

                <?php
                if (isset($_POST['btnRegistrar'])) {

                    $exUsuario = "/^([a-zA-Z0-9._@!-])+$/";
                    $exNombre = "/^([ a-zA-Z])+$/";
                    $exSoloLetras = "/^([a-zA-Z])+$/";
                    $exSoloNumeros = "/^([0-9])+$/";
                    $exDNI = "/^([0-9]){8}$/";
                    $exCP = "/^([0-9]){5}$/";
                    $exTelf = "/^([0-9]){9}$/";
                    $exDireccion = "/^([ a-zA-Z0-9.,])+$/";
                    $exCorreo = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9])+[.]([a-zA-Z0-9\._-])*([a-zA-Z0-9])+$/";

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
                        $errores[] = 'Error: Usted es menor de edad';
                    if (!isset($_POST['gender']))
                        $gender = "";
                    else
                        $gender = $_POST['gender'];


                    if (!isset($errores)) {

                        if (insertaUsuarioCliente($_POST['usuario'], $_POST['clave'], strtoupper($_POST['nombre']), strtoupper($_POST['apellido1']), strtoupper($_POST['apellido2']), $_POST['numdni'], strtoupper($_POST['letradni']), $gender, $_POST['fec_nac'], strtoupper($_POST['direccion']), $_POST['codpostal'], $_POST['telefono'], strtoupper($_POST['correo']))) {
                            echo "<a id='registroLogin' href='login.php'>Ir al Login</a>";
                        }
                    } else {
                        echo '<p style="color:red">Errores cometidos:</p>';
                        echo '<ul style="color:red">';
                        foreach ($errores as $e)
                            echo "<li>$e</li>";
                        echo '</ul>';
                    }
                }
                ?>
                
                    
                    <div class="anchoGeneral">
                        <h4 class="title">Registro de usuario</h4>
                        <form action='#' method='POST'>
                            <div class="col_1_of_2 span_1_of_2">
                                <div><input type="text" value="Nombre" name="nombre" onkeypress="return esLetraEspacio(event)" required onfocus="this.value = '';" onblur="if (this.value == '') {
                                            this.value = 'Nombre';
                                        }"></div>
                                <div><input type="text" value="Primer apellido" name="apellido1" onkeypress="return esLetraEspacio(event)" required onfocus="this.value = '';" onblur="if (this.value == '') {
                                            this.value = 'Primer Apellido';
                                        }"></div>
                                <div><input type="text" value="Segundo apellido" name="apellido2" onkeypress="return esLetraEspacio(event)" required onfocus="this.value = '';" onblur="if (this.value == '') {
                                            this.value = 'Segundo Apellido';
                                        }"></div>
                                <div><input type="text" value="Numero DNI" name="numdni" maxlength="8" size="12" onkeypress="return esNumero(event)" required onfocus="this.value = '';" onblur="if (this.value == '') {
                                            this.value = 'Numero DNI';
                                        }"></div><input type="text" name="letradni" value="Letra DNI" maxlength="1" onkeypress="return esLetra(event)" size="1" required onfocus="this.value = '';" onblur="if (this.value == '') {
                                            this.value = 'Letra DNI';
                                        }"/>
                                <div>
                                    Genero<br>
                                    Masculino <input type="radio" name="gender" value="M"> <br>Femenino <input type="radio" name="gender" value="F">
                                </div>
                                <br>
                                <div>Fecha nacimiento <br><input type="date" value="Fecha Nacimiento" name="fec_nac" value="" required></div>
                            </div>
                            <div class="col_1_of_2 span_1_of_2">	
                                <div><input type="text" name="direccion" value="Direcion" onkeypress="return esDireccion(event)" required onfocus="this.value = '';" onblur="if (this.value == '') {
                                            this.value = 'Direcion';
                                        }"></div>		        
                                <div><input type="text" name="codpostal" value="Codigo Postal" maxlength="5" size="5" onkeypress="return esNumero(event)" required  onfocus="this.value = '';" onblur="if (this.value == '') {
                                            this.value = 'Codigo Postal';
                                        }"></div>
                                <div><input type="text" name="telefono" value="Teléfono" maxlength="9" size="9" onkeypress="return esNumero(event)" required  onfocus="this.value = '';" onblur="if (this.value == '') {
                                            this.value = 'Teléfono';
                                        }"></div>
                                <div><input type="text" name="correo" value="E-Mail" onkeypress="return esCorreo(event)" required  onfocus="this.value = '';" onblur="if (this.value == '') {
                                            this.value = 'E-Mail';
                                        }"></div>
                                <div><input type="text" name="usuario" value="Nombre usuario" onkeypress="return esUsuario(event)" required  onfocus="this.value = '';" onblur="if (this.value == '') {
                                            this.value = 'Nombre usuario';
                                        }"></div>
                                        <div><input type="password" name="clave" value="Contraseña"  required  onfocus="this.value = '';" onblur="if (this.value == '') {
                                            this.value = 'Contraseña';
                                        }"></div> 
                            </div>
                            <button class="botonBuscar" type="submit" name="btnRegistrar" value="Registrar" >Registrar</button>
                            <div class="clear"></div>
                        </form>
                    </div> 
                
            </div>
            <!-- FIN FORMULARIO REGISTRO -->

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