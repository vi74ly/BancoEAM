<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CargoDAO
 *
 * @author TecnoSystem
 */
class CargoDAO {

    //put your code here

    private $con;
    private $object;

    function CargoDAO($tipo) {
        if ($tipo === 1) {
            require 'Infraestructura/Conexion.php';
        } else {
            require '../Infraestructura/Conexion.php';
        }
        $this->object = new Conexion();
        $this->con = $this->object->conectar();
    }

    public function guardar(Cargo $obj) {
        $sql = "insert into cargo(nombre,salario,descripcion,horaria) "
                . " values('" . $obj->getNombre() . "','" . $obj->getSalario() .
                "','" . $obj->getDescripcion() . "','" . $obj->getIntensidad() . "');";
//          echo ($sql);

        $resultado = $this->object->ejecutar($sql);
//        echo $resultado;
        $this->object->respuesta($resultado, 'Cargo');
    }

    public function Buscar(Cargo $obj) {
        $sql = "SELECT nombre,salario,descripcion,horaria from cargo " . "where nombre='" .
                $obj->getNombre() . "';";
        $resultado = $this->object->ejecutar($sql);
        $this->construirBusqueda($resultado);
    }

    public function construirBusqueda($resultado) {
        $vec = pg_fetch_row($resultado);

        if (isset($vec) && $vec[0] != "") {
            $lista = "nombre=" . $vec[0] . "&&";
            $lista .= "salario=" . $vec[1] . "&&";
            $lista .= "descripcion=" . $vec[2] . "&&";
            $lista .= "horaria=" . $vec[3];

            header('Location:../index.php?page=Cargo&&' . $lista);
        } else {
            $mensaje = "No se encontro informacion";
            header('Location:../index.php?page=Cargo&&message=' . $mensaje);
        }
    }

    public function modificar(Cargo $obj) {
        $sql = "update cargo set nombre='" . $obj->getNombre() .
                "',salario='" . $obj->getSalario() . "',descripcion='" . $obj->getDescripcion() . "',horaria='" . $obj->getIntensidad() .
                "' where nombre='" . $obj->getNombre() . "'";
        $resultado = $this->object->ejecutar($sql);
        $this->object->respuesta($resultado, 'Cargo');
    }

    public function eliminar(Cargo $obj) {
        $sql = "delete from cargo where nombre= '" . $obj->getNombre() . "';";
        $resultado = $this->object->ejecutar($sql);
        $this->object->respuesta($resultado, 'Cargo');
    }

    public function listar(Cargo $obj) {
        $sql = "select nombre,salario,descripcion,horaria from cargo";
        $resultado = $this->object->ejecutar($sql);
        $this->construirListado($resultado);
    }

    public function construirListado($resultado) {
        $cadenaHTML = "";
        if ($resultado && pg_num_rows($resultado) > 0) {
            $cadenaHTML = "<table border='1'>";
            $cadenaHTML .="<tr>";
            $cadenaHTML .="<th>Nombre</th>";
            $cadenaHTML .="<th>Salario</th>";
            $cadenaHTML .="<th>Descripcion</th>";
            $cadenaHTML .="<th>Horaria</th>";
            $cadenaHTML .="</tr>";


            for ($cont = 0; $cont < pg_num_rows($resultado); $cont ++) {

                $cadenaHTML .="<tr>";
                $cadenaHTML .= "<td>" . pg_result($resultado, $cont, 0) . "</td>";
                $cadenaHTML .= "<td>" . pg_result($resultado, $cont, 1) . "</td>";
                $cadenaHTML .= "<td>" . pg_result($resultado, $cont, 2) . "</td>";
                $cadenaHTML .= "<td>" . pg_result($resultado, $cont, 3) . "</td>";
                $cadenaHTML .="</tr>";
            }

            $cadenaHTML .= "</table>";
        } else {
            $cadenaHTML .= "<b>No hay registros en la base de datos</b>";
        }
        echo $cadenaHTML;
    }

}
