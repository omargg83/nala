<?php
	require_once("db_.php");
?>

	<nav class='navbar navbar-expand-lg navbar-light bg-light '>
	<a class='navbar-brand' ><i class="fab fa-cuttlefish"></i>ategorías</a>
	  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='principal' aria-expanded='false' aria-label='Toggle navigation'>
		<span class='navbar-toggler-icon'></span>
	  </button>
		  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
			<ul class='navbar-nav mr-auto'>
				<form class='form-inline my-2 my-lg-0' is="b-submit" id="form_busca" des="a_categorias/lista" dix='trabajo' >
					<div class="input-group  mr-sm-2">
						<input type="text" class="form-control form-control-sm" placeholder="Buscar" aria-label="Buscar" aria-describedby="basic-addon2"  name='buscar' id='buscar'>
						<div class="input-group-append">
							<button class="btn btn-warning btn-sm" type="submit" ><i class='fas fa-search'></i></button>
						</div>
					</div>
				</form>
			<li class='nav-item active'><a class='nav-link barranav' is='a-link' title='Mostrar todo' id='lista_comision' des='a_categorias/lista' dix='trabajo'><i class='fas fa-list-ul'></i><span>Lista</span></a></li>

			<?php
				if($_SESSION['a_sistema']==1){
					echo "<li class='nav-item active'><a class='nav-link barranav izq' is='a-link' title='Nuevo' id='new_personal' des='a_categorias/editar' v_id='0' dix='trabajo'><i class='fas fa-plus'></i><span>Nueva Categoría</span></a></li>";
				}
			?>
			</ul>
	  </div>
	</nav>

<div id='trabajo'>
	<?php
		include 'lista.php';
	?>
</div>
