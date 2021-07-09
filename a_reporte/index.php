<?php
  require_once("db_.php");
	echo "<div class='alert alert-warning'><b>REPORTES</b></div>";
	echo "<div class='container-fluid'>";
		echo "<div class='row'>";

			if(array_key_exists('REPORTE_VENTAS', $db->derecho)){
				echo "<div class='col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-1'>
					<div class='dash_icon'>
						<div class='row'>
							<div class='col-3 text-center'>
								<i class='fas fa-cash-register fa-3x'></i>
							</div>
							<div class='col-9'>
								<h5>Ventas</h5>
								<p class='card-text text-center'>Reporte de ventas</p>
								<a class='btn btn-warning btn-sm btn-block' id='menu_ventas' is='menu-link' href='#a_reporte/reporte1'>Ir</a>
							</div>
						</div>
				  </div>
			  </div>";
				}
			if(array_key_exists('REPORTE_VENTAS_PROD', $db->derecho)){
				echo "<div class='col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-1'>
					<div class='dash_icon'>
						<div class='row'>
							<div class='col-3 text-center'>
								<i class='fas fa-people-carry fa-3x'></i>
							</div>
							<div class='col-9'>
								<h5>Ventas y productos</h5>
								<p class='card-text text-center'>Ventas y productos por vendedor</p>
								<a class='btn btn-warning btn-sm btn-block' id='menu_ventas' is='menu-link' href='#a_reporte/reporte2'>Ir</a>
							</div>
						</div>
				  </div>
			  </div>";

        echo "<div class='col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-1'>
					<div class='dash_icon'>
						<div class='row'>
							<div class='col-3 text-center'>
								<i class='fas fa-people-carry fa-3x'></i>
							</div>
							<div class='col-9'>
								<h5>Reporte de ganancias</h5>
								<p class='card-text text-center'>Ganancias por venta de producto</p>
								<a class='btn btn-warning btn-sm btn-block' id='menu_ventas' is='menu-link' href='#a_reporte/reporte7'>Ir</a>
							</div>
						</div>
				  </div>
			  </div>";
			}
			if(array_key_exists('REPORTE_CORTE_DE_CAJA', $db->derecho)){
				echo "<div class='col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-1'>
					<div class='dash_icon'>
						<div class='row'>
							<div class='col-3 text-center'>
								<i class='fas fa-receipt fa-3x'></i>
							</div>
							<div class='col-9'>
								<h5>Caja</h5>
								<p class='card-text text-center'>Corte de caja</p>
								<a class='btn btn-warning btn-sm btn-block' id='menu_ventas' is='menu-link' href='#a_reporte/reporte3'>Ir</a>
							</div>
						</div>
				  </div>
			  </div>";
				}
			if(array_key_exists('REPORTE_CORTE_DE_CAJA_USUARIO', $db->derecho)){
				echo "<div class='col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 p-1'>
					<div class='dash_icon'>
						<div class='row'>
							<div class='col-3 text-center'>
								<i class='fas fa-hand-holding-usd fa-3x'></i>
							</div>
							<div class='col-9'>
								<h5>Caja</h5>
								<p class='card-text text-center'>Corte de caja por usuario</p>
								<a class='btn btn-warning btn-sm btn-block' id='menu_ventas' is='menu-link' href='#a_reporte/reporte4'>Ir</a>
							</div>
						</div>
				  </div>
			  </div>";
				}
		echo "</div>";
	echo "</div>";
?>
