<?php
session_start();
function logeado() {
    $enc = False; 
    if (isset($_SESSION['user'])) {
        $enc = True;
    }
    return $enc;
}
function conectaBBDD(&$conn, &$db) {
    $enc = False;
    $conn = mysqli_connect("localhost", "root", "") or die("Error en la conexión a MySql " . mysqli_error());
    $db = mysqli_select_db($conn, "adbai") or die("No se puede usar la BD " . mysqli_error($conn));
}
function consultaAnuncios() {
    conectaBBDD($conn, $db);
    $sql = "SELECT * FROM anuncios ORDER BY id asc";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    if ($result) {
        return $result;
    } else {
        return False;
    }
}
function consultaUsuarios() {
    conectaBBDD($conn, $db);
    $sql = "SELECT * FROM usuarios ORDER BY id asc";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    if ($result) {
        return $result;
    } else {
        return False;
    }
}
function consultaAnunciosSegunCat($id) {
    conectaBBDD($conn, $db);
    $sql = "SELECT * FROM anuncios WHERE categoria=" . $id . "";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    if ($result) {
        return $result;
    } else {
        return False;
    }
}
function consultaAnunciosSegunUsuario($usuario) {
    $id = obtenerIDUsuario($usuario);
    conectaBBDD($conn, $db);
    $sql = "SELECT * FROM anuncios WHERE userid=" . $id . "";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    if ($result) {
        return $result;
    } else {
        return False;
    }
}
function consultaAnuncioSegunID($id) {
    conectaBBDD($conn, $db);
    $sql = "SELECT * FROM anuncios WHERE id=" . $id . " ";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    if ($result) {
        return $result;
    } else {
        return False;
    }
}
function consultaCategorias() {
    conectaBBDD($conn, $db);
    $sql = "SELECT * FROM categorias ORDER BY id asc";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    if ($result) {
        return $result;
    } else {
        return False;
    }
}
function trim_value(&$value) {
    $value = trim($value);
}
function consultaIdCategorias($categoria) {
    conectaBBDD($conn, $db);
    $sql = "SELECT id FROM categorias WHERE categoria='" . $categoria . "'";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    if ($result) {
        $row = mysqli_fetch_array($result);
        return $row['id'];
    } else {
        return False;
    }
}

function contienePalabra($buscar, $titulo, $descripcion) {
    $enc = False;
    $dividir = explode(" ", $titulo);
    array_walk($dividir, 'trim_value');

    for ($i = 0; $i < count($dividir) && !$enc; $i++) {
        if (strtoupper($dividir[$i]) == strtoupper($buscar)) {
            $enc = True;
        }
    }
    $dividir = explode(" ", $descripcion);
    array_walk($dividir, 'trim_value');

    for ($i = 0; $i < count($dividir) && !$enc; $i++) {
        if (strtoupper($dividir[$i]) == strtoupper($buscar)) {
            $enc = True;
        }
    }
    return $enc;
}
function comprobarUsuario($usuario, $clave) {
    $enc = False;
    conectaBBDD($conn, $db);
    $saneadoUsuario = filter_var($usuario, FILTER_SANITIZE_STRING);
    $saneadoUsuario = mysqli_real_escape_string($conn, trim($saneadoUsuario));
    $sql = "SELECT usuario, clave FROM usuarios WHERE usuario='" . $saneadoUsuario . "'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_array($result);
        if (mysqli_num_rows($result) == 1) {

            if (password_verify($clave, $row['clave']) && $usuario == $row['usuario']) {
                $enc = True;
            }
        }
    }
    mysqli_close($conn);
    return $enc;
}
function renovarAnuncio($id) {
    $enc = False;
    conectaBBDD($conn, $db);
    $date = date("d-m-Y");
    $mod_date = strtotime($date . "+ 15 days"); // Los anuncios caducaran en 15 dias, se dará la opción a renovarlos por el mismo periodo
    $fin = date("Y-m-d", $mod_date) . "\n";
    $res = consultaAnuncioSegunID($id);
    $fila = mysqli_fetch_array($res);
    $renovaciones = $fila['renovaciones'];
    $renovaciones++;
    $sql = "UPDATE anuncios SET fecha_fin='" . $fin . "', renovaciones='" . $renovaciones . "' WHERE id=" . $id . "";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "<p style='color:red'>Error al renovar anuncio.</p>";
        $enc = False;
    } else {
        echo "<p style='color:green'>Anuncio renovado con exito.</p>";
        $enc = True;
    }
    mysqli_close($conn);
    return $enc;
}
function finalizarAnuncio($id) {
    $enc = False;
    conectaBBDD($conn, $db);
    $borraImagen = consultaAnuncioSegunID($id);
    $borrar = mysqli_fetch_array($borraImagen);
    $sql = "DELETE FROM anuncios WHERE id=" . $id . "";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "<p style='color:red'>Error al finalizar el anuncio.</p>";
        $enc = False;
    } else {
        if($borrar['imagen'] != ""){
             unlink("./Material/fotos_anuncios/" . $borrar['imagen']);
        }
       
        echo "<p style='color:green'>Anuncio finalizado con exito.</p>";
        $enc = True;
    }
    mysqli_close($conn);
    return $enc;
}
function modificarAnuncio($id, $titulo, $descripcion, $imagen, $precio, $telefono, $correo, $categoria) {
    $enc = False;
    conectaBBDD($conn, $db);
    $borraImagen = consultaAnuncioSegunID($id);
    $borrar = mysqli_fetch_array($borraImagen);
    $saneadoTitulo = filter_var($titulo, FILTER_SANITIZE_STRING);
    $saneadoTitulo = mysqli_real_escape_string($conn, trim($saneadoTitulo));
    $saneadoDescripcion = filter_var($descripcion, FILTER_SANITIZE_STRING);
    $saneadoDescripcion = mysqli_real_escape_string($conn, trim($saneadoDescripcion));
    $saneadoImagen = filter_var($imagen, FILTER_SANITIZE_STRING);
    $saneadoImagen = mysqli_real_escape_string($conn, trim($saneadoImagen));
    $saneadoTelefono = filter_var($telefono, FILTER_SANITIZE_NUMBER_INT);
    $saneadoTelefono = mysqli_real_escape_string($conn, trim($saneadoTelefono));
    $saneadoCorreo = filter_var($correo, FILTER_SANITIZE_STRING);
    $saneadoCorreo = mysqli_real_escape_string($conn, trim($saneadoCorreo));
    $sql = "UPDATE anuncios SET titulo='" . $saneadoTitulo . "', descripcion='" . $saneadoDescripcion . "', imagen='" . $saneadoImagen . "', precio='" . $precio . "', telefono='" . $saneadoTelefono . "', correo='" . $saneadoCorreo . "' WHERE id=" . $id . "";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "<p style='color:red'>Error al modificar anuncio.</p>";
        $enc = False;
    } else {
        if($borrar['imagen'] != ""){
             unlink("./Material/fotos_anuncios/" . $borrar['imagen']);
        }
        
        echo "<p style='color:green'>Anuncio modificado con exito.</p>";
        $enc = True;
    }
    mysqli_close($conn);
    return $enc;
}
function modificarAnuncioUsu($id, $titulo, $descripcion, $imagen, $precio, $telefono, $correo, $categoria, $userid) {
    $enc = False;
    conectaBBDD($conn, $db);
    $borraImagen = consultaAnuncioSegunID($id);
    $borrar = mysqli_fetch_array($borraImagen);
    $saneadoTitulo = filter_var($titulo, FILTER_SANITIZE_STRING);
    $saneadoTitulo = mysqli_real_escape_string($conn, trim($saneadoTitulo));
    $saneadoDescripcion = filter_var($descripcion, FILTER_SANITIZE_STRING);
    $saneadoDescripcion = mysqli_real_escape_string($conn, trim($saneadoDescripcion));
    $saneadoImagen = filter_var($imagen, FILTER_SANITIZE_STRING);
    $saneadoImagen = mysqli_real_escape_string($conn, trim($saneadoImagen));
    $saneadoTelefono = filter_var($telefono, FILTER_SANITIZE_NUMBER_INT);
    $saneadoTelefono = mysqli_real_escape_string($conn, trim($saneadoTelefono));
    $saneadoCorreo = filter_var($correo, FILTER_SANITIZE_STRING);
    $saneadoCorreo = mysqli_real_escape_string($conn, trim($saneadoCorreo));
    $sql = "UPDATE anuncios SET titulo='" . $saneadoTitulo . "', descripcion='" . $saneadoDescripcion . "', imagen='" . $saneadoImagen . "', precio='" . $precio . "', telefono='" . $saneadoTelefono . "', correo='" . $saneadoCorreo . "', userid=" . $userid ." WHERE id=" . $id . "";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "<p style='color:red'>Error al modificar anuncio.</p>";
        $enc = False;
    } else {
        if($borrar['imagen'] != ""){
             unlink("./Material/fotos_anuncios/" . $borrar['imagen']);
        }
       
        echo "<p style='color:green'>Anuncio modificado con exito.</p>";
        $enc = True;
    }
    mysqli_close($conn);
    return $enc;
}
function modificarUsuario($id, $usuario, $clave) {
    $enc = False;
    conectaBBDD($conn, $db);
    $saneadoUsuario = filter_var($usuario, FILTER_SANITIZE_STRING);
    $saneadoUsuario = mysqli_real_escape_string($conn, trim($saneadoUsuario));
    $hashClave = password_hash($clave, PASSWORD_DEFAULT);
    $sql = "UPDATE usuarios SET usuario='" . $saneadoUsuario . "', clave='" . $hashClave . "' WHERE id=" . $id . "";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "<p style='color:red'>Error al modificar los datos de cuenta.</p>";
        $enc = False;
    } else {
        echo "<p style='color:green'>Usuario modificado con exito.</p>";
        $enc = True;
    }
    mysqli_close($conn);
    return $enc;
}
function modificarCliente($id, $nombre, $apellido1, $apellido2, $numdni, $letradni, $sexo, $fec_nac, $direccion, $codpostal, $telefono, $correo) {
    $enc = False;
    conectaBBDD($conn, $db);
    $saneadoNombre = filter_var($nombre, FILTER_SANITIZE_STRING);
    $saneadoNombre = mysqli_real_escape_string($conn, trim($saneadoNombre));
    $saneadoApellido1 = filter_var($apellido1, FILTER_SANITIZE_STRING);
    $saneadoApellido1 = mysqli_real_escape_string($conn, trim($saneadoApellido1));
    $saneadoApellido2 = filter_var($apellido2, FILTER_SANITIZE_STRING);
    $saneadoApellido2 = mysqli_real_escape_string($conn, trim($saneadoApellido2));
    $saneadoNumdni = filter_var($numdni, FILTER_SANITIZE_NUMBER_INT);
    $saneadoNumdni = mysqli_real_escape_string($conn, trim($saneadoNumdni));
    $saneadoLetradni = filter_var($letradni, FILTER_SANITIZE_STRING);
    $saneadoLetradni = mysqli_real_escape_string($conn, trim($saneadoLetradni));
    $saneadoDireccion = filter_var($direccion, FILTER_SANITIZE_STRING);
    $saneadoDireccion = mysqli_real_escape_string($conn, trim($saneadoDireccion));
    $saneadoCodpostal = filter_var($codpostal, FILTER_SANITIZE_NUMBER_INT);
    $saneadoCodpostal = mysqli_real_escape_string($conn, trim($saneadoCodpostal));
    $saneadoTelefono = filter_var($telefono, FILTER_SANITIZE_NUMBER_INT);
    $saneadoTelefono = mysqli_real_escape_string($conn, trim($saneadoTelefono));
    $saneadoCorreo = filter_var($correo, FILTER_SANITIZE_STRING);
    $saneadoCorreo = mysqli_real_escape_string($conn, trim($saneadoCorreo));
    $dni = "" . $saneadoNumdni . "" . $saneadoLetradni . "";
    $sql = "UPDATE clientes SET dni='" . $dni . "', nombre='" . $saneadoNombre . "', apellido1='" . $saneadoApellido1 . "', apellido2='" . $saneadoApellido2 . "', sexo='" . $sexo . "', fecha_nac='" . $fec_nac . "', direccion='" . $saneadoDireccion . "', codpostal='" . $saneadoCodpostal . "', telefono='" . $saneadoTelefono . "', correo='" . $saneadoCorreo . "' WHERE id=" . $id . "";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "<p style='color:red'>Error al modificar los datos de cuenta.</p>";
        $enc = False;
    } else {
        echo "<p style='color:green'>Usuario modificado con exito.</p>";
        $enc = True;
    }
    mysqli_close($conn);
    return $enc;
}
function insertaUsuarioCliente($usuario, $clave, $nombre, $apellido1, $apellido2, $numdni, $letradni, $sexo, $fec_nac, $direccion, $codpostal, $telefono, $correo) {
    $enc = False;
    conectaBBDD($conn, $db);
    $saneadoUsuario = filter_var($usuario, FILTER_SANITIZE_STRING);
    $saneadoUsuario = mysqli_real_escape_string($conn, trim($saneadoUsuario));
    $saneadoNombre = filter_var($nombre, FILTER_SANITIZE_STRING);
    $saneadoNombre = mysqli_real_escape_string($conn, trim($saneadoNombre));
    $saneadoApellido1 = filter_var($apellido1, FILTER_SANITIZE_STRING);
    $saneadoApellido1 = mysqli_real_escape_string($conn, trim($saneadoApellido1));
    $saneadoApellido2 = filter_var($apellido2, FILTER_SANITIZE_STRING);
    $saneadoApellido2 = mysqli_real_escape_string($conn, trim($saneadoApellido2));
    $saneadoNumdni = filter_var($numdni, FILTER_SANITIZE_NUMBER_INT);
    $saneadoNumdni = mysqli_real_escape_string($conn, trim($saneadoNumdni));
    $saneadoLetradni = filter_var($letradni, FILTER_SANITIZE_STRING);
    $saneadoLetradni = mysqli_real_escape_string($conn, trim($saneadoLetradni));
    $saneadoDireccion = filter_var($direccion, FILTER_SANITIZE_STRING);
    $saneadoDireccion = mysqli_real_escape_string($conn, trim($saneadoDireccion));
    $saneadoCodpostal = filter_var($codpostal, FILTER_SANITIZE_NUMBER_INT);
    $saneadoCodpostal = mysqli_real_escape_string($conn, trim($saneadoCodpostal));
    $saneadoTelefono = filter_var($telefono, FILTER_SANITIZE_NUMBER_INT);
    $saneadoTelefono = mysqli_real_escape_string($conn, trim($saneadoTelefono));
    $saneadoCorreo = filter_var($correo, FILTER_SANITIZE_STRING);
    $saneadoCorreo = mysqli_real_escape_string($conn, trim($saneadoCorreo));
    $hashClave = password_hash($clave, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (usuario, clave, tipo)  VALUES('" . $saneadoUsuario . "','" . $hashClave . "', 'C')";
    //id ai es pk usuario es unico
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "<p style='color:red'>El usuario ya existe.</p>";
        $enc = False;
    } else {
        $id = obtenerIDUsuario($saneadoUsuario);
        $dni = "" . $saneadoNumdni . "" . $saneadoLetradni . "";
        $sql = "INSERT INTO clientes (id, dni, nombre, apellido1, apellido2, sexo, fecha_nac, direccion, codpostal, telefono, correo)  VALUES(" . $id . ",'" . $dni . "','" . $saneadoNombre . "','" . $saneadoApellido1 . "','" . $saneadoApellido2 . "','" . $sexo . "','" . $fec_nac . "','" . $saneadoDireccion . "'," . $saneadoCodpostal . "," . $saneadoTelefono . ",'" . $saneadoCorreo . "')";
        //id y dni son pk
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            echo "<p style='color:red'>El DNI introducido ya existe.</p>";
            errorClienteBorraUsuario($id);
            $enc = False;
        } else {
            echo "<p style='color:green'>Usuario registrado con exito.</p>";
            $enc = True;
        }
    }
    mysqli_close($conn);
    return $enc;
}

function obtenerIDUsuario($usuario) {
    conectaBBDD($conn, $db);
    $sql = "SELECT id FROM usuarios WHERE usuario='" . $usuario . "'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_array($result);
        mysqli_close($conn);
        return $row['id'];
    } else {
        mysqli_close($conn);
        return False;
    }
}
function errorClienteBorraUsuario($id) {
    conectaBBDD($conn, $db);
    $sql = "DELETE FROM usuarios WHERE id='" . $id . "'";

    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
}
function calculaEdad($fecha_nacimiento) {
    $dia = date("d");
    $mes = date("m");
    $ano = date("Y");
    $dianac = date("d", strtotime($fecha_nacimiento));
    $mesnac = date("m", strtotime($fecha_nacimiento));
    $anonac = date("Y", strtotime($fecha_nacimiento));


//si el mes es el mismo pero el día inferior aun no ha cumplido años, le quitaremos un año al actual

    if (($mesnac == $mes) && ($dianac > $dia)) {
        $ano = ($ano - 1);
    }

//si el mes es superior al actual tampoco habrá cumplido años, por eso le quitamos un año al actual

    if ($mesnac > $mes) {
        $ano = ($ano - 1);
    }

    //ya no habría mas condiciones, ahora simplemente restamos los años y mostramos el resultado como su edad

    $edad = ($ano - $anonac);


    return $edad;
}

function calculaDiasFecha($fecha_fin) {

    $fechahoy = date("Y-m-d");
    $resta = strtotime($fecha_fin) - strtotime($fechahoy);
    $dias = $resta / (24 * 3600);
    return $dias;
}

function obtenerTelefonoCorreoCliente($usuario) {
    $id = obtenerIDUsuario($usuario);
    conectaBBDD($conn, $db);
    $sql = "SELECT telefono, correo FROM clientes WHERE id='" . $id . "'";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        return $result;
    } else {
        mysqli_close($conn);
        return False;
    }
}

function consultaCliente($id) {
    conectaBBDD($conn, $db);
    $sql = "SELECT * FROM clientes WHERE id=" . $id . "";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        return $result;
    } else {
        mysqli_close($conn);
        return False;
    }
}

function insertaAnuncio($titulo, $descripcion, $imagen, $precio, $telefono, $correo, $usuario, $categoria) {
    $enc = False;
    conectaBBDD($conn, $db);

    $saneadoUsuario = filter_var($usuario, FILTER_SANITIZE_STRING);
    $saneadoUsuario = mysqli_real_escape_string($conn, trim($saneadoUsuario));
    $saneadoTitulo = filter_var($titulo, FILTER_SANITIZE_STRING);
    $saneadoTitulo = mysqli_real_escape_string($conn, trim($saneadoTitulo));
    $saneadoDescripcion = filter_var($descripcion, FILTER_SANITIZE_STRING);
    $saneadoDescripcion = mysqli_real_escape_string($conn, trim($saneadoDescripcion));
    $saneadoImagen = filter_var($imagen, FILTER_SANITIZE_STRING);
    $saneadoImagen = mysqli_real_escape_string($conn, trim($saneadoImagen));
    $saneadoTelefono = filter_var($telefono, FILTER_SANITIZE_NUMBER_INT);
    $saneadoTelefono = mysqli_real_escape_string($conn, trim($saneadoTelefono));
    $saneadoCorreo = filter_var($correo, FILTER_SANITIZE_STRING);
    $saneadoCorreo = mysqli_real_escape_string($conn, trim($saneadoCorreo));

    $id = obtenerIDUsuario($saneadoUsuario);
    $fecha = getdate();
    $hoy = "" . $fecha['year'] . "-" . $fecha['mon'] . "-" . $fecha['mday'];

    $date = date("d-m-Y");
    $mod_date = strtotime($date . "+ 15 days"); // Los anuncios caducaran en 15 dias, se dará la opción a renovarlos por el mismo periodo
    $fin = date("Y-m-d", $mod_date) . "\n";

    $sql = "INSERT INTO anuncios (titulo, descripcion, imagen, precio, telefono, correo, fecha_ini, fecha_fin, userid, categoria)  VALUES('" . $saneadoTitulo . "','" . $saneadoDescripcion . "','" . $saneadoImagen . "'," . $precio . "," . $saneadoTelefono . ",'" . $saneadoCorreo . "','" . $hoy . "','" . $fin . "'," . $id . "," . $categoria . ")";
    //id ai es pk 
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "<p style='color:red'>Error al insertar anuncio.</p>";
        $enc = False;
    } else {

        echo "<p style='color:green'>Anuncio registrado con exito.</p>";
        $enc = True;
    }

    mysqli_close($conn);

    return $enc;
}

function esEmpleado($usuario) {
    conectaBBDD($conn, $db);
    $sql = "SELECT tipo FROM usuarios WHERE usuario='" . $usuario . "'";

    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    if ($result) {
        $row = mysqli_fetch_array($result);
        if ($row['tipo'] == 'E') {
            return True;
        } else {
            return False;
        }
    } else {
        return False;
    }
}

function consultaDepartamentoSegunID($id) {
    conectaBBDD($conn, $db);
    $sql = "SELECT departamento FROM empleados WHERE id=" . $id . "";

    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    if ($result) {
        $row = mysqli_fetch_array($result);
        return $row['departamento'];
    } else {
        return False;
    }
}

function consultaEmpleado($id) {
    conectaBBDD($conn, $db);
    $sql = "SELECT * FROM empleados WHERE id=" . $id . "";

    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    if ($result) {
        return $result;
    } else {
        
        return False;
    }
}

function consultaEmpleados() {
    conectaBBDD($conn, $db);
    $sql = "SELECT * FROM empleados ORDER BY id asc";

    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    if ($result) {
        return $result;
    } else {

        return False;
    }
}

function consultaDepartamentoSegunDeptNo($deptNo) {
    conectaBBDD($conn, $db);
    $sql = "SELECT * FROM departamentos WHERE dept_no='" . $deptNo . "'";

    $result = mysqli_query($conn, $sql);
mysqli_close($conn);
    if ($result) {
        $row = mysqli_fetch_array($result);
        
        return $row['nombre'];
    } else {
        
        return False;
    }
}

function consultaDepartamentoTodoSegunDeptNo($deptNo) {
    conectaBBDD($conn, $db);
    $sql = "SELECT * FROM departamentos WHERE dept_no='" . $deptNo . "'";

    $result = mysqli_query($conn, $sql);
mysqli_close($conn);
    if ($result) {     
        return $result;
    } else {
        
        return False;
    }
}

function consultaClientes() {
    conectaBBDD($conn, $db);
    $sql = "SELECT * FROM clientes ORDER BY id asc";

    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    if ($result) {
        return $result;
    } else {

        return False;
    }
}

function consultaDepartamentos() {
    conectaBBDD($conn, $db);
    $sql = "SELECT * FROM departamentos ORDER BY dept_no asc";

    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    if ($result) {
        return $result;
    } else {

        return False;
    }
}


function consultaCategoriaSegunID($id) {
    conectaBBDD($conn, $db);
    $sql = "SELECT * FROM categorias WHERE id=" . $id . "";

    $result = mysqli_query($conn, $sql);
mysqli_close($conn);
    if ($result) {
        $row = mysqli_fetch_array($result);
        
        return $row['categoria'];
    } else {
        
        return False;
    }
}

function consultaUsuarioSegunID($id) {
    conectaBBDD($conn, $db);
    $sql = "SELECT * FROM usuarios WHERE id=" . $id . "";

    $result = mysqli_query($conn, $sql);
mysqli_close($conn);
    if ($result) {
        $row = mysqli_fetch_array($result);
        
        return $row['usuario'];
    } else {
        
        return False;
    }
}

function modificarDatosEmpleado($id, $nombre, $apellido1, $apellido2, $numdni, $letradni, $sexo, $fec_nac, $direccion, $codpostal, $telefono, $correo) {
    $enc = False;
    conectaBBDD($conn, $db);

    $saneadoNombre = filter_var($nombre, FILTER_SANITIZE_STRING);
    $saneadoNombre = mysqli_real_escape_string($conn, trim($saneadoNombre));
    $saneadoApellido1 = filter_var($apellido1, FILTER_SANITIZE_STRING);
    $saneadoApellido1 = mysqli_real_escape_string($conn, trim($saneadoApellido1));
    $saneadoApellido2 = filter_var($apellido2, FILTER_SANITIZE_STRING);
    $saneadoApellido2 = mysqli_real_escape_string($conn, trim($saneadoApellido2));
    $saneadoNumdni = filter_var($numdni, FILTER_SANITIZE_NUMBER_INT);
    $saneadoNumdni = mysqli_real_escape_string($conn, trim($saneadoNumdni));
    $saneadoLetradni = filter_var($letradni, FILTER_SANITIZE_STRING);
    $saneadoLetradni = mysqli_real_escape_string($conn, trim($saneadoLetradni));
    $saneadoDireccion = filter_var($direccion, FILTER_SANITIZE_STRING);
    $saneadoDireccion = mysqli_real_escape_string($conn, trim($saneadoDireccion));
    $saneadoCodpostal = filter_var($codpostal, FILTER_SANITIZE_NUMBER_INT);
    $saneadoCodpostal = mysqli_real_escape_string($conn, trim($saneadoCodpostal));
    $saneadoTelefono = filter_var($telefono, FILTER_SANITIZE_NUMBER_INT);
    $saneadoTelefono = mysqli_real_escape_string($conn, trim($saneadoTelefono));
    $saneadoCorreo = filter_var($correo, FILTER_SANITIZE_STRING);
    $saneadoCorreo = mysqli_real_escape_string($conn, trim($saneadoCorreo));
    $dni = "" . $saneadoNumdni . "" . $saneadoLetradni . "";


    $sql = "UPDATE empleados SET dni='" . $dni . "', nombre='" . $saneadoNombre . "', apellido1='" . $saneadoApellido1 . "', apellido2='" . $saneadoApellido2 . "', sexo='" . $sexo . "', fecha_nac='" . $fec_nac . "', direccion='" . $saneadoDireccion . "', codpostal='" . $saneadoCodpostal . "', telefono='" . $saneadoTelefono . "', correo='" . $saneadoCorreo . "' WHERE id=" . $id . "";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "<p style='color:red'>Error al modificar los datos de cuenta.</p>";
        $enc = False;
    } else {
        echo "<p style='color:green'>Usuario modificado con exito.</p>";
        $enc = True;
    }

    mysqli_close($conn);

    return $enc;
}

function insertaCategoria($categoria) {
    $enc = False;
    conectaBBDD($conn, $db);

    $saneadoCategoria = filter_var($categoria, FILTER_SANITIZE_STRING);
    $saneadoCategoria = mysqli_real_escape_string($conn, trim($saneadoCategoria));


    $sql = "INSERT INTO categorias (categoria)  VALUES('" . $saneadoCategoria . "')";
    //id ai es pk 
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "<p style='color:red'>Error al insertar categoria.</p>";
        $enc = False;
    } else {

        echo "<p style='color:green'>Categoria registrada con exito.</p>";
        $enc = True;
    }

    mysqli_close($conn);

    return $enc;
}

function modificarCategoria($id, $categoria) {
    $enc = False;
    conectaBBDD($conn, $db);
    $saneadoCategoria = filter_var($categoria, FILTER_SANITIZE_STRING);
    $saneadoCategoria = mysqli_real_escape_string($conn, trim($saneadoCategoria));


    $sql = "UPDATE categorias SET categoria='" . $saneadoCategoria . "' WHERE id=" . $id . "";
    $result = mysqli_query($conn, $sql);
    
    
    if (!$result) {
        echo "<p style='color:red'>Error al modificar la categoria.</p>";
        $enc = False;
    } else {
        echo "<p style='color:green'>Categoria modificada con exito.</p>";
        $enc = True;
    }

    mysqli_close($conn);

    return $enc;
}


function eliminarCategoria($id) {
    $enc = False;
    conectaBBDD($conn, $db);


    $sql = "DELETE FROM categorias WHERE id=" . $id . "";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "<p style='color:red'>Error al eliminar la categoria.</p>";
        $enc = False;
    } else {
        echo "<p style='color:green'>Categoria eliminada con exito.</p>";
        $enc = True;
    }

    mysqli_close($conn);

    return $enc;
}


function insertaDepartamento($nombre, $descripcion) {
    $enc = False;
    conectaBBDD($conn, $db);

    $saneadoNombre = filter_var($nombre, FILTER_SANITIZE_STRING);
    $saneadoNombre = mysqli_real_escape_string($conn, trim($saneadoNombre));
    $saneadoDescripcion = filter_var($descripcion, FILTER_SANITIZE_STRING);
    $saneadoDescripcion = mysqli_real_escape_string($conn, trim($saneadoDescripcion));


    $sql = "INSERT INTO departamentos (nombre, descripcion)  VALUES('" . $saneadoNombre . "', '" . $saneadoDescripcion . "')";
    //dept_no ai es pk 
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "<p style='color:red'>Error al insertar departamento.</p>";
        $enc = False;
    } else {

        echo "<p style='color:green'>Departamento registrado con exito.</p>";
        $enc = True;
    }

    mysqli_close($conn);

    return $enc;
}

function modificarDepartamento($dept_no, $nombre, $descripcion) {
    $enc = False;
    conectaBBDD($conn, $db);
    $saneadoNombre = filter_var($nombre, FILTER_SANITIZE_STRING);
    $saneadoNombre = mysqli_real_escape_string($conn, trim($saneadoNombre));
    $saneadoDescripcion = filter_var($descripcion, FILTER_SANITIZE_STRING);
    $saneadoDescripcion = mysqli_real_escape_string($conn, trim($saneadoDescripcion));


    $sql = "UPDATE departamentos SET nombre='" . $saneadoNombre . "', descripcion='" . $saneadoDescripcion . "' WHERE dept_no=" . $dept_no . "";
    $result = mysqli_query($conn, $sql);
    
    
    if (!$result) {
        echo "<p style='color:red'>Error al modificar el departamento.</p>";
        $enc = False;
    } else {
        echo "<p style='color:green'>Departamento modificado con exito.</p>";
        $enc = True;
    }

    mysqli_close($conn);

    return $enc;
}

function eliminarDepartamento($dept_no) {
    $enc = False;
    conectaBBDD($conn, $db);


    $sql = "DELETE FROM departamentos WHERE dept_no=" . $dept_no . "";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "<p style='color:red'>Error al eliminar el departamento.</p>";
        $enc = False;
    } else {
        echo "<p style='color:green'>Departamento eliminado con exito.</p>";
        $enc = True;
    }

    mysqli_close($conn);

    return $enc;
}
function insertaUsuarioEmpleado($usuario, $clave, $nombre, $apellido1, $apellido2, $numdni, $letradni, $sexo, $fec_nac, $direccion, $codpostal, $telefono, $correo, $salario, $departamento) {
    $enc = False;
    conectaBBDD($conn, $db);
    $saneadoUsuario = filter_var($usuario, FILTER_SANITIZE_STRING);
    $saneadoUsuario = mysqli_real_escape_string($conn, trim($saneadoUsuario));
    $saneadoNombre = filter_var($nombre, FILTER_SANITIZE_STRING);
    $saneadoNombre = mysqli_real_escape_string($conn, trim($saneadoNombre));
    $saneadoApellido1 = filter_var($apellido1, FILTER_SANITIZE_STRING);
    $saneadoApellido1 = mysqli_real_escape_string($conn, trim($saneadoApellido1));
    $saneadoApellido2 = filter_var($apellido2, FILTER_SANITIZE_STRING);
    $saneadoApellido2 = mysqli_real_escape_string($conn, trim($saneadoApellido2));
    $saneadoNumdni = filter_var($numdni, FILTER_SANITIZE_NUMBER_INT);
    $saneadoNumdni = mysqli_real_escape_string($conn, trim($saneadoNumdni));
    $saneadoLetradni = filter_var($letradni, FILTER_SANITIZE_STRING);
    $saneadoLetradni = mysqli_real_escape_string($conn, trim($saneadoLetradni));
    $saneadoDireccion = filter_var($direccion, FILTER_SANITIZE_STRING);
    $saneadoDireccion = mysqli_real_escape_string($conn, trim($saneadoDireccion));
    $saneadoCodpostal = filter_var($codpostal, FILTER_SANITIZE_NUMBER_INT);
    $saneadoCodpostal = mysqli_real_escape_string($conn, trim($saneadoCodpostal));
    $saneadoTelefono = filter_var($telefono, FILTER_SANITIZE_NUMBER_INT);
    $saneadoTelefono = mysqli_real_escape_string($conn, trim($saneadoTelefono));
    $saneadoCorreo = filter_var($correo, FILTER_SANITIZE_STRING);
    $saneadoCorreo = mysqli_real_escape_string($conn, trim($saneadoCorreo));
    $saneadoSalario = filter_var($salario, FILTER_SANITIZE_NUMBER_FLOAT);
    $saneadoSalario = mysqli_real_escape_string($conn, trim($saneadoSalario));
    $hashClave = password_hash($clave, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (usuario, clave, tipo)  VALUES('" . $saneadoUsuario . "','" . $hashClave . "', 'E')";
    //id ai es pk usuario es unico
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "<p style='color:red'>El usuario ya existe.</p>";
        $enc = False;
    } else {
        $id = obtenerIDUsuario($saneadoUsuario);
        $dni = "" . $saneadoNumdni . "" . $saneadoLetradni . "";
        $sql = "INSERT INTO empleados (id, dni, nombre, apellido1, apellido2, sexo, fecha_nac, direccion, codpostal, telefono, correo, salario, departamento)  VALUES(" . $id . ",'" . $dni . "','" . $saneadoNombre . "','" . $saneadoApellido1 . "','" . $saneadoApellido2 . "','" . $sexo . "','" . $fec_nac . "','" . $saneadoDireccion . "'," . $saneadoCodpostal . "," . $saneadoTelefono . ",'" . $saneadoCorreo . "','" . $saneadoSalario . "','" . $departamento . "')";
        //id y dni son pk
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            echo "<p style='color:red'>El DNI introducido ya existe.</p>";
            errorClienteBorraUsuario($id);
            $enc = False;
        } else {
            echo "<p style='color:green'>Usuario registrado con exito.</p>";
            $enc = True;
        }
    }
    mysqli_close($conn);
    return $enc;
}
function modificarEmpleado($id, $nombre, $apellido1, $apellido2, $numdni, $letradni, $sexo, $fec_nac, $direccion, $codpostal, $telefono, $correo, $salario, $departamento) {
    $enc = False;
    conectaBBDD($conn, $db);
    $saneadoNombre = filter_var($nombre, FILTER_SANITIZE_STRING);
    $saneadoNombre = mysqli_real_escape_string($conn, trim($saneadoNombre));
    $saneadoApellido1 = filter_var($apellido1, FILTER_SANITIZE_STRING);
    $saneadoApellido1 = mysqli_real_escape_string($conn, trim($saneadoApellido1));
    $saneadoApellido2 = filter_var($apellido2, FILTER_SANITIZE_STRING);
    $saneadoApellido2 = mysqli_real_escape_string($conn, trim($saneadoApellido2));
    $saneadoNumdni = filter_var($numdni, FILTER_SANITIZE_NUMBER_INT);
    $saneadoNumdni = mysqli_real_escape_string($conn, trim($saneadoNumdni));
    $saneadoLetradni = filter_var($letradni, FILTER_SANITIZE_STRING);
    $saneadoLetradni = mysqli_real_escape_string($conn, trim($saneadoLetradni));
    $saneadoDireccion = filter_var($direccion, FILTER_SANITIZE_STRING);
    $saneadoDireccion = mysqli_real_escape_string($conn, trim($saneadoDireccion));
    $saneadoCodpostal = filter_var($codpostal, FILTER_SANITIZE_NUMBER_INT);
    $saneadoCodpostal = mysqli_real_escape_string($conn, trim($saneadoCodpostal));
    $saneadoTelefono = filter_var($telefono, FILTER_SANITIZE_NUMBER_INT);
    $saneadoTelefono = mysqli_real_escape_string($conn, trim($saneadoTelefono));
    $saneadoCorreo = filter_var($correo, FILTER_SANITIZE_STRING);
    $saneadoCorreo = mysqli_real_escape_string($conn, trim($saneadoCorreo));
    $saneadoSalario = filter_var($salario, FILTER_SANITIZE_NUMBER_FLOAT);
    $saneadoSalario = mysqli_real_escape_string($conn, trim($saneadoSalario));
    $dni = "" . $saneadoNumdni . "" . $saneadoLetradni . "";
    $sql = "UPDATE empleados SET nombre='" . $saneadoNombre . "', apellido1='" . $saneadoApellido1 . "', apellido2='" . $saneadoApellido2 . "', dni='" . $dni . "', sexo='" . $sexo . "', fecha_nac='" . $fec_nac . "', direccion='" . $saneadoDireccion . "', codpostal='" . $saneadoCodpostal . "', telefono='" . $saneadoTelefono . "', correo='" . $saneadoCorreo . "', salario='" . $saneadoSalario . "', departamento='" . $departamento . "' WHERE id=" . $id . "";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "<p style='color:red'>Error al modificar el empleado.</p>";
        $enc = False;
    } else {
        echo "<p style='color:green'>Empleado modificado con exito.</p>";
        $enc = True;
    }
    mysqli_close($conn);
    return $enc;
}
function eliminarEmpleado($id) {
    $enc = False;
    conectaBBDD($conn, $db);
    $sql = "DELETE FROM empleados WHERE id=" . $id . "";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "<p style='color:red'>Error al eliminar el empleado.</p>";
        $enc = False;
    } else {
        echo "<p style='color:green'>Empleado eliminado con exito.</p>";
        $enc = True;
    }
   mysqli_close($conn);
    return $enc;
}
function eliminarUsuario($id) {
    $enc = False;
    conectaBBDD($conn, $db);
    $sql = "DELETE FROM usuarios WHERE id=" . $id . "";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "<p style='color:red'>Error al eliminar el usuario.</p>";
        $enc = False;
    } else {
        echo "<p style='color:green'>Usuario eliminado con exito.</p>";
        $enc = True;
    }
    mysqli_close($conn);
    return $enc;
}
function eliminarCliente($id) {
    $enc = False;
    conectaBBDD($conn, $db);
    $sql = "DELETE FROM clientes WHERE id=" . $id . "";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "<p style='color:red'>Error al eliminar el cliente.</p>";
        $enc = False;
    } else {
        echo "<p style='color:green'>Cliente eliminado con exito.</p>";
        $enc = True;
    }
    mysqli_close($conn);
    return $enc;
}
?>