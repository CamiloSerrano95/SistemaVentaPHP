<?php
    session_start();
    require_once "../modelos/Usuario.php";

    $usuario = new Usuario();

    $idusuario = isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
    $nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
    $tipo_documento = isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
    $num_documento = isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
    $direccion = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
    $telefono = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
    $email = isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
    $cargo = isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):"";
    $login = isset($_POST["login"])? limpiarCadena($_POST["login"]):"";
    $clave = isset($_POST["clave"])? limpiarCadena($_POST["clave"]):"";
    $imagen = isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";

    switch ($_GET["op"]){
        case 'guardaryeditar':
            if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
                $imagen=$_POST["imagenactual"];
            } else {
                $ext = explode(".", $_FILES["imagen"]["name"]);
                if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png") {
                    $imagen = round(microtime(true)) . '.' . end($ext);
                    move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);
                }
            }

            $clavehash = hash("SHA256", $clave);

            if (empty($idusuario)){
                $res = $usuario->insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clavehash,$imagen,$_POST['permiso']);
                echo $res ? "Usuario registrado" : "No se pudieron registrar todos los datos del usuario";
            } else {
                $res = $usuario->editar($idusuario,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clavehash,$imagen,$_POST['permiso']);
                echo $res ? "Usuario actualizado" : "Usuario no se pudo actualizar";
            }
        break;

        case 'desactivar':
            $res = $usuario->desactivar($idusuario);
            echo $res ? "Usuario Desactivado" : "Usuario no se puede desactivar";
        break;

        case 'activar':
            $res = $usuario->activar($idusuario);
            echo $res ? "Usuario activado" : "Usuario no se puede activar";
        break;

        case 'mostrar':
            $res = $usuario->mostrar($idusuario);
            echo json_encode($res);
        break;

        case 'listar':
            $res = $usuario->listar();
            $usuarios = Array();

            while ($reg = $res->fetch_object()){
                $usuarios[] = array(
                    "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.
                        ' <button class="btn btn-danger" onclick="desactivar('.$reg->idusuario.')"><i class="fa fa-close"></i></button>':
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.
                        ' <button class="btn btn-primary" onclick="activar('.$reg->idusuario.')"><i class="fa fa-check"></i></button>',
                    "1"=>$reg->nombre,
                    "2"=>$reg->tipo_documento,
                    "3"=>$reg->num_documento,
                    "4"=>$reg->telefono,
                    "5"=>$reg->email,
                    "6"=>$reg->login,
                    "7"=>"<img src='../files/usuarios/".$reg->imagen."' height='50px' width='50px'>",
                    "8"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
                    '<span class="label bg-red">Desactivado</span>'
                    );
            }
            
            $res = array(
                "sEcho" => 1,
                "iTotalRecords" => count($usuarios),
                "iTotalDisplayRecords" => count($usuarios),
                "aaData" => $usuarios
            );
            echo json_encode($res);
        break;

        case 'permisos':
            require_once "../modelos/Permiso.php";

            $permiso = new Permiso();
            $permisos = $permiso->listar();

            $id = $_GET['id'];
            $marcados = $usuario->listarmarcados($id);
            $valores = array();

            while ($per = $marcados->fetch_object()) {
                array_push($valores, $per->idpermiso);
            }

            while ($reg = $permisos->fetch_object()) {
                $sw = in_array($reg->idpermiso, $valores) ? 'checked':'';
                echo "<li> <input type='checkbox' {$sw} name='permiso[]' value='$reg->idpermiso'> {$reg->nombre}</li>";
            }
        break;

        case 'verificar':
            $logina = $_POST['logina'];
            $clavea = $_POST['clavea'];

            $clavehash = hash("SHA256", $clavea);

            $res = $usuario->verificar($logina, $clavehash);

            $fetch = $res->fetch_object();
            
            if (isset($fetch)) {
                $_SESSION['idusuario'] = $fetch->idusuario;
                $_SESSION['nombre'] = $fetch->nombre;
                $_SESSION['imagen'] = $fetch->imagen;
                $_SESSION['login'] = $fetch->login;

                $marcados = $usuario->listarmarcados($fetch->idusuario);

                $valores = array();

                while ($per = $marcados->fetch_object()) {
                    array_push($valores, $per->idpermiso);
                }

                in_array(1, $valores) ? $_SESSION['escritorio'] = 1:$_SESSION['escritorio'] = 0;
                in_array(2, $valores) ? $_SESSION['almacen'] = 1:$_SESSION['almacen'] = 0;
                in_array(3, $valores) ? $_SESSION['compras'] = 1:$_SESSION['compras'] = 0;
                in_array(4, $valores) ? $_SESSION['ventas'] = 1:$_SESSION['ventas'] = 0;
                in_array(5, $valores) ? $_SESSION['acceso'] = 1:$_SESSION['acceso'] = 0;
                in_array(6, $valores) ? $_SESSION['consultac'] = 1:$_SESSION['consultac'] = 0;
                in_array(7, $valores) ? $_SESSION['consultav'] = 1:$_SESSION['consultav'] = 0;
            }
            
            echo json_encode($fetch);
        break;

        case 'salir':
            session_unset();
            session_destroy();
            header("Location: ../index.php");
        break;
    }
?>