<?php
require_once("../modelo/clsUsuario.php");

controlador($_POST['accion']);

function controlador($accion){
	$objUsu = new clsUsuario();

	switch ($accion) {
		case 'INICIAR_SESION':

			$usuario = $_POST['usuario'];
			$pass = $_POST['password'];

			$resultado = $objUsu->verificarUsuario($usuario, $pass);

			$mensaje = "";
			$correcto= 0;
			if($resultado->rowCount()>0){
				$mensaje = "Usuario Encontrado";
				$correcto = 1;

				$datoUsuario = $resultado->fetch(PDO::FETCH_NAMED);
				$_SESSION['idusuario'] = $datoUsuario['idusuario'];
				$_SESSION['nombre'] = $datoUsuario['nombre'];
				$_SESSION['idperfil'] = $datoUsuario['idperfil'];

			}

			$resultado = array("mensaje"=>$mensaje, "correcto"=>$correcto);

			echo json_encode($resultado);

			break;

		case 'NUEVO':
			$resultado = array();
			try {

				
				$nombre =  strtoupper($_POST['nombre']);
				$estado = $_POST['estado'];
				$usuario = $_POST['usuario'];
				$clave = $_POST['clave'];
				$idperfil = $_POST['idperfil'];

				$existeUsuario = $objUsu->verificarDuplicado($usuario);
				if($existeUsuario->rowCount()>0){
					throw new Exception("Existe un usuario Registrado con el mismo nombre", 1);
					
				}
					
				$objUsu->insertarUsuario($nombre, $usuario, $clave, $idperfil, $estado);
				$resultado['correcto']=1;
				$resultado['mensaje'] = 'Usuario Registrado de forma satisfactoria.';

				echo json_encode($resultado);
				
			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje'] = $e->getMessage();
				echo json_encode($resultado);
			}
			break;

		case 'CONSULTAR_USUARIO':
			try {
				$idusuario = $_POST['idusuario'];

				$resultado = $objUsu->consultarUsuario($idusuario);
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
				$idusuario = $_POST['idusuario'];
				$nombre = $_POST['nombre'];
				$estado = $_POST['estado'];
				$usuario = $_POST['usuario'];
				$clave = $_POST['clave'];
				$idperfil = $_POST['idperfil'];

				$existeUsuario = $objUsu->verificarDuplicado($usuario, $idusuario);
				if($existeUsuario->rowCount()>0){
					throw new Exception("Existe un usuario Registrado con el mismo nombre", 1);
					
				}

				$objUsu->actualizarUsuario($idusuario, $nombre, $usuario, $clave, $idperfil , $estado);

				$resultado['correcto']=1;
				$resultado['mensaje']="Usuario actualizado de forma satisfactoria.";
				echo json_encode($resultado);

			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']=$e->getMessage();

				echo json_encode($resultado);
			}
			break;

		case 'CAMBIAR_ESTADO_USUARIO':
			$resultado = array();
			try {
				$idusuario = $_POST['idusuario'];
				$estado = $_POST['estado'];
				$arrayEstado = array('ANULADO','ACTIVADO','ELIMINADO');

				$objUsu->actualizarEstadoUsuario($idusuario, $estado);

				$resultado['correcto']=1;
				$resultado['mensaje']='El Usuario ha sido '.$arrayEstado[$estado].' de forma satisfactoria';

				echo json_encode($resultado);
				
			} catch (Exception $e) {
				$resultado['correcto']=0;
				$resultado['mensaje']=$e->getMessage();

				echo json_encode($resultado);
			}
			break;
			
		default:
			// code...
			break;
	}

}

?>