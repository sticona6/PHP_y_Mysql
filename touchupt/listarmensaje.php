
<?php

$codcurso = $_POST['IdServicio'];

require_once 'funciones_bd.php';
$db = new funciones_BD();
	if($db->ListarMensaje($codcurso))
	{	
		$result = $db->ListarMensaje($codcurso);
		echo json_encode($result);
	}else{
		$result = null;
		echo json_encode($result);
	}

?>
