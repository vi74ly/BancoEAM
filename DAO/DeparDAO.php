<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DeparDAO
 *
 * @author TecnoSystem
 */
class DeparDAO {
    //put your code here
    
    private $con;
    private $object;

    function DeparDAO($tipo) {
        if ($tipo === 1) {
            require 'Infraestructura/Conexion.php';
        } else {
            require '../Infraestructura/Conexion.php';
        }
        $this->object = new Conexion();
        $this->con = $this->object->conectar();
    }

    public function guardar(Departamento $obj) {
        $sql = "insert into Departamento(nombre,descripcion,pais) "
                . " values('" . $obj->getNombre() ."','".$obj->getDescripcion()."','". $obj->getPais() . "');";
//          echo ($sql);

        $resultado = $this->object->ejecutar($sql);
//        echo $resultado;
        $this->object->respuesta($resultado, 'Departamento');
    }

    public function Buscar(Departamento $obj) {
        $sql = "SELECT nombre,descripcion,pais from departamento " . "where nombre='" .
                $obj->getNombre() . "';";
        $resultado = $this->object->ejecutar($sql);
        $this->construirBusqueda($resultado);
    }

    public function construirBusqueda($resultado) {
        $vec = pg_fetch_row($resultado);

        if (isset($vec) && $vec[0] != "") {
            $lista = "nombre=" . $vec[0] . "&&";
            $lista = "descripcion=" . $vec[1] . "&&";
            $lista .= "pais=" . $vec[2];

            header('Location:../index.php?page=Departamento&&' . $lista);
        } else {
            $mensaje = "No se encontro informacion";
            header('Location:../index.php?page=Departamento&&message=' . $mensaje);
        }
    }

    public function modificar(Departamento $obj) {
        $sql = "update departamento set nombre='" . $obj->getNombre() ."',descripcion='" . $obj->getDescripcion() ."',pais='" . $obj->getPais() .
                "' where nombre='" . $obj->getNombre() . "'";
        $resultado = $this->object->ejecutar($sql);
        $this->object->respuesta($resultado, 'Departamento');
    }

    public function eliminar(Departamento $obj) {
        $sql = "delete from departamento where nombre= '" . $obj->getNombre() . "';";
        $resultado = $this->object->ejecutar($sql);
        $this->object->respuesta($resultado, 'Departamento');
    }

    public function listar(Departamento $obj) {
        $sql = "select id,nombre,descripcion,pais from departamento";
        $resultado = $this->object->ejecutar($sql);
        $this->construirListado($resultado);
    }

    public function construirListado($resultado) {
        $cadenaHTML = "";
        if ($resultado && pg_num_rows($resultado) > 0) {
            $cadenaHTML = "<table border='1'>";
            $cadenaHTML .="<tr>";
            $cadenaHTML .="<th>ID</th>";
            $cadenaHTML .="<th>Nombre</th>";
            $cadenaHTML .="<th>Descripcion</th>";
            $cadenaHTML .="<th>Pais</th>";
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
    
    
    function Cargar_Combo(){
        $sql = "SELECT * from pais";
        $resultadoCombo = $this->object->ejecutar($sql);
        echo gettype($resultadoCombo);
        die("Here 2");
        return $resultado;
    }
    
}
