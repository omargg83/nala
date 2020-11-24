<?php
	require_once("db_.php");
	$idtraspaso=$_REQUEST['idtraspaso'];
	$sucursal=$db->sucursal_lista();


	$traspaso = $db->traspaso($idtraspaso);
	$numero=$traspaso->numero;
	$nombre=$traspaso->nombre;
	$idsucursal=$traspaso->idsucursal;
	$estado=$traspaso->estado;
	$fecha=$traspaso->fecha;


?>
