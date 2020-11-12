<?php
	require_once("db_.php");
	if(!isset($_SESSION['idadmin']) or strlen($_SESSION['idadmin'])==0 or $_SESSION['autoriza']==0){
		header("location: login/");
	}
?>
<!DOCTYPE HTML>
<html lang="es">
<head>
	<title>SAGYC Admin</title>
	<link rel="icon" type="image/png" href="../img/favicon.ico">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Last-Modified" content="0">
	<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
	<meta http-equiv="Pragma" content="no-cache">

	<link rel="stylesheet" href="../lib/load/css-loader.css">
	<link rel="stylesheet" type="text/css" href="modulos.css"/>
</head>

<?php
	$valor=$_SESSION['idfondo'];
	echo "<body style='background-image: url(\"$valor\")'>";
?>


<header class="d-block p-2" id='header'>
	<nav class='barraprincipal navbar navbar-expand-sm fixed-top navbar-light bg-light text-'  style='background-color: #e4e9ee !important; color: white !important;'>

		<button class="btn btn-warning btn-sm mr-2" type="button" onclick='fijar()'><i class='fas fa-bars'></i></button>

	  <img src='../img/sagyc.png' width='60' height='30' alt=''>
	  <a class='navbar-brand text-black text-center ml-3' href='#'> Administracion  </a>
	  <button class='navbar-toggler collapsed' type='button' data-toggle='collapse' data-target='#navbarsExample06' aria-controls='navbarsExample06' aria-expanded='false' aria-label='Toggle navigation'>
	    <span class='navbar-toggler-icon'></span>
	  </button>
	  <div class='navbar-collapse collapse' id='navbarsExample06' style=''>

	    <ul class='navbar-nav mr-auto'>

	    </ul>
      <ul class='nav navbar-nav navbar-right text-white' id='precios'>

			</ul>
      <ul class='nav navbar-nav navbar-right text-white' id='fondo'></ul>
      <ul class='nav navbar-nav navbar-right'>
        <li class='nav-item'>
          <a class='nav-link pull-left text-black' onclick='salir()'>
            <i class='fas fa-sign-out-alt text-dark'></i> Salir
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
	      <div class='sidebar sidenav' id='navx'>
	        <a href='#dash/index' is='menu-link' class='activeside'><i class='fas fa-home'></i><span>Inicio</span></a>

					<a href='#a_empresas/index' id='venta' is='menu-link' title='Empresas'><i class='fas fa-cash-register'></i><span>Empresas</span></a>

	      </div>
	    </div>
	    <div class='fijaproceso main' id='contenido'>
	    </div>
	  </div>
	</div>
</div>

<div class="modal animated fadeInDown" tabindex="-1" role="dialog" id="myModal" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document" id='modal_dispo'>
		<div class="modal-content" id='modal_form'>

		</div>
	</div>
</div>

<div class="loader loader-default is-active" id='cargando_div' data-text="Cargando">
	<h2><span style='font-color:white'></span></h2>
</div>

</body>
	<!--   Core JS Files   -->
	<script src="../lib/jquery-3.5.1.js" type="text/javascript"></script>

	<!-- Animation library for notifications   -->
  <link href="../lib/animate.css" rel="stylesheet"/>

	<!--   Alertas   -->
	<script src="../lib/swal/dist/sweetalert2.min.js"></script>
	<link rel="stylesheet" href="../lib/swal/dist/sweetalert2.min.css">

	<!--   para imprimir   -->
	<script src="../lib/VentanaCentrada.js" type="text/javascript"></script>

	<!--   Cuadros de confirmación y dialogo   -->
	<link rel="stylesheet" href="../lib/jqueryconfirm/css/jquery-confirm.css">
	<script src="../lib/jqueryconfirm/js/jquery-confirm.js"></script>

	<!--   iconos   -->
	<link rel="stylesheet" href="../lib/fontawesome-free-5.12.1-web/css/all.css">

	<script src="../lib/popper.js"></script>
	<script src="../lib/tooltip.js"></script>

	<!--   Propios   -->
	<script src="sagyc.js"></script>

	<link href="https://fonts.googleapis.com/css2?family=Baloo+2&display=swap" rel="stylesheet">

	<!--   Boostrap   -->
	<link rel="stylesheet" href="../lib/boostrap/css/bootstrap.min.css">
	<script src="../lib/boostrap/js/bootstrap.js"></script>
</html>
