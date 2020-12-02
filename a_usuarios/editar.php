<?php
	require_once("db_.php");
	if (isset($_REQUEST['id'])){$id=clean_var($_REQUEST['id']);} else{ $id=0;}
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
		$correo=$pd->correo;
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
		$correo="";
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
					<div class="col-xl col-auto">
						<?php
							if(strlen($archivo)>0 and file_exists("../".$db->f_usuarios."/".$archivo)){
								echo "<img src='".$db->f_usuarios."/".$archivo."' width='100%' class='img-thumbnail'/>";
							}
							else{
								echo "<img src='img/user.jpg' width='100%' class='img-thumbnail'/>";
							}
					  ?>
					</div>
					<div class="col-xl col-auto">
						<div class="row">
							<div class="col-xl col-auto">
								<label>Correo:</label>
								<input type="email" class="form-control form-control-sm" name="correo" id="correo" value="<?php echo $correo ;?>" placeholder="Correo" required>
							</div>
						</div>
							<div class="row">
							<div class="col-xl col-auto">
		 				   <label>Nombre:</label>
		 					 <input type="text" class="form-control form-control-sm" name="nombre" id="nombre" value="<?php echo $nombre ;?>" placeholder="Nombre del usuario" required
							 <?php
							 	if($id>0){
									echo " readonly";
								}
							 ?>
							 >
		 				 	</div>
						</div>
						<div class="row">
								<div class="col-xl col-auto">
								 <label >Estado:</label>
									<select class="form-control form-control-sm" name="estado" id="estado">
										<option value="1"<?php if($estado=="1") echo "selected"; ?> >Activo</option>
										<option value="0"<?php if($estado=="0") echo "selected"; ?> >Inactivo</option>
									</select>
								</div>
								<div class="col-xl col-auto">
									<label>Chat:</label>
									<input type="text" class="form-control form-control-sm" name="user" id="user" value="<?php echo $user ;?>" placeholder="Usuario" required>
								</div>
							</div>
				  	<div class="row">
							<?php
								if($db->nivel_personal==0){
				 					echo "<div class='col-xl col-auto'>";
				 				  	echo "<label>Sucursal:</label>";
				 						echo "<select class='form-control form-control-sm' name='idsucursal' id='idsucursal'>";
				 						foreach($sucursal as $v1){
				 							  echo '<option value="'.$v1->idsucursal.'"';
				 							  if($v1->idsucursal==$idsucursal){
				 								  echo " selected";
				 							  }
				 							  echo '>'.$v1->nombre.'</option>';
				 						}
				 					  echo "</select>";
									echo "</div>";
								}
		 					?>
						</div>
					</div>
				</div>

			</div>
			<div class="card-footer">
				<div class="row">
					<div class="col-xl col-auto">
						<div class="btn-group">
						<button class="btn btn-warning btn-sm" type="submit"><i class='far fa-save'></i>Guardar</button>
						<?php
							if($id>0){

								echo "<button type='button' class='btn btn-warning btn-sm' is='b-link' db='a_usuarios/db_' des='a_usuarios/lista' fun='borrar_usuario' dix='trabajo' v_id='$id' id='eliminar' tp='¿Desea eliminar el usuario seleccionado?'><i class='far fa-trash-alt'></i>Eliminar</button>";

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
		if($db->nivel_personal==0){
			echo "<div class='card-body' >";
				echo "<form is='f-submit' id='form_permiso' db='a_usuarios/db_' fun='guardar_permiso' des='a_usuarios/form_permisos' dix='permisos' desid='id'>";
					echo "<input type='hidden' class='form-control form-control-sm' name='idusuariox' id='idusuariox' value='$id' readonly>";
					echo "<div class='row'>";
						echo "<div class='col-xl col-auto'>";
							echo "<label for='prof'>Modulo:</label>";
							echo "<select id='modulo' name='modulo' class='form-control'>";
							echo $db->modulos();
							echo "</select>";
						echo "</div>";

						echo "<div class='col-xl col-auto'>";
							echo "<label>Captura</label>";
							echo "<select id='captura' name='captura' class='form-control'>";
							echo "<option value='0' >Sin captura</option>";
							echo "<option value='1' >Captura</option>";
							echo "</select>";
						echo "</div>";

						echo "<div class='col-xl col-auto'>";
							echo "<label for='prof'>Nivel</label>";
							echo "<select id='nivelx' name='nivelx' class='form-control'>";
							echo $db->nivel();
							echo "</select>";
						echo "</div>";
					echo "</div>";
				echo "</form>";
				echo "<hr>";
				echo "<div class='row'>";
					echo "<div class='col-xl col-auto'>";
						echo "<div class='btn-group'>";
							echo "<button class='btn btn-warning btn-sm' type='submit' ><i class='fa fa-check'></i>Agregar</button>";

							echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' des='a_usuarios/editar' desid='id' db='a_usuarios/db_' fun='agregar_todos' dix='trabajo' v_idusuario='$id'>Todos</button>";

						echo "</div>";
					echo "</div>";
				echo "</div>";


				echo "<div id='permisos'>";
					include 'form_permisos.php';
				echo "</div>";
			echo "</div>";
		}
	}
?>
	</div>
</div>
