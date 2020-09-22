
//FUNCION COMPROBAR SI ES NUMERO
function esNumero(e) {
    var pnum = /^[0-9]+$/;
    //event.which .- devuelve el boton del raton que fue pulsado
    var keynum = e.which;
    //Objecto string manipula cadena de caracteres.
    //fromCharCode .- convierte del valor unicode (asociado a cada tecla)
    //     a una cadena.
    var keychar = String.fromCharCode(keynum);
    //si el caracter es un numero o las teclas de borrar.      
    if (pnum.test(keychar) || keynum == 8 || keynum == 0) { //el 8 y el 0 son el intro y el suprimir (no se sabe el orden)
        return true;
    } else {
        return false;
    }
}

function esReal(e) {
    var pnum = /^[0-9.]$/;
    //event.which .- devuelve el boton del raton que fue pulsado
    var keynum = e.which;
    //Objecto string manipula cadena de caracteres.
    //fromCharCode .- convierte del valor unicode (asociado a cada tecla)
    //     a una cadena.
    var keychar = String.fromCharCode(keynum);
    //si el caracter es un numero o las teclas de borrar.      
    if (pnum.test(keychar) || keynum == 8 || keynum == 0) { //el 8 y el 0 son el intro y el suprimir (no se sabe el orden)
        return true;
    } else {
        return false;
    }
}

function esLetra(e) {
    var pnum = /^[a-zA-Z]+$/;
    //event.which .- devuelve el boton del raton que fue pulsado
    var keynum = e.which;
    //Objecto string manipula cadena de caracteres.
    //fromCharCode .- convierte del valor unicode (asociado a cada tecla)
    //     a una cadena.
    var keychar = String.fromCharCode(keynum);
    //si el caracter es un numero o las teclas de borrar.      
    if (pnum.test(keychar) || keynum == 8 || keynum == 0) { //el 8 y el 0 son el intro y el suprimir (no se sabe el orden)
        return true;
    } else {
        return false;
    }
}

function esLetraEspacio(e) {
    var pnum = /^[ a-zA-Z]+$/;
    //event.which .- devuelve el boton del raton que fue pulsado
    var keynum = e.which;
    //Objecto string manipula cadena de caracteres.
    //fromCharCode .- convierte del valor unicode (asociado a cada tecla)
    //     a una cadena.
    var keychar = String.fromCharCode(keynum);
    //si el caracter es un numero o las teclas de borrar.      
    if (pnum.test(keychar) || keynum == 8 || keynum == 0) { //el 8 y el 0 son el intro y el suprimir (no se sabe el orden)
        return true;
    } else {
        return false;
    }
}

function esLetraEspacioNums(e) {
    var pnum = /^[ a-zA-Z0-9]+$/;
    //event.which .- devuelve el boton del raton que fue pulsado
    var keynum = e.which;
    //Objecto string manipula cadena de caracteres.
    //fromCharCode .- convierte del valor unicode (asociado a cada tecla)
    //     a una cadena.
    var keychar = String.fromCharCode(keynum);
    //si el caracter es un numero o las teclas de borrar.      
    if (pnum.test(keychar) || keynum == 8 || keynum == 0) { //el 8 y el 0 son el intro y el suprimir (no se sabe el orden)
        return true;
    } else {
        return false;
    }
}

function esCategoria(e) {
    var pnum = /^[ a-zA-Z,]+$/;
    //event.which .- devuelve el boton del raton que fue pulsado
    var keynum = e.which;
    //Objecto string manipula cadena de caracteres.
    //fromCharCode .- convierte del valor unicode (asociado a cada tecla)
    //     a una cadena.
    var keychar = String.fromCharCode(keynum);
    //si el caracter es un numero o las teclas de borrar.      
    if (pnum.test(keychar) || keynum == 8 || keynum == 0) { //el 8 y el 0 son el intro y el suprimir (no se sabe el orden)
        return true;
    } else {
        return false;
    }
}

function esUsuario(e) {
    var pnum = /^[a-zA-Z0-9._@!-]+$/;
    //event.which .- devuelve el boton del raton que fue pulsado
    var keynum = e.which;
    //Objecto string manipula cadena de caracteres.
    //fromCharCode .- convierte del valor unicode (asociado a cada tecla)
    //     a una cadena.
    var keychar = String.fromCharCode(keynum);
    //si el caracter es un numero o las teclas de borrar.      
    if (pnum.test(keychar) || keynum == 8 || keynum == 0) { //el 8 y el 0 son el intro y el suprimir (no se sabe el orden)
        return true;
    } else {
        return false;
    }
}

function esDireccion(e) {
    var pnum = /^[ a-zA-Z0-9.,]+$/;
    //event.which .- devuelve el boton del raton que fue pulsado
    var keynum = e.which;
    //Objecto string manipula cadena de caracteres.
    //fromCharCode .- convierte del valor unicode (asociado a cada tecla)
    //     a una cadena.
    var keychar = String.fromCharCode(keynum);
    //si el caracter es un numero o las teclas de borrar.      
    if (pnum.test(keychar) || keynum == 8 || keynum == 0) { //el 8 y el 0 son el intro y el suprimir (no se sabe el orden)
        return true;
    } else {
        return false;
    }
}

function esCorreo(e) {
    var pnum = /^[a-zA-Z0-9\._@-]+$/;
    //event.which .- devuelve el boton del raton que fue pulsado
    var keynum = e.which;
    //Objecto string manipula cadena de caracteres.
    //fromCharCode .- convierte del valor unicode (asociado a cada tecla)
    //     a una cadena.
    var keychar = String.fromCharCode(keynum);
    //si el caracter es un numero o las teclas de borrar.      
    if (pnum.test(keychar) || keynum == 8 || keynum == 0) { //el 8 y el 0 son el intro y el suprimir (no se sabe el orden)
        return true;
    } else {
        return false;
    }
}




