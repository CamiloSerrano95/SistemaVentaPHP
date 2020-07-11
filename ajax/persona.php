<?php
    require_once("../modelos/Persona.php");

    $persona = new Persona();

    $idpersona = isset($_POST['idpersona']) ? $_POST['idpersona'] : "";
    $tipo_persona = isset($_POST['tipo_persona']) ? $_POST['tipo_persona'] : "";
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : "";
    $tipo_documento = isset($_POST['tipo_documento']) ? $_POST['tipo_documento'] : "";
    $num_documento = isset($_POST['num_documento']) ? $_POST['num_documento'] : "";
    $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : "";
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : "";
    $email = isset($_POST['email']) ? $_POST['email'] : "";

    switch ($_GET['op']) {
        case 'guardaryeditar':
            if (empty($idpersona)) {
                $res = $persona->insertar($tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email);
                echo $res ? "Persona registrada" : "No se pudo registrar la persona";
            } else {
                $res = $persona->editar($idpersona, $tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email);
                echo $res ? "Persona actualizada" : "No se pudo actualizar la persona";
            }
            break;
        case 'eliminar':
            $res = $persona->eliminar($idpersona);
            echo $res ? "Persona eliminada" : "No se pudo eliminar la persona";
            break;
        case 'mostrar':
            $res = $persona->mostrar($idpersona);
            echo json_encode($res);
            break;    
        case 'listarp':
            $res = $persona->listarp();
            $personas = Array();
            while($reg = $res->fetch_object()) {
                $personas[] = array(
                    "0" => "<button class='btn btn-warning' onclick='mostrar({$reg->idpersona})'><i class='fa fa-pencil'></i></button>
                        <button class='btn btn-danger' onclick='eliminar({$reg->idpersona})'><i class='fa fa-trash'></i></button>" ,
                    "1" => $reg->nombre,
                    "2" => $reg->tipo_documento,
                    "3" => $reg->num_documento,
                    "4" => $reg->telefono,
                    "5" => $reg->email
                );
            }

            $res = array(
                "sEcho" => 1,
                "iTotalRecords" => count($personas),
                "iTotalDisplayRecords" => count($personas),
                "aaData" => $personas
            );

            echo json_encode($res);
            break;
        case 'listarc':
            $res = $persona->listarc();
            $personas = Array();
            while($reg = $res->fetch_object()) {
                $personas[] = array(
                    "0" => "<button class='btn btn-warning' onclick='mostrar({$reg->idpersona})'><i class='fa fa-pencil'></i></button>
                        <button class='btn btn-danger' onclick='eliminar({$reg->idpersona})'><i class='fa fa-trash'></i></button>" ,
                    "1" => $reg->nombre,
                    "2" => $reg->tipo_documento,
                    "3" => $reg->num_documento,
                    "4" => $reg->telefono,
                    "5" => $reg->email
                );
            }

            $res = array(
                "sEcho" => 1,
                "iTotalRecords" => count($personas),
                "iTotalDisplayRecords" => count($personas),
                "aaData" => $personas
            );

            echo json_encode($res);
            break;
    }

?>