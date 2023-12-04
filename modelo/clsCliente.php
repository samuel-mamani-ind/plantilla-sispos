<?php
require_once('conexion.php');

class clsCliente{

	function listarCliente($nombre, $estado){
		$sql = "SELECT * FROM cliente WHERE estado<2";
		$parametros = array();

		if($nombre!=""){
			$sql .= " AND nombre LIKE :nombre ";
			$parametros[':nombre'] = "%".$nombre."%";
		}

		if($estado!=""){
			$sql .= " AND estado = :estado ";
			$parametros[':estado'] = $estado;
		}

		$sql .= " ORDER BY nombre ASC";

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function insertarCliente($nombre, $idtipodocumento, $nrodocumento, $direccion, $estado){
		$sql = "INSERT INTO cliente(nombre, idtipodocumento, nrodocumento, direccion, estado) VALUES(:nombre, :idtipodocumento, :nrodocumento, :direccion, :estado)";
		$parametros = array(":nombre"=>$nombre, ":idtipodocumento"=>$idtipodocumento, ":nrodocumento"=>$nrodocumento, ":direccion"=>$direccion, ":estado"=>$estado);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function verificarDuplicado($nrodocumento, $idcliente=0){
		$sql = "SELECT * FROM cliente WHERE estado<2 AND nrodocumento=:nrodocumento AND idcliente<>:idcliente";
		$parametros = array(":nrodocumento"=>$nrodocumento, ":idcliente"=>$idcliente);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function consultarCliente($idcliente){
		$sql = "SELECT * FROM cliente WHERE idcliente=:idcliente ";
		$parametros = array(":idcliente"=>$idcliente);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function actualizarCliente($idcliente, $nombre, $idtipodocumento, $nrodocumento, $direccion, $estado){
		$sql = "UPDATE cliente SET nombre=:nombre, idtipodocumento=:idtipodocumento, nrodocumento=:nrodocumento, direccion=:direccion, estado=:estado WHERE idcliente=:idcliente";
		$parametros = array(':idcliente'=>$idcliente, ":nombre"=>$nombre, ":idtipodocumento"=>$idtipodocumento, ":nrodocumento"=>$nrodocumento, ":direccion"=>$direccion, ":estado"=>$estado);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function actualizarEstadoCliente($idcliente, $estado){
		$sql = "UPDATE cliente SET estado=:estado WHERE idcliente=:idcliente";
		$parametros = array(":estado"=>$estado, ":idcliente"=>$idcliente);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function consultarTipoDocumneto(){
		$sql = "SELECT * FROM tipodocumento WHERE estado=1 ";

		global $cnx;
		$pre = $cnx->query($sql);
		return $pre;
	}

}


?>