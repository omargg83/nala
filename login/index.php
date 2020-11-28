<?php
  session_name("chingon");
  @session_start();
  if(isset($_SESSION['idusuario']) and strlen($_SESSION['idusuario'])>0){
    header("location: /");
  }
 ?>
 <!DOCTYPE HTML>
 <html lang="es">
 <head>
 	<title>SAGYC POS</title>
  <link rel="icon" type="image/png" href="../img/favicon.ico">
 	<meta charset="utf-8">
 	<meta http-equiv="X-UA-Compatible" content="IE=edge">
 	<meta name="viewport" content="width=device-width, initial-scale=1">

 	<meta http-equiv="Expires" content="0">
 	<meta http-equiv="Last-Modified" content="0">
 	<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
 	<meta http-equiv="Pragma" content="no-cache">

  <link rel="stylesheet" href="../lib/boostrap/css/bootstrap.min.css">
 	<link rel="stylesheet" href="../lib/swal/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="../lib/load/css-loader.css">
 	<link rel="stylesheet" href="login.css">
 </head>
 <?php

 $arreglo=array();
 $directory="../fondo/";
 $dirint = dir($directory);
 $contar=0;
 while (($archivo = $dirint->read()) !== false){
   if ($archivo != "." && $archivo != ".." && $archivo != "" && substr($archivo,-4)==".jpg"){
     $arreglo[$contar]=$directory.$archivo;
     $contar++;
   }
 }
 $valor=$arreglo[rand(1,$contar-1)];
   echo "<body style='background-image: url(\"$valor\")'>";
 ?>
   <div class="container" style='overflow: hidden;'>
       <div class="card card-container login">
         <img id="profile-img" src="../img/logo.png" width='250px'/>
         <p id="profile-name" class="profile-name-card"></p>
         <div id='login'>
           <?php
             include "log.php";
           ?>
         </div>

       </div>
     </div>

   <div class="loader loader-double is-active" id='cargando_div'>
   	<h2><span style='font-color:white'></span></h2>
   </div>
 </body>

 	<!--   Core JS Files   -->
  <script src="../lib/jquery-3.5.1.js" type="text/javascript"></script>
 	<script src="../lib/swal/dist/sweetalert2.min.js"></script>
  <script src="../lib/boostrap/js/bootstrap.min.js"></script>
  <script src="log.js" type="text/javascript"></script>

 </html>
