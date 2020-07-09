<?php
    require_once("../modelos/Categoria.php");

    $categoria = new Categoria();

    $idcategoria = isset($_POST['idcategoria']) ? $_POST['idcategoria'] : "";
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : "";
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : "";

    switch ($_GET['op']) {
        case 'guardaryeditar':
            if (empty($idcategoria)) {
                $res = $categoria->insertar($nombre, $descripcion);
                echo $res ? "Categoria registrada" : "No se pudo registrar la categoria";
            } else {
                $res = $categoria->editar($idcategoria, $nombre, $descripcion);
                echo $res ? "Categoria actualizada" : "No se pudo actualizar la categoria";
            }
            break;
        case 'desactivar':
            $res = $categoria->desactivar($idcategoria);
            echo $res ? "Categoria desactivada" : "No se pudo desactivar la categoria";
            break;
        case 'activar':
            $res = $categoria->activar($idcategoria);
            echo $res ? "Categoria activada" : "No se pudo activar la categoria";
            break;
        case 'mostrar':
            $res = $categoria->mostrar($idcategoria);
            echo json_encode($res);
            break;    
        case 'listar':
            $res = $categoria->listar();
            $categorias = Array();
            while($reg = $res->fetch_object()) {
                $categorias[] = array(
                    "0" => ($reg->condicion) ? "<button class='btn btn-warning' onclick='mostrar({$reg->idcategoria})'><i class='fa fa-pencil'></i></button>
                    <button class='btn btn-danger' onclick='desactivar({$reg->idcategoria})'><i class='fa fa-close'></i></button>" : 
                    "<button class='btn btn-warning' onclick='mostrar({$reg->idcategoria})'><i class='fa fa-pencil'></i></button>
                    <button class='btn btn-primary' onclick='activar({$reg->idcategoria})'><i class='fa fa-check'></i></button>",
                    "1" => $reg->nombre,
                    "2" => $reg->descripcion,
                    "3" => ($reg->condicion) ? "<span class='label bg-green'>Activado</span>" : "<span class='label bg-red'>Desactivado</span>",
                );
            }

            $res = array(
                "sEcho" => 1,
                "iTotalRecords" => count($categorias),
                "iTotalDisplayRecords" => count($categorias),
                "aaData" => $categorias
            );

            echo json_encode($res);
            break;
    }

?>