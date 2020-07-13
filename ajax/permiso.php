<?php
    require_once("../modelos/Permiso.php");

    $permiso = new Permiso();

    switch ($_GET['op']) {
        case 'listar':
            $res = $permiso->listar();
            $permisos = Array();
            while($reg = $res->fetch_object()) {
                $permisos[] = array(
                    "0" => $reg->nombre,
                );
            }

            $res = array(
                "sEcho" => 1,
                "iTotalRecords" => count($permisos),
                "iTotalDisplayRecords" => count($permisos),
                "aaData" => $permisos
            );

            echo json_encode($res);
            break;
    }

?>