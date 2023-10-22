<?php

    class Conexion{

        static public function conectar(){
            $link = new PDO("mysql:host=localhost:3306; dbname=wedding-jac", "Soporte2", "");

            $link -> exec("set names utf8");

            return $link;
        }
    }
