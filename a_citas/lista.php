<?php
	require_once("db_.php");

	if(isset($_REQUEST['buscar'])){
		$texto=$_REQUEST['buscar'];
		$pd = $db->busca_cliente($texto);
	}
	else{
		$pd = $db->citas_lista();
	}


	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
	echo "<br><h5>Citas</h5>";
	echo "<hr>";
?>
		<div class="content table-responsive table-full-width" >
			<table id='x_lista' class='dataTable compact hover row-border' style='font-size:10pt;'>
			<thead>
			<th>#</th>
			<th>Citas</th>
			<th>Asunto</th>
			<th>Cliente</th>
			<th>Fecha Inicio</th>
			<th>Fecha Fin</th>
			<th>Estado</th>
			<th>Cubículo</th>
			<th>Servicio</th>
			</tr>
			</thead>
			<tbody>
			<?php
				if (count($pd)>0){
					foreach($pd as $key){
						echo "<tr id='".$key->idcitas."' class='edit-t'>";
						echo "<td>";
							echo "<div class='btn-group'>";

								if($key->estatus!="REALIZADA"){

									if($db->nivel_captura==1){
										echo "<button type='button' class='btn btn-warning btn-sm' id='edit_comision' is='b-link' title='Editar' des='a_citas/editar' dix='trabajo' v_idcita='$key->idcitas'><i class='fas fa-pencil-alt'></i></button>";
										echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_citas/db_' des='a_citas/lista' fun='borrar_cita' dix='trabajo' v_idcita='$key->idcitas' id='eliminar' tp='¿Desea eliminar la cita?'><i class='far fa-trash-alt'></i></button>";
										}					
									}

							echo "</div>";
						echo "</td>";
						echo "<td>".$key->idcitas."</td>";

						if($key->idcliente){
							$cli=$db->cliente($key->idcliente);
							$nombre=$cli->nombre;
						}
						else{
							$nombre="";
						}
						echo "<td>".$key->asunto."</td>";
						echo "<td>".$nombre."</td>";
						echo "<td>".fecha($key->fecha,2)."</td>";
						echo "<td>".fecha($key->fecha_fin,2)."</td>";
						if ($key->estatus=="PENDIENTE"){
						echo "<td>";
						echo "<button type='button' class='btn btn-warning' title='PENDIENTE' omodal='1' id='edit_comision' is='b-link' title='Editar' des='a_citas/editar2' dix='trabajo' v_idcita='$key->idcitas'><i class='fas fa-clock'></i></button>"." ".$key->estatus;
						echo "</td>";
						}
						else if ($key->estatus=="CANCELADA"){
							echo "<td>";
							echo "<button type='button' class='btn btn-danger' title='CANCELADA' omodal='1' id='edit_comision' is='b-link' title='Editar' des='a_citas/editar2' dix='trabajo' v_idcita='$key->idcitas'><i class='fas fa-window-close'></i></button>"." ".$key->estatus;
							echo "</td>";
						}
						else if ($key->estatus=="PROGRAMADA"){
							echo "<td>";
							echo "<button type='button' class='btn btn-primary' title='PROGRAMADA' omodal='1' id='edit_comision' is='b-link' title='Editar' des='a_citas/editar2' dix='trabajo' v_idcita='$key->idcitas'><i class='far fa-clock'></i></button>"." ".$key->estatus;
							echo "</td>";
						}
						else if ($key->estatus=="REALIZADA"){
							echo "<td>";
							echo"<button type='button' class='btn btn-success' title='REALIZADA' omodal='1'><i class='far fa-check-circle'></i></button>"." ".$key->estatus;
							echo"</td>";

						}
						echo "<td>".$key->cubiculo."</td>";
						echo "<td>".$key->servicio."</td>";

						echo "</tr>";
					}
				}
			?>
			</tbody>
			</table>
		</div>
	</div>
