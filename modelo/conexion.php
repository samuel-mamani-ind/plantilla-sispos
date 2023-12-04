<?php
	session_start();
	if(!isset($_SESSION['idusuario'])){
		if($_POST['accion']!="INICIAR_SESION"){
			header("Location: index.php");
		}
	}

	$manejador = "mysql";
	$servidor = "localhost";
	$usuario = "root"; // usuario con acceso a la base de datos, generalmente root
	$pass = "";// aquí coloca la clave de la base de datos del servidor o hosting
	$base = "sispos"; //nombre de la base de datos
	$cadena = "$manejador:host=$servidor;dbname=$base";

	$cnx = new PDO($cadena, $usuario, $pass, array(PDO::ATTR_PERSISTENT => "true", PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

	// $nombre = "%gas%"; 
	// $sql = "SELECT * FROM producto WHERE nombre LIKE :nombre ";
	// $parametros = array(':nombre'=>$nombre);
	// $pre = $cnx->prepare($sql);
	// $pre->execute($parametros);

	// $resultado = $pre;
	// $resultado = $resultado->fetchAll(PDO::FETCH_NAMED);

	// foreach($resultado as $k=>$v){
	// 	echo $v['nombre'].'<br>';
	// }
?>