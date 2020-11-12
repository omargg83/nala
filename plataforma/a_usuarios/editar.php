<?php
	require_once("db_.php");

	if (isset($_REQUEST['idusuario'])){$idusuario=$_REQUEST['idusuario'];} else{ $idusuario=0;}
	if (isset($_REQUEST['idtienda'])){$idtienda=$_REQUEST['idtienda'];} else{ $idtienda=0;}


	$sucursal=$db->sucursal_lista($idtienda);

	if($idusuario>0){
		$pd = $db->usuario($idusuario);
		$id=$pd->idusuario;
		$idsucursal=$pd->idsucursal;
		$user=$pd->user;
		$pass=$pd->pass;
		$nivel=$pd->nivel;
		$nombre=$pd->nombre;
		$estado=$pd->activo;
	}
	else{
		$id=0;
		$user="";
		$pass="";
		$nivel="1";
		$nombre="";
		$estado="1";
		$idsucursal=0;
	}
?>
<div class="container">
	<div class='card'>
		<div class='card-header'>
			Usuarios
		</div>

		<form is="f-submit" id="form_personal" db="a_usuarios/db_" fun="guardar_usuario" des='a_usuarios/editar' desid='id'>
			<div class='card-body'>

				 <input type="hidden" name="id" id="id" value="<?php echo $idusuario ;?>" placeholder="Tienda" readonly>
				 <input type="hidden" name="idtienda" id="idtienda" value="<?php echo $idtienda ;?>" placeholder="Tienda" readonly>
				 <div class="form-group row">
				   <label class="control-label col-sm-2" for="">Nombre:</label>
				   <div class="col-sm-10">
					 <input type="text" class="form-control form-control-sm" name="nombre" id="nombre" value="<?php echo $nombre ;?>" placeholder="Nombre del usuario" required>
				   </div>
				 </div>


				<div class="form-group row">
				 <label class="control-label col-sm-2" for="">Estado:</label>
				  <div class="col-sm-10">
					<select class="form-control form-control-sm" name="estado" id="estado">
					  <option value="1"<?php if($estado=="1") echo "selected"; ?> >Activa</option>
					  <option value="0"<?php if($estado=="0") echo "selected"; ?> >Inactivo</option>
					</select>
				  </div>
				</div>

				 <div class="form-group row">
				   <label class="control-label col-sm-2" for="">Usuario:</label>
				   <div class="col-sm-10">
					 <input type="text" class="form-control form-control-sm" name="user" id="user" value="<?php echo $user ;?>" placeholder="Usuario" required>
				   </div>
				 </div>

				 <div class="form-group row">
				 <label class="control-label col-sm-2" for="">Nivel:</label>
				  <div class="col-sm-10">
					<select class="form-control form-control-sm" name="nivel" id="nivel">
					  <option value="1"<?php if($nivel=="1") echo "selected"; ?> >1 Administrador</option>
					  <option value="2"<?php if($nivel=="2") echo "selected"; ?> >2 Normal</option>
					</select>
				  </div>
				</div>

				<div class="form-group row">
				  <label class="control-label col-sm-2" for="">Sucursal:</label>
				  <div class="col-sm-10">
					<?php

						echo "<select class='form-control form-control-sm' name='idsucursal' id='idsucursal'>";
						echo '<option disabled>Seleccione sucursal</option>';
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
			<div class="card-footer">
				<div class="row">
					<div class="col-sm-12">
						<button class="btn btn-warning btn-sm" type="submit"><i class='far fa-save'></i>Guardar</button>
						<?php
							if($id>0){
								echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' des='a_usuarios/form_pass' omodal='1' v_idusuario='$id'><i class='fas fa-key'></i>Contraseña</button>";
							}
							echo "<button type='button' class='btn btn-warning btn-sm' id='lista_penarea' is='b-link' des='a_usuarios/lista' v_idtienda='$idtienda' dix='trabajo'><i class='fas fa-undo-alt'></i>Regresar</button>";
						?>
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
						echo "<button class='btn btn-warning btn-sm' type='submit'><i class='fas fa-plus'></i>Agregar</button>";
						echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' des='a_usuarios/form_permisos' desid='id' db='a_usuarios/db_' fun='agregar_todos' dix='permisos' v_idusuario='$id'><i class='fas fa-globe-americas'></i>Todos</button>";

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
