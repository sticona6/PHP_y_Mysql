<?php
$usuario = $_POST['Email'];
$passw = $_POST['Contrasena'];

require_once 'funciones_bd.php';
$db = new funciones_BD();

	if($db->login($usuario,$passw)){
		$result = ($db->login($usuario,$passw));
		echo json_encode($result);
	}else{
		$result[] = array("id"=>"null", 
			"codigo"=>"null",
			"dni"=>"null",
			"nombre"=>"null",
			"apellidos"=>"null",
			"tipo"=>"null",
			"foto"=>"null",
			"email"=>"null",
			"direccion"=>"null",
			"celular"=>"null",
			"telefono"=>"null",
			"estado"=>"null");
		echo json_encode($result);
	}

?>