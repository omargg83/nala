<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
?>
<form is="f-submit" id="form_passc" db="a_usuarios/db_" fun="password" cmodal=2>

<div class='modal-header'>
	<h5 class='modal-title'>Cambiar contraseña</h5>
</div>
  <div class='modal-body' >
	<?php
		echo "<input  type='hidden' id='id' NAME='id' value='$id'>";
	?>
		<p class='input_title'>Contraseña:</p>
		<div class='form-group input-group'>
			<div class='input-group-prepend'>
				<span class='input-group-text'> <i class='fas fa-user-circle'></i>
			</div>
			<input class='form-control' placeholder='pass1' type='password'  id='pass1' name='pass1' required autocomplete='new-password'>
		</div>

		<p class='input_title'>Contraseña:</p>
		<div class='form-group input-group'>
			<div class='input-group-prepend'>
				<span class='input-group-text'> <i class='fa fa-lock'></i>
			</div>
			<input class='form-control' placeholder='pass2' type='password'  id='pass2' name='pass2' required autocomplete='new-password'>
		</div>

		<small>La clave debe tener al menos 6 caracteres
		<br>La clave no puede tener más de 16 caracteres
		<br>La clave debe tener al menos una letra minúscula
		<br>La clave debe tener al menos una letra mayúscula
		<br>La clave debe tener al menos un caracter numérico</small>
	</div>

	<div class='modal-footer' >
		<button class='btn btn-warning btn-sm' type='submit' id='acceso' title='Guardar'><i class='far fa-save'></i>Guardar</button>
		<button class="btn btn-warning btn-sm" type="button" is="b-link" cmodal='1' ><i class="fas fa-sign-out-alt"></i>Regresar</button>
  </div>
</form>
