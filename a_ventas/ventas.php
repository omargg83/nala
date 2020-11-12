<?php
	require_once("db_.php");
	$fecha=date("d-m-Y");

	$nuevafecha = strtotime ( '-3 month' , strtotime ( $fecha ) ) ;
	$fecha1 = date ( "d-m-Y" , $nuevafecha );
	echo "<div class='container' style='background-color:".$_SESSION['cfondo']."; '>";
?>
		<h5><b>Busqueda avanzada:</b></h5>
		<form id='consulta_avanzada' action='' data-destino='a_ventas/lista' data-div='resultado' data-funcion='avanzada' autocomplete='off'>
			<div class='row'>
				<div class='col-sm-2'>
					<label>Desde:</label>
					<input class="form-control" placeholder="Desde...." type="text" id='desde' name='desde' value='<?php echo $fecha1; ?>'>
				</div>

				<div class='col-sm-2'>
					<label>Hasta:</label>
					<input class="form-control" placeholder="Hasta...." type="text" id='hasta' name='hasta' value='<?php echo $fecha; ?>'>
				</div>

				<div class='col-sm-2'>
					<label># Interno</label>
					<input class="form-control" placeholder="# interno" type="text" id='interno' name='interno' value=''>
				</div>

				<div class='col-sm-2'>
					<label>Número Oficio</label>
					<input class="form-control" placeholder="Numero de oficio" type="text" id='numero' name='numero' value=''>
				</div>
				<div class='col-sm-4'>
					<label>Asunto</label>
					<input class="form-control" placeholder="Asunto" type="text" id='asunto' name='asunto' value=''>
				</div>
				<div class='col-sm-3'>
					<label>Remitente</label>
					<input class="form-control" placeholder="Remitente" type="text" id='remitente' name='remitente' value=''>
				</div>
				<div class='col-sm-3'>
					<label>Cargo</label>
					<input class="form-control" placeholder="Cargo" type="text" id='cargo' name='cargo' value=''>
				</div>
				<div class='col-sm-3'>
					<label>Dependencia</label>
					<input class="form-control" placeholder="Dependencia" type="text" id='dependencia' name='dependencia' value=''>
				</div>
				<div class='col-sm-2'>
					<label>Tipo de documento</label>
					<select id='contestado' name='contestado' class='form-control'>
						<option value=''></option>
						<option value='0'>Pendiente</option>
						<option value='1'>Contestado</option>
					</select>
				</div>

			</div><br>
			<div class='row'>
				<div class='col-sm-4'>
					<div class='btn-group'>
					<button class='btn btn-outline-primary btn-sm' type='submit' id='acceso'><i class='fas fa-search'></i>Buscar</button>
					</div>
				</div>
			</div>
			*Solo se muestran los primeros 100 registros de la busqueda
		</form>
	</div>
	<hr>

	<div id='resultado' style="background-color:white;" class="container-fluid">

	</div>



<SCRIPT type='text/javascript'>
	$(document).ready(function(){

		$.datepicker.setDefaults($.datepicker.regional['es']);
		$( "#desde" ).datepicker();
		$( "#hasta" ).datepicker();

	});
</script>
