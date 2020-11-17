<?php
	require_once("db_.php");
	$tipo=$_REQUEST['tipo'];

	$start=$_REQUEST['start'];
	$end=$_REQUEST['end'];

	$inicio=explode("T",$start);
	$fin=explode("T",$end);

	$citas=$db->citas_calendario($inicio[0],$fin[0]);
	$arreglo=array();

	$i=0;
	foreach($citas as $key){
		$hora=explode(" ",$key->fecha);
		$hora2=explode(" ",$key->fecha_fin);
		$color="";
		$limite=new DateTime($key->fecha);

		$limite->modify("+60 minute");

		$color="#000";
		if($key->estatus=='PENDIENTE'){ $color="#f3ef19"; }
		if($key->estatus=='PROGRAMADA'){ $color="#6392e5"; }
		if($key->estatus=='REALIZADA'){ $color="#20b224"; }
		if($key->estatus=='CANCELADA'){ $color="#e53636"; }

		$texto="Retiro";
		if ($key->idcliente>0){
			$arreglo[$i]=array(
				'id'=>$key->idcitas,
				'title'=>$key->nombre,
				'start'=>$hora[0]."T".$hora[1],
				'end'=>$hora2[0]."T".$hora2[1],
				'color'=>$color
			);
			}
			else {
				$arreglo[$i]=array(
					'id'=>$key->idcitas,
					'title'=>$key->asunto,
					'start'=>$hora[0]."T".$hora[1],
					'end'=>$hora2[0]."T".$hora2[1],
					'color'=>$color
				);
				}

		$i++;
	}
	echo json_encode($arreglo);
?>
