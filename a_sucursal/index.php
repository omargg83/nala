<?php
	require_once("db_.php");
?>

	<nav class='navbar navbar-expand-sm navbar-light bg-light '>
	<a class='navbar-brand' ><i class='fas fa-store-alt'></i> Sucursal</a>
	  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='principal' aria-expanded='false' aria-label='Toggle navigation'>
		<span class='navbar-toggler-icon'></span>
	  </button>
		  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
			<ul class='navbar-nav mr-auto'>

				<li class='nav-item active'><a class='nav-link barranav' is='a-link' title='Mostrar todo' id='lista_comision' des='a_sucursal/lista' dix='trabajo'><i class='fas fa-list-ul'></i><span>Lista</span></a></li>
				<?php

				/*<li class='nav-item active'><a class='nav-link barranav izq' is='a-link' title='Nuevo' id='new_personal' des='a_sucursal/editar' v_idsucursal='0' dix='trabajo'><i class='fas fa-plus'></i><span>Nuevo</span></a></li>*/

				?>
			</ul>
	  </div>
	</nav>
<div id='trabajo'>
	<?php
		include 'lista.php';
	?>
</div>
