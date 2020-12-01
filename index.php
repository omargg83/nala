<?php
	require_once("db_.php");
	if(!isset($_SESSION['idusuario']) or strlen($_SESSION['idusuario'])==0 or $_SESSION['autoriza']==0){
		header("location: login/");
	}
?>
<!DOCTYPE HTML>
<html lang="es">
<head>
	<title>SAGYC POS</title>
	<link rel="icon" type="image/png" href="img/favicon.ico">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Last-Modified" content="0">
	<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
	<meta http-equiv="Pragma" content="no-cache">

	<link rel="stylesheet" href="lib/load/css-loader.css">
	<link rel="stylesheet" type="text/css" href="lib/modulos.css"/>
	<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="chat/chat.css" />
	<link href="lib/animate.min.css" rel="stylesheet"/>
</head>

<?php
	$valor=$_SESSION['idfondo'];
	echo "<body style='background-image: url(\"$valor\")'>";
?>

<header class="d-block p-2" id='header'>
	<nav class='barraprincipal navbar navbar-expand-md fixed-top navbar-light bg-light' >

		<button class="btn btn-warning btn-sm mr-2" type="button" onclick='fijar()'><i class='fas fa-bars'></i></button>

	  <img src='img/sagyc.png' width='60' height='30' alt=''>
	  <a class='navbar-brand text-black text-center ml-3' href='#'>
			<?php
			echo $_SESSION['n_sistema'];
			echo "<small> - ".$_SESSION['sucursal_nombre']."</small>";
			if($_SESSION['a_sistema']==0){
				echo " - SISTEMA INACTIVO";
			}
			?>  </a>
	  <button class='navbar-toggler collapsed' type='button' data-toggle='collapse' data-target='#navbarsExample06' aria-controls='navbarsExample06' aria-expanded='false' aria-label='Toggle navigation'>
	    <span class='navbar-toggler-icon'></span>
	  </button>
	  <div class='navbar-collapse collapse' id='navbarsExample06' style=''>

	    <ul class='navbar-nav mr-auto px-1'></ul>
			<ul class='nav navbar-nav navbar-right px-1' id='chatx'></ul>
			<?php
				if($_SESSION['a_sistema']==1){
      		echo "<ul class='nav navbar-nav navbar-right px-1' id='fondo'></ul>";
			 	}
			?>
      <ul class='nav navbar-nav navbar-right px-1' id='precios'>
				<?php
					if($_SESSION['a_sistema']==1){
						echo "<li class='nav-item'>";
		          echo "<a class='nav-link pull-left bg-warning rounded' is='b-link' des='a_precios/index' omodal='1'>";
		            echo "<i class='fas fa-search-dollar'></i>";
		          echo "</a>";
		        echo "</li>";
					}
				?>
			</ul>

      <ul class='nav navbar-nav navbar-right px-1'>
        <li class='nav-item'>
          <a class='nav-link pull-left border border-warning rounded' onclick='salir()'>
            <i class='fas fa-sign-out-alt text-dark'></i>
          </a>
        </li>
      </ul>
	  </div>
	</nav>
</header>

<div class="page-wrapper d-block p-2" id='bodyx'>
	<div class='wrapper' >
	  <div class='content navbar-default'>
	    <div class='container-fluid'>

					<?php
						if($_SESSION['sidebar']==1){
							echo "<div class='sidebar_fija' id='navx'>";
						}
						else{
							echo "<div class='sidebar' id='navx'>";
						}
					?>

					<div class="sidebar-header">
		        <div class="user-pic">
							<?php
								if(strlen($_SESSION['foto'])>0 and file_exists($db->f_usuarios."/".$_SESSION['foto'])){
									echo "<img class='img-responsive img-rounded' src='".$db->f_usuarios.$_SESSION['foto']."' alt='User picture'>";
								}
								else{
									echo "<img class='img-responsive img-rounded' src='https://raw.githubusercontent.com/azouaoui-med/pro-sidebar-template/gh-pages/src/img/user.jpg' alt='User picture'>";
								}
							?>
		        </div>
		        <div class="user-info" style='width: 80px; white-space: nowrap; text-overflow: ellipsis;overflow: hidden;'>
		          <span class="user-name" ><?php echo $_SESSION['nombre'] ?></span>
		          <span class="user-role"><?php echo $_SESSION['sucursal_nombre']; ?></span>
		          <span class="user-status">
		            <i class="fa fa-circle"></i>
		            <span>Online</span>
		          </span>
		        </div>
      		</div>

					<div class="sidebar-body">
	        	<a href='#dash/index' is='menu-link' class='activeside'><i class='fas fa-home'></i><span>Inicio</span></a>
					<?php
						if(array_key_exists('VENTA', $db->derecho) and $_SESSION['a_sistema']==1)
						echo "<a href='#a_venta/venta' id='ventax' is='menu-link' title='Pedidos'><i class='fas fa-cash-register'></i><span>+ Venta</span></a>";

						if(array_key_exists('VENTAREGISTRO', $db->derecho))
						echo "<a href='#a_ventas/index' id='ventas' is='menu-link' title='Pedidos'><i class='fas fa-shopping-basket'></i><span>Ventas</span></a>";

						if(array_key_exists('COMPRAS', $db->derecho))
						echo "<a href='#a_compras/index' is='menu-link' title='Compras'><i class='fas fa-shopping-bag'></i><span>Compras</span></a>";

						if(array_key_exists('PRODUCTOS', $db->derecho) and $_SESSION['matriz']==1)
						echo "<a href='#a_productos/index' is='menu-link' title='Productos'><i class='fab fa-product-hunt'></i><span>Catalogo</span></a>";

						if(array_key_exists('INVENTARIO', $db->derecho))
						echo "<a href='#a_inventario/index' is='menu-link' title='inventario'><i class='fas fa-boxes'></i><span>Inventario</span></a>";

						if(array_key_exists('CLIENTES', $db->derecho))
						echo "<a href='#a_cliente/index' is='menu-link' title='Clientes'><i class='fas fa-user-tag'></i><span>Clientes</span></a>";

						if(array_key_exists('CITAS', $db->derecho))
						echo "<a href='#a_citas/index' is='menu-link' title='Citas'><i class='far fa-calendar-check'></i><span>Citas/Agenda</span></a>";

						if(array_key_exists('PROVEEDORES', $db->derecho))
						echo "<a href='#a_proveedores/index' is='menu-link' title='Proveedores'><i class='fas fa-people-carry'></i><span>Proveedores</span></a>";

						if(array_key_exists('TRASPASOS', $db->derecho))
						echo "<a href='#a_traspasos/index' is='menu-link' title='Traspasos'><i class='fas fa-arrows-alt-h'></i><span>Traspasos</span></a>";

						if(array_key_exists('GASTOS', $db->derecho))
		        echo "<a href='#a_gastos/index' is='menu-link' title='Datosemp'><i class='fas fa-donate'></i><span>Gastos</span></a>";

						if(array_key_exists('REPORTES', $db->derecho) and $_SESSION['a_sistema']==1)
						echo "<a href='#a_reporte/index' is='menu-link' title='Reportes'><i class='far fa-chart-bar'></i> <span>Reportes</span></a>";

						if(array_key_exists('USUARIOS', $db->derecho) or $_SESSION['nivel']==0)
						echo "<a href='#a_usuarios/index' is='menu-link' title='Usuarios'><i class='fas fa-users'></i> <span>Usuarios</span></a>";

						if(array_key_exists('DATOSEMP', $db->derecho))
		        echo "<a href='#a_datosemp/index' is='menu-link' title='Datos de la empresa'><i class='fas fa-wrench'></i><span>Datos Emp.</span></a>";

						if(array_key_exists('SUPERVISOR', $db->derecho))
		        echo "<a href='#a_supervisor/index' is='menu-link' title='Supervisor'><i class='far fa-eye'></i><span>Supervisor</span></a>";
					?>
					</div>
	      </div>
			</div>
			<?php
				if($_SESSION['sidebar']==1){
					echo "<div class='fijaproceso main_fija' id='contenido'>";
				}
				else{
					echo "<div class='fijaproceso main' id='contenido'>";
				}
			?>
	    </div>
	  </div>
	</div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="myModal" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document" id='modal_dispo'>
		<div class="modal-content" id='modal_form'>

		</div>
	</div>
</div>

<div class="loader loader-double is-active" id='cargando_div'>
	<h2><span style='font-color:white'></span></h2>
</div>


</body>
	<!--   Core JS Files   -->
	<script src="lib/jquery-3.5.1.js" type="text/javascript"></script>

	<!--   url   -->
	<script src="lib/jquery/jquery-ui.js"></script>
	<link rel="stylesheet" type="text/css" href="lib/jquery/jquery-ui.min.css" />



	<!--   Alertas   -->
	<script src="lib/swal/dist/sweetalert2.min.js"></script>
	<link rel="stylesheet" href="lib/swal/dist/sweetalert2.min.css">

	<!--   para imprimir   -->
	<script src="lib/VentanaCentrada.js" type="text/javascript"></script>

	<!--   Cuadros de confirmación y dialogo   -->
	<link rel="stylesheet" href="lib/jqueryconfirm/css/jquery-confirm.css">
	<script src="lib/jqueryconfirm/js/jquery-confirm.js"></script>

	<!--   iconos   -->
	<link rel="stylesheet" href="lib/fontawesome-free-5.12.1-web/css/all.css">

	<script src="lib/popper.min.js"></script>
	<script src="lib/tooltip.js"></script>

	<!--   Propios   -->
	<script src="chat/chat.js"></script>
	<script src="sagyc.js"></script>
	<script src="vainilla.js"></script>


	<link rel="stylesheet" type="text/css" href="lib/modulos.css"/>

	<!--- calendario -->
	<link href='lib/fullcalendar-4.0.1/packages/core/main.css' rel='stylesheet' />
	<link href='lib/fullcalendar-4.0.1/packages/daygrid/main.css' rel='stylesheet' />
	<link href='lib/fullcalendar-4.0.1/packages/timegrid/main.css' rel='stylesheet' />

	<script src='lib/fullcalendar-4.0.1/packages/core/main.js'></script>
	<script src='lib/fullcalendar-4.0.1/packages/interaction/main.js'></script>
	<script src='lib/fullcalendar-4.0.1/packages/daygrid/main.js'></script>
	<script src='lib/fullcalendar-4.0.1/packages/timegrid/main.js'></script>
	<script src='lib/fullcalendar-4.0.1/packages/core/locales/es.js'></script>

	<!--   Boostrap   -->
	<link rel="stylesheet" href="lib/boostrap/css/bootstrap.min.css">
	<script src="lib/boostrap/js/bootstrap.js"></script>
</html>
