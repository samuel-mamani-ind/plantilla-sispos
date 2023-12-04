<?php
require_once('conexion.php');

class clsProducto{

	function listarProducto($codigo, $nombre, $idcategoria, $estado){
		$sql = "SELECT pro.*, und.descripcion as 'unidad' FROM producto pro INNER JOIN unidad und ON pro.idunidad=und.idunidad WHERE pro.estado<2 AND und.estado<2";
		$parametros = array();

		if($codigo!=""){
			$sql .= " AND pro.codigobarra LIKE :codigo ";
			$parametros[':codigo'] = "%".$codigo."%";
		}

		if($nombre!=""){
			$sql .= " AND pro.nombre LIKE :nombre ";
			$parametros[':nombre'] = "%".$nombre."%";
		}

		if($idcategoria!=""){
			$sql .= " AND pro.idcategoria = :idcategoria ";
			$parametros[':idcategoria'] = $idcategoria; 
		}

		if($estado!=""){
			$sql .= " AND pro.estado = :estado ";
			$parametros[':estado'] = $estado;
		}

		$sql .= " ORDER BY pro.nombre ASC";

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function consultarUnidad(){
		$sql = "SELECT * FROM unidad WHERE estado=1 ";
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

	function insertarProducto($codigo,$nombre,$estado,$pventa,$pcompra,$stock,$stockseguridad,$idunidad,$idcategoria,$idafectacion,$afectoicbper){
		$sql = "INSERT INTO producto(nombre, codigobarra, pventa, pcompra, stock, idunidad, idcategoria, idafectacion, afectoicbper, estado, stockseguridad) VALUES(:nombre, :codigobarra, :pventa, :pcompra, :stock, :idunidad, :idcategoria, :idafectacion, :afectoicbper, :estado, :stockseguridad)";
		$parametros = array(
			":nombre"=>$nombre, 
			":codigobarra"=>$codigo, 
			":pventa"=>$pventa, 
			":pcompra"=>$pcompra, 
			":stock"=>$stock, 
			":idunidad"=>$idunidad, 
			":idcategoria"=>$idcategoria, 
			":idafectacion"=>$idafectacion, 
			":afectoicbper"=>$afectoicbper, 
			":estado"=>$estado, 
			":stockseguridad"=>$stockseguridad
		);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function verificarDuplicado($nombre, $idproducto=0){
		$sql = "SELECT * FROM producto WHERE estado<2 AND nombre=:nombre AND idproducto<>:idproducto";
		$parametros = array(":nombre"=>$nombre, ":idproducto"=>$idproducto);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function consultarProducto($idproducto){
		$sql = "SELECT * FROM producto WHERE idproducto=:idproducto ";
		$parametros = array(":idproducto"=>$idproducto);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function actualizarProducto($idproducto, $codigo,$nombre,$estado,$pventa,$pcompra,$stock,$stockseguridad,$idunidad,$idcategoria,$idafectacion,$afectoicbper){
		$sql = "UPDATE producto SET nombre=:nombre, codigobarra=:codigobarra, pventa=:pventa, pcompra=:pcompra, stock=:stock, idunidad=:idunidad, idcategoria=:idcategoria, idafectacion=:idafectacion, afectoicbper=:afectoicbper, estado=:estado, stockseguridad=:stockseguridad WHERE idproducto=:idproducto";
		$parametros = array(
			":idproducto"=>$idproducto, 
			":nombre"=>$nombre, 
			":codigobarra"=>$codigo, 
			":pventa"=>$pventa, 
			":pcompra"=>$pcompra, 
			":stock"=>$stock, 
			":idunidad"=>$idunidad, 
			":idcategoria"=>$idcategoria, 
			":idafectacion"=>$idafectacion, 
			":afectoicbper"=>$afectoicbper, 
			":estado"=>$estado, 
			":stockseguridad"=>$stockseguridad
		);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function actualizarEstadoProducto($idproducto, $estado){
		$sql = "UPDATE producto SET estado=:estado WHERE idproducto=:idproducto";
		$parametros = array(":estado"=>$estado, ":idproducto"=>$idproducto);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

	function actualizarImagen($idproducto, $imagen){
		$sql = "UPDATE producto SET urlimagen=:imagen WHERE idproducto=:idproducto";
		$parametros = array(":imagen"=>$imagen, ":idproducto"=>$idproducto);

		global $cnx;
		$pre = $cnx->prepare($sql);
		$pre->execute($parametros);
		return $pre;
	}

}
?>