<?php
	require_once("db_.php");
  $id=$_REQUEST['idcita'];
  $fecha=date("Y-m-d");
	$hora=12;
	$minuto=0;

	$hora_fin=12;
	$minuto_fin=59;

	$cubiculo=0;
	$atiende=0;
	$servicio=0;
	$precio=0;

	$asunto="";
  $estatus="";
  $idcliente=0;
	$idproducto=0;
  $idenvio="";
  $idfactura="";
  $observaciones="";
  $pago="";
  $idpago="";
  $pagador="";
  $estado_pago="";

	$nombre_cli="";
	$correo_cli="";
	$telefono_cli="";
	$ati=$db->atiende();
	$serv=$db->servicios_lista();
	if($id>0){
    $row=$db->editar_cita($id);
		$fech = new DateTime($row->fecha);
		$fecha=$fech->format('d-m-Y');
		$hora=$fech->format('H');
		$minuto=$fech->format('i');

		$fech_fin = new DateTime($row->fecha_fin);

		$hora_fin=$fech_fin->format('H');
		$minuto_fin=$fech_fin->format('i');

		$asunto=$row->asunto;
		$estatus=$row->estatus;
		$observaciones=$row->observaciones;
		//$idproducto=$row->idcliente;
		$idcliente=$row->idcliente;
		$cubiculo=$row->cubiculo;
		$atiende=$row->atiende;
		$servicio=$row->servicio;
		$precio=$row->precio;

		$cliente=$db->cliente($idcliente);
		if ($idcliente>0){
		$nombre_cli=$cliente->nombre;
		$correo_cli=$cliente->correo;
		$telefono_cli=$cliente->telefono;
		}

  }

	echo "<div class='container'>";
      echo "<div class='card'>";
			echo "<form is='f-submit' id='form_comision' db='a_citas/db_' fun='guardar_cita' lug='a_citas/editar'>";

			echo "<input type='hidden' class='form-control' id='idcliente' name='idcliente' value='$idcliente' placeholder='idcliente'>";
			echo "<input type='hidden' class='form-control' id='id' name='id' value='$id' placeholder='id'>";
        echo "<div class='card-header'>";
          echo "Cita # $id";
        echo "</div>";

        echo "<div class='card-body'>";

          echo "<div class='row'>";
            echo "<div class='col-2'>";
              echo "<label>Fecha</label>";
              echo "<input type='text' class='form-control form-control-sm fechaclass' id='fecha' name='fecha' value='$fecha'>";

            echo "</div>";

						echo "<div class='col-2'>";
			        echo "<label>Hora Inicio</label>";
			        echo "<select class='form-control form-control-sm' name='hora' id='hora'>";
								for($i=0;$i<24;$i++){
									echo  "<option value='$i' "; if($hora==$i){ echo " selected";} echo ">$i</option>";
								}
			        echo  "</select>";
			      echo "</div>";

						echo "<div class='col-2'>";
			        echo "<label>Minuto</label>";
			        echo "<select class='form-control form-control-sm' name='minuto' id='minuto'>";
								for($i=0;$i<=59;$i++){
									echo  "<option value='$i' "; if($minuto==$i){ echo " selected";} echo ">$i</option>";
								}
			        echo  "</select>";
			      echo "</div>";

						echo "<div class='col-2'>";
			        echo "<label>Hora final</label>";
			        echo "<select class='form-control form-control-sm' name='hora_fin' id='hora_fin'>";
								for($i=0;$i<24;$i++){
									echo  "<option value='$i' "; if($hora_fin==$i){ echo " selected";} echo ">$i</option>";
								}
			        echo  "</select>";
			      echo "</div>";

						echo "<div class='col-2'>";
			        echo "<label>Minuto</label>";
			        echo "<select class='form-control form-control-sm' name='minuto_fin' id='minuto_fin'>";
								for($i=0;$i<=59;$i++){
									echo  "<option value='$i' "; if($minuto_fin==$i){ echo " selected";} echo ">$i</option>";
								}
			        echo  "</select>";
			      echo "</div>";

						echo "<div class='col-3'>";
							echo "<label>Estado</label>";
							echo "<select id='estatus' name='estatus' class='form-control form-control-sm'>";
								echo "<option value='PENDIENTE'"; if($estatus=='PENDIENTE'){ echo " selected"; } echo ">PENDIENTE</option>";
								echo "<option value='PROGRAMADA'"; if($estatus=='PROGRAMADA'){ echo " selected"; } echo ">PROGRAMADA</option>";
								echo "<option value='REALIZADA'"; if($estatus=='REALIZADA'){ echo " selected"; } echo ">REALIZADA</option>";
								echo "<option value='CANCELADA'"; if($estatus=='CANCELADA'){ echo " selected"; } echo ">CANCELADA</option>";
							echo "</select>";
						echo "</div>";

					echo "<hr>";
					echo "</div>";

					echo "<hr>";
					echo "<div class='row'>";
						echo "<div class='col-6'>";
							echo "<label>Asunto:</label>";
								echo "<input type='text' class='form-control form-control-sm' id='asunto' name='asunto' value='$asunto' placeholder='Asunto' maxlength='65' required >";
						echo "</div>";

						echo "<div class='col-6'>";
							echo "<label>Nombre:</label>";
								echo "<input type='text' class='form-control form-control-sm' id='nombre' name='nombre' value='$nombre_cli' placeholder='Nombre del cliente' readonly>";
						echo "</div>";


					echo "</div>";

					echo "<div class='row'>";

						echo "<div class='col-4'>";
							echo "<label>Correo:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='correo' name='correo' value='$correo_cli' readonly>";
						echo "</div>";

						echo "<div class='col-4'>";
							echo "<label>Teléfono:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='telefono' name='telefono' value='$telefono_cli' readonly>";
						echo "</div>";

					echo "</div>";
					echo "<hr>";
				  echo "<div class='row'>";
            echo "<div class='col-12'>";
              echo "<label>Notas de la cita</label>";
              echo "<input type='text' class='form-control form-control-sm' id='observaciones' name='observaciones' value='$observaciones' placeholder='Notas del pedido'>";
            echo "</div>";
          echo "</div>";

					echo "<div class='row'>";
						echo "<div class='col-2'>";
							echo "<label>Cubiculo</label>";
							echo "<select class='form-control form-control-sm' name='cubiculo' id='cubiculo'>";
								echo  "<option value='1' "; if($cubiculo=="1"){ echo " selected";} echo ">1</option>";
								echo  "<option value='2' "; if($cubiculo=="2"){ echo " selected";} echo ">2</option>";
								echo  "<option value='3' "; if($cubiculo=="3"){ echo " selected";} echo ">3</option>";
								echo  "<option value='4' "; if($cubiculo=="4"){ echo " selected";} echo ">4</option>";
							echo  "</select>";
						echo "</div>";

						echo "<div class='col-3'>";
							echo "<label>Atiende</label>";
							echo "<select class='form-control form-control-sm' name='atiende' id='atiende'>";
								foreach($ati as $key){
									echo  "<option value='".$key->idusuario."' "; if($atiende==$key->idusuario){ echo " selected";} echo ">".$key->nombre."</option>";
								}
							echo  "</select>";
						echo "</div>";

						echo "<div class='col-4'>";
							echo "<label>Servicio</label>";
							echo "<select class='form-control form-control-sm' name='servicio' id='servicio'>";
							echo "<option value=''"; if($servicio==''){ echo " selected"; } echo "></option>";
								foreach($serv as $row){
									echo  "<option value='".$row->nombre."' "; if($servicio==$row->nombre){ echo " selected";} echo ">".$row->nombre."</option>";
								}
							echo  "</select>";
						echo "</div>";

						echo "<div class='col-3'>";
              echo "<label>Costo:</label>";
              echo "<input type='text' class='form-control form-control-sm' id='precio' name='precio' value='$precio' placeholder='Costo de la cita'>";
            echo "</div>";

					echo "</div>";

        echo "</div>";
        echo "<div class='card-footer'>";

						if($db->nivel_captura==1){
							echo "<button type='submit' class='btn btn-warning btn-sm'><i class='far fa-save'></i>Guardar</button>";
							}		

							if($estatus=='PENDIENTE' or $id==0){

								echo "<button type='button' class='btn btn-warning btn-sm' id='cliente_add' v_idcliente='$idcliente' is='b-link' v_idcita='$id' des='a_citas/form_cliente' omodal='1' title='Agregar Cliente'><i class='fas fa-user-tag'></i>Cliente</button>";

							}
							/*
							if($id>0){
								echo "<button type='button' class='btn btn-warning btn-sm' id='producto_add' v_idproducto='$idproducto'is='b-link' v_idcita='$id' des='a_citas/form_producto'  dix='trabajo' omodal='1'>+ <i class='fab fa-product-hunt'></i>Producto</button>";
							}*/
            echo "<button type='button' class='btn btn-warning btn-sm' id='calendario' data-dismiss='modal' title='Cerrar'><i class='fas fa-sign-out-alt'></i>Cerrar</button>";

        echo "</div>";
				echo "</form>";
      echo "</div>";

		echo "<div class='card-body' id='compras'>";
			include 'lista_pedido.php';
		echo "</div>";
	echo "</div>";
 ?>

 <script>
   $(function() {
     fechas();
   });
 </script>
