<?php
require_once("../db_.php");
?>
<br>
<div class='container-fluid'>
	<div class='row'>
		<?php

		if(array_key_exists('VENTA', $db->derecho)){
			echo "<div class='col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-1'>
				<div class='dash_icon'>
					<div class='row'>
						<div class='col-3'>
							<img class='card-img-top mx-auto d-block' src='img\\venta.png' alt='Card image' style='width:60px'>
						</div>
						<div class='col-9'>
							<h5>VENTA</h5>
							<p class='card-text text-center'>Nueva Venta.</p>
							<a class='btn btn-warning btn-sm btn-block' id='menu_ventas' is='menu-link' href='#a_venta/venta'>Ir</a>
						</div>
					</div>
			  </div>
		  </div>";
		}


		if(array_key_exists('VENTAREGISTRO', $db->derecho)){
			echo "<div class='col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-1'>
				<div class='dash_icon'>
					<div class='row'>
						<div class='col-3'>
							<img class='card-img-top mx-auto d-block' src='img\\venta.png' alt='Card image' style='width:60px'>
						</div>
						<div class='col-9'>
							<h5>VENTAS</h5>
							<p class='card-text text-center'>Registro de Ventas.</p>
							<a class='btn btn-warning btn-sm btn-block' id='menu_ventas' is='menu-link' href='#a_ventas/index'>Ir</a>
						</div>
					</div>
			  </div>
		  </div>";

		}

		if(array_key_exists('CITAS', $db->derecho)){
			echo "<div class='col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-1'>
				<div class='dash_icon'>
					<div class='row'>
						<div class='col-3'>
							<img class='card-img-top mx-auto d-block' src='img\\cita.png' alt='Card image' style='width:60px'>
						</div>
						<div class='col-9'>
							<h5>CITAS</h5>
							<p class='card-text text-center'>Registro de citas.</p>
							<a class='btn btn-warning btn-sm btn-block' id='menu_ventas' is='menu-link' href='#a_citas/index'>Ir</a>
						</div>
					</div>
			  </div>
		  </div>";

		}

		if(array_key_exists('PRODUCTOS', $db->derecho)){
			echo "<div class='col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-1'>
				<div class='dash_icon'>
					<div class='row'>
						<div class='col-3'>
							<img class='card-img-top mx-auto d-block' src='img\\pies.png' alt='Card image' style='width:150px'>
						</div>
						<div class='col-9'>
							<h5>PRODUCTOS</h5>
							<p class='card-text text-center'>Registro de productos.</p>
							<a class='btn btn-warning btn-sm btn-block' id='menu_ventas' is='menu-link' href='#a_productos/index'>Ir</a>
						</div>
					</div>
			  </div>
		  </div>";
		}
		?>
	</div>
</div>
