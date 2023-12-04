<?php
require_once('../modelo/clsPerfil.php');

controlador($_POST['accion']);

function controlador($accion){
	$objPer = new clsPerfil();

	switch ($accion) {
		case 'NUEVO':
			$resultado = array();
			try {

				
				$nombre = $_POST['nombre'];
				$estado = $_POST['estado'];

				$existePerfil = $objPer->verificarDuplicado($nombre);
				if($existePerfil->rowCount()>0){
					throw new Exception("Existe un Perfil Registrado con el mismo nombre", 1);
					
				}
					
				$objPer->insertarPerfil($nombre, $estado);
				$resultado['correcto']=1;
				$resultado['mensaje'] = 'Perfil Registrado de forma satisfactoria.';

				echo json_encode($resultado);
				
			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje'] = $e->getMessage();
				echo json_encode($resultado);
			}
			break;

		case 'CONSULTAR_PERFIL':
			try {
				$idperfil = $_POST['idperfil'];

				$resultado = $objPer->consultarPerfil($idperfil);
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
				$idperfil = $_POST['idperfil'];
				$nombre = $_POST['nombre'];
				$estado = $_POST['estado'];

				$existePerfil = $objPer->verificarDuplicado($nombre, $idperfil);
				if($existePerfil->rowCount()>0){
					throw new Exception("Existe un Perfil Registrado con el mismo nombre", 1);
					
				}

				$objPer->actualizarPerfil($idperfil, $nombre, $estado);

				$resultado['correcto']=1;
				$resultado['mensaje']="Perfil actualizado de forma satisfactoria.";
				echo json_encode($resultado);

			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']=$e->getMessage();

				echo json_encode($resultado);
			}
			break;

		case 'CAMBIAR_ESTADO_PERFIL':
			$resultado = array();
			try {
				$idperfil = $_POST['idperfil'];
				$estado = $_POST['estado'];
				$arrayEstado = array('ANULADO','ACTIVADO','ELIMINADO');

				$objPer->actualizarEstadoPerfil($idperfil, $estado);

				$resultado['correcto']=1;
				$resultado['mensaje']='El perfil ha sido '.$arrayEstado[$estado].' de forma satisfactoria';

				echo json_encode($resultado);
				
			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']=$e->getMessage();

				echo json_encode($resultado);
			}
			break;

		case 'CONSULTAR_ACCESO':
			try {
				$idperfil = $_POST['idperfil'];

				$resultado = $objPer->listarOpciones($idperfil);
				$resultado = $resultado->fetchAll(PDO::FETCH_NAMED);
				echo json_encode($resultado);
				
			} catch (Exception $e) {
				$resultado = array('correcto'=>0, 'mensaje'=>$e->getMessage());
				echo json_encode($resultado);
			}
			break;

		case 'VERIFICAR_ACCESO':
			$resultado = array();
			try {
				$idperfil = $_POST['idperfil'];
				$idopcion = $_POST['idopcion'];
				$estado = $_POST['estado'];
				

				$existePermiso = $objPer->VerificarPermiso($idperfil, $idopcion);

				if($existePermiso->rowCount()>0){
					$objPer->actualizarPermiso($idperfil, $idopcion, $estado);
				}else{
					$objPer->insertarAcceso($idperfil, $idopcion);
				}

				$resultado['correcto']=1;
				$resultado['mensaje']='Permiso Actualizado de forma Correcta';

				echo json_encode($resultado);
				
			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']=$e->getMessage();

				echo json_encode($resultado);
			}
			break;
		
		default:
			echo "No ha definido una accion";
			break;
	}

}

?>