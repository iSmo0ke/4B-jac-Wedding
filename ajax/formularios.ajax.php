<?php 
    require_once("../controladores/formularios.controlador.php");
    require_once("../modelos/formularios.modelo.php");

    ##Clase AJAX
    class AjaxFormularios{
        public $validarEmail;

        public function ajaxValidarEmail(){

            $item = "email";
            $valor= $this->validarEmail;

            ##Validar email existente
            $respuesta = ControladorFormularios::ctrSeleccionarRegistros(
            $item, $valor
            );

            echo json_encode($respuesta);
            //echo '<pre>'; print_r($respuesta); echo '</pre>';

        }

        
    }

    ##Objetos de AJAX que recive la variable POST
    if(isset($_POST["validarEmail"])){
        $validarEmail = new AjaxFormularios();
        $validarEmail -> validarEmail = $_POST["validarEmail"];
        $validarEmail -> ajaxValidarEmail();

    }


?>