<?php
require_once("modelos/formularios.modelo.php");

class ControladorFormularios
{
    # Registro

    static public function ctrRegistro()
    {
        if (isset($_POST["registroNombre"])) {

            if (preg_match("/^[a-zA-Z0-9áéíóúüÜÁÉÍÓÚ\s]+$/", $_POST["registroNombre"]) &&
                preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["registroEmail"]) &&
                preg_match('/^[0-9a-zA-Z.]+$/', $_POST["registroPassword"])) {
                $tabla = "registros";

                $token = md5($_POST["registroNombre"] . "+" . $_POST["registroEmail"]);
                $encriptarPassword = crypt($_POST["registroPassword"], '$5$rounds=5000$jesusaltamiranocarrillo4b$');

                $datos = array(
                    "token" => $token,
                    "nombre" => $_POST["registroNombre"],
                    "email" => $_POST["registroEmail"],
                    "password" => $encriptarPassword
                );

                $respuesta = ModeloFormularios::mdlRegistro($tabla, $datos);
                return $respuesta;
            } else {
                $respuesta = "error";
                return $respuesta;
            }
        }
    }

    /**
     * Seleccionar registros de la tabla
     */

    static public function ctrSeleccionarRegistros($item, $valor)
    {
        $tabla = "registros";
        $respuesta = ModeloFormularios::mdlSeleccionarRegistro($tabla, $item, $valor);
        return $respuesta;
    }

    /**
     * Ingreso
     */

    public function ctrIngreso()
    {
        if(isset($_POST["ingresoEmail"])){
            $tabla = "registros";
            $item = "email";
            $valor = $_POST["ingresoEmail"];

            $respuesta = ModeloFormularios::mdlSeleccionarRegistro($tabla, $item, $valor);

            //echo "<pre>"; print_r($respuesta); echo "</pre>";

            $encriptarPassword=crypt($_POST["ingresoPassword"], '$5$rounds=5000$jesusaltamiranocarrillo4b$');

            if(is_array($respuesta)){ //$respuesta != null

                if($respuesta["email"] == $_POST["ingresoEmail"] && $respuesta["password"] == $encriptarPassword){
                    ModeloFormularios::mdlActualizarIntentosFallidos($tabla, 0, $respuesta["token"]);
                    $_SESSION["validarIngreso"] = "ok";


                    echo'<script>
                    if(window.history.replaceState){
                        window.history.replaceState(null, null, window.location.href);
                    }
                    window.location = "index.php?pagina=inicio";
                    </script>';
                }else{

                    if($respuesta["intentos_fallidos"] <3 ){

                        $tabla = "registros";

                        $intentos_fallidos = $respuesta["intentos_fallidos"] +1;
                    
                        $actualizarIntentosFallidos = ModeloFormularios::mdlActualizarIntentosFallidos($tabla, $intentos_fallidos, $respuesta["token"]);

                    //echo'<pre>'; print_r($intentos_fallidos); echo '</pre>';

                    

                    }else{
                        echo '<div class="alert alert-warning">Recaptcha, Debes varidar que no eres un robot</div>';
                    }

                    
                    echo'<script>
                    if(window.history.replaceState){
                        window.history.replaceState(null, null, window.location.href);
                    }</script>';
                    echo '<div class="alert alert-danger">Contraseña incorrecta</div>';
                }

            } else{

                
                echo'<script>
                    if(window.history.replaceState){
                        window.history.replaceState(null, null, window.location.href);
                    }
                </script>';
                echo '<div class="alert alert-danger">El usuario no existe</div>';
            }

        }
    }

    /**
     * Actualizar registro de la tabla
     */

    static public function ctrActualizarRegistro()
    {
        if(isset($_POST["actualizarNombre"])){

            if (preg_match("/^[a-zA-Z0-9áéíóúüÜÁÉÍÓÚ\s]+$/", $_POST["actualizarNombre"]) &&
                preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["actualizarEmail"])){

                $usuario = ModeloFormularios::mdlSeleccionarRegistro("registros", "token", $_POST["tokenUsuario"]);
                $compararToken = md5($usuario["nombre"] . "+" . $usuario["email"]);
                if ($compararToken == $_POST["tokenUsuario"]) {

                    if ($_POST["actualizarPassword"] != "") {
                        if(preg_match("/^[0-9a-zA-Z]+$/", $_POST["actualizarPassword"])){

                            $password = crypt($_POST["actualizarPassword"], '$5$rounds=5000$jesusaltamiranocarrillo4b$');

                        }
                    } else {
                        $password = $_POST["passwordActual"];
                    }

                    //Actualizar Token
                    if($_POST["nombreActual"] != $_POST["actualizarNombre"] || $_POST["emailActual"] != $_POST["actualizarEmail"] ){

                        $nuevoToken = md5($_POST["actualizarNombre"] . "+" . $_POST["actualizarEmail"]);

                    }else{
                        $nuevoToken = null;
                    }

                    $tabla = "registros";

                    $datos = array(
                            "token" =>$_POST["tokenUsuario"],
                            "nuevoToken"=>$nuevoToken, // Viene un dato si se cambia nombre o email, si no viene vacio
                            "nombre"=>$_POST["actualizarNombre"],
                            "email"=>$_POST["actualizarEmail"],
                            "password"=>$password
                        );

                    $respuesta = ModeloFormularios::mdlActualizarRegistros($tabla, $datos);

                    return $respuesta;
                } else {
                    $respuesta = "error";
                    return $respuesta;
                }

            }else{
                $respuesta = "error";
                return $respuesta;
            }

        }
    }

    /**
     * Eliminar registro
     */

    public function ctrEliminarRegistro()
    {
        if(isset($_POST["eliminarRegistro"])){

            $usuario = ModeloFormularios::mdlSeleccionarRegistro("registros", "token", $_POST["eliminarRegistro"]);
            $compararToken = md5($usuario["nombre"] . "+" . $usuario["email"]);
            if($compararToken == $_POST["eliminarRegistro"]){

                $tabla = "registros";
                $valor = $_POST["eliminarRegistro"];

                $respuesta = ModeloFormularios::mdlEliminarRegistro($tabla, $valor);
                if($respuesta == "ok"){
                    echo '<script>

                    if(window.history.replaceState){
                        window.history.replaceState(null, null, wi ndow.location.href);
                    }
                    window.location = "index.php?pagina=inicio";
                    
                    </script>';
                }

            }

            
        }
    }
}
?>
