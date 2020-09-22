<?php
/*Trabajo-Aplicación AdBai
 */

/*
Autor: José_Manuel_Fernández_Labrador
*/

?>

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

        <!--BootStrap 4  --> 
        <link rel="stylesheet" href="bootstrap4/css/*" type="text/css"/>
        <script type="text/javascript" src="bootstrap4/js/*"></script>
        
        <!-- JavaScript Menu Inferior -->   
        <script type="text/javascript" src="js/megamenu.js"></script>
        <script>$(document).ready(function () {
                $(".megamenu").megamenu();
            });
        </script>
        <!-- FIN JavaScript Menu Inferior -->


        <!-- JavaScript SlideShow --> 
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $(".scroll").click(function (event) {
                    event.preventDefault();
                    $('html,body').animate({scrollTop: $(this.hash).offset().top}, 1000);
                });
            });
        </script>
        <!-- FIN JavaScript SlideShow -->


    </head>

    <body>

        <!-- MENU SUPERIOR -->
        <header class="superior">
            <div class="logo">
                <a href="index.php"><img width="35%" height="35%"src="./Material/img/adbailogo.png" alt="adbailogo"/></a>
            </div>
            <div class="menuSuperior">
                <ul>
                    <?php if (!logeado()) { ?>
                        <li><a href="registro.php">Registrate</a></li>
                        <li><a href="login.php">Login</a></li>
                    <?php } else { ?>
                        <li> <a href="perfil.php">Area Personal</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php } ?>
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



        <!-- SLIDE -->
        <nav class="index-banner">
            <div class="wmuSlider example1" style="height: 560px;">
                <div class="wmuSliderWrapper">
                    <article style="position: relative; width: 100%; opacity: 1;"> 
                        <div class="banner-wrap">
                            <div class="slider-left">
                                <img src="./Material/img/slide1.jpg" alt=""/> 
                            </div>
                            <div class="slider-right">
                                <h2>Coches</h2>
                                <p>Las mejores ofertas de coches.</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </article>
                    <article style="position: absolute; width: 100%; opacity: 0;"> 
                        <div class="banner-wrap">
                            <div class="slider-left">
                                <img src="./Material/img/slide2.jpg" alt=""/> 
                            </div>
                            <div class="slider-right">
                                <h2>Bicicletas</h2>

                                <p>Las mejores bicicletas de montaña</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </article>
                    <article style="position: absolute; width: 100%; opacity: 0;"> 
                        <div class="banner-wrap">
                            <div class="slider-left">
                                <img src="./Material/img/slide3.jpg" alt=""/> 
                            </div>
                            <div class="slider-right">
                                <h2>Ropa</h2>

                                <p>Vendelo</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </article>
                    <article style="position: absolute; width: 100%; opacity: 0;"> 
                        <div class="banner-wrap">
                            <div class="slider-left">
                                <img src="./Material/img/slide4.jpg" alt=""/> 
                            </div>
                            <div class="slider-right">
                                <h2>Libros</h2>

                                <p>Compra,vende o intercambia tus libros</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </article>
                </div>

                <a class="wmuSliderPrev">Previous</a><a class="wmuSliderNext">Next</a>
                <ul class="wmuSliderPagination">
                    <li><a href="#" class="">0</a></li>
                    <li><a href="#" class="">1</a></li>
                    <li><a href="#" class="wmuActive">2</a></li>
                    <li><a href="#">3</a></li>

                </ul>
                <ul class="wmuSliderPagination"><li><a href="#" class="wmuActive">0</a></li><li><a href="#" class="">1</a></li><li><a href="#" class="">2</a></li><li><a href="#" class="">3</a></li></ul>
            </div>
            <!--JQUERY SLIDE -->
            <script src="js/jquery.wmuSlider.js"></script> 
            <script>
            $('.example1').wmuSlider();
            </script> 
            <!--JQUERY SLIDE -->
        </nav>


        <!--FIN  SLIDE -->




        <!-- INTRO -->

        <section class="buscador">

            <h2>¿Qué estás buscando?</h2>

            <form action='#' method='GET'>
                <input class="inputBuscar" type="text" name="buscador" id="buscador" required/>
                <br>
                <br>
                <input class="botonBuscar"type="submit" name="btnBuscar" id="btnBuscar" value="Buscar" />
                <br>
                <br>
            </form>
            <br>
            <br>

        </section>
        <?php
        if (isset($_GET['btnBuscarCat'])) {
            if ($_GET['btnBuscarCat'] != 'Todas las categorias') {
                $id = consultaIdCategorias($_GET['btnBuscarCat']);
                $result = consultaAnunciosSegunCat($id);
            } else {
                $result = consultaAnuncios();
            }
            ?>
            <section class="resultadoBusqueda">
                <h2>Búsqueda por categoria: <?php echo $_GET['btnBuscarCat'] ?></h2>
                <br>
                <?php
                if (isset($_SESSION['user'])) {
                    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                        $row = mysqli_fetch_array($result);
                        ?>
                        <form action='#' method='GET'>
                            <table border="1">
                                <tr><td colspan="4">Titulo: <?php echo $row['titulo'] ?></td></tr>
                                <tr> <td colspan="2"><img src="./Material/fotos_anuncios/<?php echo $row['imagen'] ?>" alt="<?php echo $row['titulo'] ?>" width="300px" height="300px"/></td></tr>
                                <tr><td colspan="2"><?php echo $row['descripcion'] ?></td></tr>
                                <tr><td>Precio: <?php echo $row['precio'] ?> €</td></tr>
                                <tr><td>Correo: <?php echo $row['correo'] ?> </td></tr>
                                <tr><td> Telefono: <?php echo $row['telefono'] ?> </td></tr>
                            </table>
                        </form>
                        <br><hr><br>
                        <?php
                    }
                } else {

                    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                        $row = mysqli_fetch_array($result);
                        ?>
                        <form action='#' method='GET'>
                            <table border="2">
                                <tr><td colspan="4">Titulo: <?php echo $row['titulo'] ?></td></tr>
                                <tr> <td colspan="2"><img src="./Material/fotos_anuncios/<?php echo $row['imagen'] ?>" alt="<?php echo $row['titulo'] ?>" width="300px" height="300px"/></td></tr>
                                <tr><td colspan="2">Descripcion: <?php echo $row['descripcion'] ?></td></tr>
                                <tr><td colspan="4"><a id="registroLogin" href="login.php">Inicia sesión para ver más datos</a></td></tr>

                            </table>
                        </form>
                        <br><hr><br>
                        <?php
                    }
                    ?>
                </section>
                <?php
            }
        }
        ?>

        <?php
        if (isset($_GET['btnBuscar'])) {
            $result = consultaAnuncios();
            ?>
            <section class="resultadoBusqueda">
                <h2>Busqueda por palabra: <?php echo ucfirst($_GET['buscador']) ?></h2>
                <?php
                if (isset($_SESSION['user'])) {
                    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                        $row = mysqli_fetch_array($result);
                        if (contienePalabra($_GET['buscador'], $row['titulo'], $row['descripcion'])) {
                            ?>
                            <form action='#' method='GET'>
                                <table border="1">
                                    <tr><td colspan="4">Titulo: <?php echo $row['titulo'] ?></td></tr>
                                    <tr> <td colspan="2"><img src="./Material/fotos_anuncios/<?php echo $row['imagen'] ?>" alt="<?php echo $row['titulo'] ?>" width="300px" height="300px"/></td></tr>
                                    <tr><td colspan="2"><?php echo $row['descripcion'] ?></td></tr>
                                    <tr><td>Precio: <?php echo $row['precio'] ?> €</td></tr>
                                    <tr><td>Correo: <?php echo $row['correo'] ?> </td></tr>
                                    <tr><td> Telefono: <?php echo $row['telefono'] ?> </td></tr>
                                </table>
                            </form>

                            <br><hr><br>
                            <?php
                        }
                    }
                } else {

                    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                        $row = mysqli_fetch_array($result);
                        if (contienePalabra($_GET['buscador'], $row['titulo'], $row['descripcion'])) {
                            ?>
                            <form action='#' method='GET'>
                                <table border="2">
                                    <tr><td colspan="4"><?php echo $row['titulo'] ?></td></tr>
                                    <tr><td colspan="2"><?php echo $row['descripcion'] ?></td> <td colspan="2"><img src="./Material/fotos_anuncios/<?php echo $row['imagen'] ?>" alt="<?php echo $row['titulo'] ?>" width="300px" height="300px"/></td></tr>
                                    <tr><td colspan="4"><a id="registroLogin" href="login.php">Inicia sesión para ver más datos</a></td></tr>

                                </table>
                            </form>
                            <br><hr><br>
                            <?php
                        }
                    }
                }
                ?>
            </section>
            <?php
        }
        ?>

        <!-- INTRO -->


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

