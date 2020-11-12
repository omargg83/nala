<?php
require_once("../db_.php");
?>
<br>
<div class='container-fluid'>
	<div class='row'>
		<?php

		if(array_key_exists('VENTA', $db->derecho)){
			echo "<div class='col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 p-1'>
		  	<div class='card bg-white text-black'>
					<img class='card-img-top mx-auto d-block' src='img\\venta.png' alt='Card image' style='width:60px'>
			    <h5 class='card-title text-center'>VENTA</h5>
			    <p class='card-text text-center'>Nueva Venta.</p>
					<div class='card-footer'>
						<a class='btn btn-warning btn-sm btn-block' id='menu_ventas' is='menu-link' href='#a_venta/venta'>Ir</a>
					</div>
			  </div>
		  </div>";
		}

		if(array_key_exists('VENTAREGISTRO', $db->derecho)){
			echo "<div class='col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 p-1'>
		  	<div class='card bg-white text-black'>
					<img class='card-img-top mx-auto d-block' src='img\\venta.png' alt='Card image' style='width:60px'>
			    <h5 class='card-title text-center'>VENTAS</h5>
			    <p class='card-text text-center'>Registro de Ventas.</p>
					<div class='card-footer'>
						<a class='btn btn-warning btn-sm btn-block' id='menu_ventas' is='menu-link' href='#a_ventas/index'>Ir</a>
					</div>
			  </div>
		  </div>";
		}

		if(array_key_exists('CITAS', $db->derecho)){
			echo "<div class='col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 p-1'>
				<div class='card bg-white text-black' >
					 <img class='card-img-top mx-auto d-block' src='img\\cita.png' alt='Card image' style='width:60px'>
					<h5 class='card-title text-center'>CITAS</h5>
					<p class='card-text text-center'>Registro de citas por cliente.</p>
					<div class='card-footer'>
						<a class='btn btn-warning btn-sm btn-block' id='menu_ventas' is='menu-link' href='#a_citas/index'>Ir</a>
					</div>
				</div>
			</div>";
		}

		if(array_key_exists('PRODUCTOS', $db->derecho)){
			echo "<div class='col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 p-1'>
				<div class='card bg-white text-black' >
					 <img class='card-img-top mx-auto d-block' src='img\\pies.png' alt='Card image' style='width:124px'>
					<h5 class='card-title text-center'>PRODUCTOS</h5>
					<p class='card-text text-center'>Registro de productos.</p>
					<div class='card-footer'>
						<a class='btn btn-warning btn-sm btn-block' id='menu_ventas'  is='menu-link' href='#a_productos/index'>Ir</a>
					</div>
				</div>
			</div>";
		}
		?>
	</div>
</div>
