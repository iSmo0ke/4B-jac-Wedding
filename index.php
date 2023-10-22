<?php
#En el mostraremos la salida de las vistas al usuario y tambien a traves de el le enviaremos las distintas 
#acciones que el usuario envie al controlador.


#require(). Establevce que el codigo del archivo invocado es requerido, es decir, obligatorio para el funcionamiento
#del programa. Por ello, si el archivo especificado en RA funcion requiere() no se encuentra saldra un error y 
#el programa se detiene.

#require_once, funciona de la misma que su respectivo, salvo que, al utilizar
require_once("controladores/plantilla.controlador.php");
require_once("controladores/formularios.controlador.php");
require_once"modelos/formularios.modelo.php";


$plantilla = new ControladorPlantilla();
$plantilla->ctrTraerPlantilla();


