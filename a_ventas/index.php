<?php
	require_once("db_.php");
?>
<nav class='navbar navbar-expand-sm navbar-light bg-light '>
	<a class='navbar-brand' ><i class='fas fa-user-check'></i> Ventas</a>
	  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='principal' aria-expanded='false' aria-label='Toggle navigation'>
		<span class='navbar-toggler-icon'></span>
	  </button>
		  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
			<ul class='navbar-nav mr-auto'>

				<form  class='form-inline my-2 my-lg-0' is="b-submit" id="form_lista" des="a_ventas/lista" dix='trabajo'>
					<div class="input-group  mr-sm-2">
						<input type="text" class="form-control form-control-sm" placeholder="Buscar" aria-label="Buscar" aria-describedby="basic-addon2"  name='buscar' id='buscar'>
						<div class="input-group-append">
							<button class="btn btn-warning btn-sm" type="submit" ><i class='fas fa-search'></i></button>
						</div>
					</div>
				</form>

				<?php
					if($_SESSION['a_sistema']==1){
						echo "<li class='nav-item active'><a class='nav-link barranav izq' title='Nuevo' is='a-link' id='nueva_venta' des='a_venta/venta' dix='trabajo'  ><i class='fas fa-plus'></i><span>Nueva</span></a></li>";
					}
				?>

				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='lista_comision' is='a-link' des='a_ventas/lista' dix='trabajo' ><i class="far fa-folder-open"></i><span>Abiertas</span></a></li>

			</ul>
	  </div>
	</nav>

<div id='trabajo'>
	<?php
	include 'lista.php';
	?>
</div>

<script type="text/javascript">

	function sel_cita(idcita,idventa){
		$.ajax({
			data:  {
				"idcita":idcita,
				"idventa":idventa,
				"function":"selecciona_cita"
			},
			url:   "a_ventas/db_.php",
			type:  'post',
			success:  function (response) {
				$("#resultadosx").html(response);
			}
		});
	}

	function imprime(id){
		$.confirm({
			title: 'Producto',
			content: '¿Desea imprimir la venta seleccionada?',
			buttons: {
				Aceptar: function () {
					$.ajax({
						data:  {
							"id":id,
							"function":"imprimir"
						},
						url:   "a_ventas/db_.php",
						type:  'post',
						beforeSend: function () {

						},
						success:  function (response) {
							var datos = JSON.parse(response);
							if (datos.error==0){
								Swal.fire({
									type: 'success',
									title: "Se mandó imprimir correctamente",
									showConfirmButton: false,
									timer: 1000
								});
							}
							else{
								alert(response);
							}
						}
					});
				},
				Cancelar: function () {

				}
			}
		});
	}
	function imprime_pdf(id){
		$.confirm({
			title: 'Producto',
			content: '¿Desea imprimir la venta seleccionada?',
			buttons: {
				Aceptar: function () {
					VentanaCentrada("a_ventas/imprimir.php"+'?id='+id,'Impresion','','1024','768','true');
				},
				Cancelar: function () {

				}
			}
		});
	}


</script>
