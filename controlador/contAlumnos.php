<?php
require_once('../modelo/clsAlumnos.php');

controlador($_POST['accion']);

function controlador($accion){
	$objAlm = new clsAlumnos();

	switch ($accion) {
		case 'NUEVO':
			$resultado = array();
			try {

				$rude = $_POST['rude'];
				$nombre = $_POST['nombre'];
				$idnivel = $_POST['idnivel'];
				$carnet = $_POST['carnet'];
				$idcategoria = $_POST['idcategoria'];
				$edad = $_POST['edad'];
				$fecha_nac = $_POST['fecha_nac'];
				$direccion = $_POST['direccion'];
				$telefono = $_POST['telefono'];
				$sexo = $_POST['sexo'];
				$estado = $_POST['estado'];

				$existeProducto = $objAlm->verificarDuplicado($rude);
				if($existeProducto->rowCount()>0){
					throw new Exception("Existe un alumno Registrado con el mismo RUDE", 1);
					
				}
					
				$objAlm->insertarProducto($rude,$nombre,$idnivel,$carnet,$idcategoria,$edad,$fecha_nac,$direccion,$telefono,$sexo,$estado);
				$resultado['correcto']=1;
				$resultado['mensaje'] = 'Alumno Registrado de forma satisfactoria.';

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

				$resultado = $objAlm->consultarProducto($idproducto);
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
				$rude = $_POST['rude'];
				$nombre = $_POST['nombre'];
				$idnivel = $_POST['idnivel'];
				$carnet = $_POST['carnet'];
				$idcategoria = $_POST['idcategoria'];
				$edad = $_POST['edad'];
				$fecha_nac = $_POST['fecha_nac'];
				$direccion = $_POST['direccion'];
				$telefono = $_POST['telefono'];
				$sexo = $_POST['sexo'];

				$existeProducto= $objAlm->verificarDuplicado($rude, $idproducto);
				if($existeProducto->rowCount()>0){
					throw new Exception("Existe un Alumno Registrado con el mismo RUDE", 1);
					
				}

				$objAlm->actualizarProducto($idproducto,$rude,$nombre,$idnivel,$carnet,$idcategoria,$edad,$fecha_nac,$direccion,$telefono,$sexo);

				$resultado['correcto']=1;
				$resultado['mensaje']="Alumno actualizado de forma satisfactoria.";
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

				$objAlm->actualizarEstadoProducto($idproducto, $estado);

				$resultado['correcto']=1;
				$resultado['mensaje']='El Registro ha sido '.$arrayEstado[$estado].' de forma satisfactoria';

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
				$objAlm->actualizarImagen($idproducto, $ruta);

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