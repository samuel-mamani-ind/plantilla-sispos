<?php
require_once('conexion.php');

class clsUsuario{

	function listarUsuario($nombre, $estado){
		$sql = "SELECT us.*, pe.nombre as 'perfil' FROM usuario us INNER JOIN perfil pe ON us.idperfil=pe.idperfil WHERE us.estado<2 AND pe.estado<2";
		$parametros = array();

		if($nombre!=""){
			$sql .= " AND us.nombre LIKE :nombre ";
			$parametros[':nombre'] = "%".$nombre."%";
		}

		if($estado!=""){
			$sql .= " AND us.estado = :estado ";
			$parametros[':estado'] = $estado;
		}

		$sql .= " ORDER BY us.nombre ASC";

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function insertarUsuario($nombre, $usuario, $clave, $idperfil, $estado){
		$sql = "INSERT INTO usuario(nombre,usuario,clave,idperfil,estado) VALUES(:nombre,:usuario,SHA1(:clave),:idperfil,:estado)";
		$parametros = array(':nombre'=>$nombre, ':usuario'=>$usuario,':clave'=>$clave,':idperfil'=>$idperfil, ':estado'=>$estado);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function verificarUsuario($usuario, $clave){
		$sql = "SELECT * FROM usuario WHERE usuario=:usuario AND clave=SHA1(:clave) AND estado=1";
		$parametros = array(":usuario"=>$usuario,':clave'=>$clave);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function verificarDuplicado($usuario, $idusuario=0){
		$sql = "SELECT * FROM usuario WHERE usuario=:usuario AND idusuario<>:idusuario";
		$parametros = array(':usuario'=>$usuario, ':idusuario'=>$idusuario);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function consultarUsuario($idusuario){
		$sql = "SELECT * FROM usuario WHERE idusuario=:idusuario ";
		$parametros = array(":idusuario"=>$idusuario);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function actualizarUsuario($idusuario, $nombre, $usuario, $clave, $idperfil , $estado){
		$sql = "UPDATE usuario SET nombre=:nombre, usuario=:usuario, idperfil=:idperfil, estado=:estado ";
		$parametros = array(':idusuario'=>$idusuario, ':nombre'=>$nombre, ':usuario'=>$usuario, ':idperfil'=>$idperfil, ':estado'=>$estado);

		if($clave!=""){
			$sql.=",clave = SHA1(:clave)";
			$parametros[':clave'] = $clave;
		}

		$sql.=" WHERE idusuario=:idusuario ";

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function actualizarEstadoUsuario($idusuario, $estado){
		$sql = "UPDATE usuario SET estado=:estado WHERE idusuario=:idusuario";
		$parametros = array(":estado"=>$estado, ":idusuario"=>$idusuario);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

}

?>