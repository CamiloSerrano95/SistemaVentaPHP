<?php 
    require "../config/Conexion.php";

    Class Usuario {
	public function __construct(){}

	public function insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $permisos) {
		$sql="INSERT INTO usuario (nombre,tipo_documento,num_documento,direccion,telefono,email,cargo,login,clave,imagen,condicion)
		VALUES ('$nombre','$tipo_documento','$num_documento','$direccion','$telefono','$email','$cargo','$login','$clave','$imagen','1')";

		$nuevo_usuario = ejecutarConsulta_retornarID($sql);
		$numero_permisos = 0;
		$sw = true;

		while($numero_permisos < count($permisos)) {
			$sql_detalles = "INSERT INTO usuario_permiso (idusuario, idpermiso) 
				VALUES ('$nuevo_usuario', '$permisos[$numero_permisos]')";

			ejecutarConsulta($sql_detalles) or $sw = false;
			$numero_permisos = $numero_permisos + 1;
		}
		return $sw;
	}

	public function editar($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $permisos) {
		$sql="UPDATE usuario SET nombre = '$nombre', tipo_documento = '$tipo_documento', num_documento = '$num_documento', direccion = '$direccion', telefon o= '$telefono', email = '$email', cargo ='$cargo', login = '$login', clave= '$clave', imagen='$imagen' WHERE idusuario = '$idusuario'";
		ejecutarConsulta($sql);

		$sql_delete = "DELETE FROM usuario_permiso WHERE idusuario = '$idusuario'";
		ejecutarConsulta($sql_delete);

		$numero_permisos = 0;
		$sw = true;

		while($numero_permisos < count($permisos)) {
			$sql_detalles = "INSERT INTO usuario_permiso (idusuario, idpermiso) 
				VALUES ('$idusuario', '$permisos[$numero_permisos]')";

			ejecutarConsulta($sql_detalles) or $sw = false;
			$numero_permisos = $numero_permisos + 1;
		}
		return $sw;
	}

	public function desactivar($idusuario) {
		$sql="UPDATE usuario SET condicion = '0' WHERE idusuario = '$idusuario'";
		return ejecutarConsulta($sql);
	}

	public function activar($idusuario) {
		$sql="UPDATE usuario SET condicion = '1' WHERE idusuario = '$idusuario'";
		return ejecutarConsulta($sql);
	}

	public function mostrar($idusuario) {
		$sql="SELECT * FROM usuario WHERE idusuario =' $idusuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listar() {
		$sql="SELECT * FROM usuario";
		return ejecutarConsulta($sql);		
	}
	
	public function listarmarcados($idusuario) {
		$sql = "SELECT * FROM usuario_permiso WHERE idusuario = '$idusuario'";
		return ejecutarConsulta($sql);
	}

	public function verificar($login, $clave) {
    	$sql="SELECT idusuario,nombre,tipo_documento,num_documento,telefono,email,cargo,imagen,login FROM usuario WHERE login='$login' AND condicion='1'"; 
		return ejecutarConsulta($sql);
    }
}