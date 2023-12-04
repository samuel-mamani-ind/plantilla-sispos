<?php
require_once('conexion.php');

class clsAlumnos{

	function listarProducto($codigo, $nombre, $idcategoria, $estado){
		$sql = "SELECT alm.*, niv.descripcion as 'nivel', cat.nombre as 'curso' 
		FROM alumnos alm 
		INNER JOIN nivel niv ON alm.idnivel=niv.idnivel 
		INNER JOIN categoria cat ON alm.idcategoria=cat.idcategoria
		WHERE alm.estado<2 AND niv.estado<2 AND cat.estado<2";
		$parametros = array();

		if($codigo!=""){
			$sql .= " AND alm.rude LIKE :codigo ";
			$parametros[':codigo'] = "%".$codigo."%";
		}

		if($nombre!=""){
			$sql .= " AND alm.nombre LIKE :nombre ";
			$parametros[':nombre'] = "%".$nombre."%";
		}

		if($idcategoria!=""){
			$sql .= " AND alm.idcategoria = :idcategoria ";
			$parametros[':idcategoria'] = $idcategoria; 
		}

		if($estado!=""){
			$sql .= " AND alm.estado = :estado ";
			$parametros[':estado'] = $estado;
		}

		$sql .= " ORDER BY alm.nombre ASC";

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function consultarUnidad(){
		$sql = "SELECT * FROM nivel WHERE estado=1 ";
		$parametros = array();

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function consultarAfectacion(){
		$sql = "SELECT * FROM afectacion";
		$parametros = array();

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function insertarProducto($rude,$nombre,$idnivel,$carnet,$idcategoria,$edad,$fecha_nac,$direccion,$telefono,$sexo,$estado){
		$sql = "INSERT INTO alumnos(nombre, rude, direccion, idnivel, idcategoria, telefono, carnet, estado, edad, sexo, fecha_nac) VALUES(:nombre, :rude, :direccion, :idnivel, :idcategoria, :telefono, :carnet, :estado, :edad, :sexo, :fecha_nac)";
		$parametros = array(
			":nombre"=>$nombre, 
			":rude"=>$rude, 
			":direccion"=>$direccion, 
			":idnivel"=>$idnivel, 
			":idcategoria"=>$idcategoria, 
			":telefono"=>$telefono, 
			":carnet"=>$carnet, 
			":estado"=>$estado, 
			":edad"=>$edad, 
			":sexo"=>$sexo, 
			":fecha_nac"=>$fecha_nac
		);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function verificarDuplicado($rude, $idproducto=0){
		$sql = "SELECT * FROM alumnos WHERE estado<2 AND rude=:rude AND idproducto<>:idproducto";
		$parametros = array(":rude"=>$rude, ":idproducto"=>$idproducto);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function consultarProducto($idproducto){
		$sql = "SELECT * FROM alumnos WHERE idproducto=:idproducto ";
		$parametros = array(":idproducto"=>$idproducto);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function actualizarProducto($idproducto,$rude,$nombre,$idnivel,$carnet,$idcategoria,$edad,$fecha_nac,$direccion,$telefono,$sexo){
		$sql = "UPDATE alumnos SET nombre=:nombre, rude=:rude, direccion=:direccion, idnivel=:idnivel, idcategoria=:idcategoria, telefono=:telefono, carnet=:carnet, edad=:edad, sexo=:sexo, fecha_nac=:fecha_nac WHERE idproducto=:idproducto";
		$parametros = array(
			":idproducto"=>$idproducto, 
			":nombre"=>$nombre, 
			":rude"=>$rude, 
			":direccion"=>$direccion, 
			":idnivel"=>$idnivel, 
			":idcategoria"=>$idcategoria, 
			":telefono"=>$telefono, 
			":carnet"=>$carnet, 
			":edad"=>$edad, 
			":fecha_nac"=>$fecha_nac, 
			":sexo"=>$sexo
		);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function actualizarEstadoProducto($idproducto, $estado){
		$sql = "UPDATE alumnos SET estado=:estado WHERE idproducto=:idproducto";
		$parametros = array(":estado"=>$estado, ":idproducto"=>$idproducto);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function actualizarImagen($idproducto, $imagen){
		$sql = "UPDATE alumnos SET urlimagen=:imagen WHERE idproducto=:idproducto";
		$parametros = array(":imagen"=>$imagen, ":idproducto"=>$idproducto);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

}
?>