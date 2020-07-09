<?php
    require_once("../modelos/Articulo.php");

    $articulo = new Articulo();

    $idarticulo = isset($_POST['idarticulo']) ? $_POST['idarticulo'] : "";
    $idcategoria = isset($_POST['idcategoria']) ? $_POST['idcategoria'] : "";
    $codigo = isset($_POST['codigo']) ? $_POST['codigo'] : "";
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : "";
    $stock = isset($_POST['stock']) ? $_POST['stock'] : "";
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : "";
    $imagen = isset($_POST['imagen']) ? $_POST['imagen'] : "";
    

    switch ($_GET['op']) {
        case 'guardaryeditar':
            if (!file_exists($_FILES['images']['tmp_name']) || !is_uploaded_file($_FILES['images']['tmp_name'])) {
                $imagen = "";
            } else {
                $ext = explode(".", $_FILES['image']['name']);
                if ($_FILES['image']['type'] == "image/jpg" || $_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/png") {
                    $imagen = round(microtime(true)).".".end($ext);
                    move_uploaded_file($_FILES['images']['tmp_name'], "../files/articulos/{$imagen}");
                }
            }

            if (empty($idarticulo)) {
                $res = $articulo->insertar($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen);
                echo $res ? "Articulo registrado" : "No se pudo registrar el articulo";
            } else {
                $res = $articulo->editar($idarticulo, $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen);
                echo $res ? "Articulo actualizado" : "No se pudo actualizar el articulo";
            }
            break;
        case 'desactivar':
            $res = $articulo->desactivar($idarticulo);
            echo $res ? "Articulo desactivado" : "No se pudo desactivar el articulo";
            break;
        case 'activar':
            $res = $articulo->activar($idarticulo);
            echo $res ? "Articulo activado" : "No se pudo activar el articulo";
            break;
        case 'mostrar':
            $res = $articulo->mostrar($idarticulo);
            echo json_encode($res);
            break;    
        case 'listar':
            $res = $articulo->listar();
            $articulos = Array();
            while($reg = $res->fetch_object()) {
                $articulos[] = array(
                    "0" => ($reg->condicion) ? "<button class='btn btn-warning' onclick='mostrar({$reg->idarticulo})'><i class='fa fa-pencil'></i></button>
                    <button class='btn btn-danger' onclick='desactivar({$reg->idarticulo})'><i class='fa fa-close'></i></button>" : 
                    "<button class='btn btn-warning' onclick='mostrar({$reg->idarticulo})'><i class='fa fa-pencil'></i></button>
                    <button class='btn btn-primary' onclick='activar({$reg->idarticulo})'><i class='fa fa-check'></i></button>",
                    "1" => $reg->nombre,
                    "2" => $reg->categoria,
                    "3" => $reg->codigo,
                    "4" => $reg->stock,
                    "5" => "<img src='../files/articulos/$reg->imagen' height='50px' width='50px'>",
                    "6" => ($reg->condicion) ? "<span class='label bg-green'>Activado</span>" : "<span class='label bg-red'>Desactivado</span>",
                );
            }

            $res = array(
                "sEcho" => 1,
                "iTotalRecords" => count($articulos),
                "iTotalDisplayRecords" => count($articulos),
                "aaData" => $articulos
            );

            echo json_encode($res);
            break;
    }

?>