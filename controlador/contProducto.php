<?php
require_once('../modelo/clsProducto.php');

controlador($_POST['accion']);

function controlador($accion){
	$objPro = new clsProducto();

	switch ($accion) {
		case 'NUEVO':
			$resultado = array();
			try {

				$codigo = $_POST['codigobarra'];
				$nombre = $_POST['nombre'];
				$estado = $_POST['estado'];
				$pventa = $_POST['pventa'];
				$pcompra = $_POST['pcompra'];
				$stock = $_POST['stock'];
				$stockseguridad = $_POST['stockseguridad'];
				$idunidad = $_POST['idunidad'];
				$idcategoria = $_POST['idcategoria'];
				$idafectacion = $_POST['idafectacion'];
				$afectoicbper = $_POST['afectoicbper'];

				$existeProducto = $objPro->verificarDuplicado($nombre);
				if($existeProducto->rowCount()>0){
					throw new Exception("Existe un producto Registrado con el mismo nombre", 1);
					
				}
					
				$objPro->insertarProducto($codigo,$nombre,$estado,$pventa,$pcompra,$stock,$stockseguridad,$idunidad,$idcategoria,$idafectacion,$afectoicbper);
				$resultado['correcto']=1;
				$resultado['mensaje'] = 'Producto Registrado de forma satisfactoria.';

				echo json_encode($resultado);
				
			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje'] = $e->getMessage();
				echo json_encode($resultado);
			}
			break;

		case 'CONSULTAR_PRODUCTO':
			try {
				$idproducto = $_POST['idproducto'];

				$resultado = $objPro->consultarProducto($idproducto);
				$resultado = $resultado->fetch(PDO::FETCH_NAMED);
				echo json_encode($resultado);
				
			} catch (Exception $e) {
				$resultado = array('correcto'=>0, 'mensaje'=>$e->getMessage());
				echo json_encode($resultado);
			}
			break;

		case 'ACTUALIZAR':
			$resultado = array();
			try {
				$idproducto = $_POST['idproducto'];
				$codigo = $_POST['codigobarra'];
				$nombre = $_POST['nombre'];
				$estado = $_POST['estado'];
				$pventa = $_POST['pventa'];
				$pcompra = $_POST['pcompra'];
				$stock = $_POST['stock'];
				$stockseguridad = $_POST['stockseguridad'];
				$idunidad = $_POST['idunidad'];
				$idcategoria = $_POST['idcategoria'];
				$idafectacion = $_POST['idafectacion'];
				$afectoicbper = $_POST['afectoicbper'];

				$existeProducto= $objPro->verificarDuplicado($nombre, $idproducto);
				if($existeProducto->rowCount()>0){
					throw new Exception("Existe un Producto Registrado con el mismo nombre", 1);
					
				}

				$objPro->actualizarProducto($idproducto, $codigo,$nombre,$estado,$pventa,$pcompra,$stock,$stockseguridad,$idunidad,$idcategoria,$idafectacion,$afectoicbper);

				$resultado['correcto']=1;
				$resultado['mensaje']="Producto actualizado de forma satisfactoria.";
				echo json_encode($resultado);

			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']=$e->getMessage();

				echo json_encode($resultado);
			}
			break;

		case 'CAMBIAR_ESTADO_PRODUCTO':
			$resultado = array();
			try {
				$idproducto = $_POST['idproducto'];
				$estado = $_POST['estado'];
				$arrayEstado = array('ANULADA','ACTIVADA','ELIMINADA');

				$objPro->actualizarEstadoProducto($idproducto, $estado);

				$resultado['correcto']=1;
				$resultado['mensaje']='El Producto ha sido '.$arrayEstado[$estado].' de forma satisfactoria';

				echo json_encode($resultado);
				
			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']=$e->getMessage();

				echo json_encode($resultado);
			}
			break;

		case 'SUBIR_IMAGEN':
			try {

				if(empty($_FILES)) {
					throw new Exception("No se encontraron archivos para cargar.", 123);
				}

				$idproducto = $_POST['idproducto'];
				$archivo = $_FILES['uploadFile'];
				$ruta = "imagen/productos/IMG_".$idproducto.$archivo["name"];
				move_uploaded_file($archivo["tmp_name"], '../'.$ruta);
				$objPro->actualizarImagen($idproducto, $ruta);

				echo '[]';
				
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			break;
		
		default:
			echo "No ha definido una accion";
			break;
	}

}

?>