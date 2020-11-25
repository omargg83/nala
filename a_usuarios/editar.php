<?php
	require_once("db_.php");
	if (isset($_REQUEST['id'])){$id=$_REQUEST['id'];} else{ $id=0;}
	$sucursal=$db->sucursal_lista();
	$caja=$db->caja_lista();
	if($id>0){
		$pd = $db->usuario($id);
		$id=$pd->idusuario;
		$idsucursal=$pd->idsucursal;
		$user=$pd->user;
		$pass=$pd->pass;
		$nivel=$pd->nivel;
		$nombre=$pd->nombre;
		$estado=$pd->activo;
		$idcaja=$pd->idcaja;
		$archivo=$pd->archivo;
	}
	else{
		$id=0;
		$user="";
		$pass="";
		$nivel="1";
		$nombre="";
		$estado="1";
		$idsucursal=$_SESSION['idsucursal'];
		$archivo="";
	}
?>
<div class="container">
	<div class='card'>
		<div class='card-header'>
			Usuarios
		</div>
		<form is="f-submit" id="form_personal" db="a_usuarios/db_" fun="guardar_usuario" des='a_usuarios/editar' desid='id'>
			<input type="hidden" class="form-control form-control-sm" name="id" id="id" value="<?php echo $id ;?>" placeholder="Tienda" readonly>

			<div class='card-body'>
				<div class="row">
					<div class="col-2">
						<?php
							echo "<img src='".$db->f_usuarios."/".$archivo."' width='100%' class='img-thumbnail'/>";
					 ?>
					</div>
					<div class="col-10">
						<div class="row">
							<div class="col-12">
		 				   <label>Nombre:</label>
		 					 <input type="text" class="form-control form-control-sm" name="nombre" id="nombre" value="<?php echo $nombre ;?>" placeholder="Nombre del usuario" required>
		 				 	</div>

							<div class="col-12">
							 <label >Estado:</label>
								<select class="form-control form-control-sm" name="estado" id="estado">
									<option value="1"<?php if($estado=="1") echo "selected"; ?> >Activa</option>
									<option value="0"<?php if($estado=="0") echo "selected"; ?> >Inactivo</option>
								</select>
							</div>

							<div class="col-12">
								<label >Usuario:</label>
								<input type="text" class="form-control form-control-sm" name="user" id="user" value="<?php echo $user ;?>" placeholder="Usuario" required>
							</div>

						<div class="col-12">
		 				 <label>Nivel:</label>
		 					<select class="form-control form-control-sm" name="nivel" id="nivel">
		 					  <option value="1"<?php if($nivel=="1") echo "selected"; ?> >1 Administrador</option>
		 					  <option value="2"<?php if($nivel=="2") echo "selected"; ?> >2 Normal</option>
		 					</select>
		 				</div>

		 				<div class="col-12">
		 				  <label>Sucursal:</label>
		 					<?php

		 						echo "<select class='form-control form-control-sm' name='idsucursal' id='idsucursal'>";
		 						foreach($sucursal as $v1){
		 							  echo '<option value="'.$v1->idsucursal.'"';
		 							  if($v1->idsucursal==$idsucursal){
		 								  echo " selected";
		 							  }
		 							  echo '>'.$v1->nombre.'</option>';
		 						}
		 					  echo "</select>";
		 					?>
		 				</div>




						</div>

					</div>
				</div>

			</div>
			<div class="card-footer">
				<div class="row">
					<div class="col-sm-12">
						<div class="btn-group">
						<button class="btn btn-warning btn-sm" type="submit"><i class='far fa-save'></i>Guardar</button>
						<?php
							if($id>0){
								echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' des='a_usuarios/form_foto' v_id='$id' omodal='1'><i class='fas fa-camera'></i>Foto</button>";

								echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' des='a_usuarios/form_pass' omodal='1' v_id='$id'><i class='fas fa-key'></i>Contraseña</button>";
							}
						?>
						<button type="button" class='btn btn-warning btn-sm' id='lista_penarea' is="b-link" des='a_usuarios/lista' dix='trabajo'><i class='fas fa-undo-alt'></i>Regresar</button>
						</div>
					</div>
				</div>
			</div>
		</form>


<?php
	if($id>0){
		echo "<div class='card-body' >";
			echo "<form is='f-submit' id='form_permiso' db='a_usuarios/db_' fun='guardar_permiso' des='a_usuarios/form_permisos' dix='permisos' desid='id'>";
				echo "<input type='hidden' class='form-control form-control-sm' name='idusuariox' id='idusuariox' value='$id' readonly>";
				echo "<div class='row'>";
					echo "<div class='col-sm-4'>";
						echo "<label for='prof'>Modulo:</label>";
						echo "<select id='modulo' name='modulo' class='form-control'>";
						echo $db->modulos();
						echo "</select>";
					echo "</div>";

					echo "<div class='col-sm-4'>";
						echo "<label>Captura</label>";
						echo "<select id='captura' name='captura' class='form-control'>";
						echo "<option value='0' >Sin captura</option>";
						echo "<option value='1' >Captura</option>";
						echo "</select>";
					echo "</div>";

					echo "<div class='col-sm-4'>";
						echo "<label for='prof'>Nivel</label>";
						echo "<select id='nivelx' name='nivelx' class='form-control'>";
						echo $db->nivel();
						echo "</select>";
					echo "</div>";

					echo "<div class='col-sm-12'>";
						echo "<button class='btn btn-warning btn-sm' type='submit' ><i class='fa fa-check'></i>Agregar</button>";

						echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' des='a_usuarios/editar' desid='id' db='a_usuarios/db_' fun='agregar_todos' dix='trabajo' v_idusuario='$id'>Todos</button>";

					echo "</div>";
				echo "</div>";
			echo "</form>";

			echo "<div id='permisos'>";
				include 'form_permisos.php';
			echo "</div>";
		echo "</div>";
	}
?>
	</div>
</div>
