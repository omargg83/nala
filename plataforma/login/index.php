<?php
  session_name("admin");
  @session_start();
  if(isset($_SESSION['idusuario']) and strlen($_SESSION['idusuario'])>0){
    header("location: /");
  }
 ?>
 <!DOCTYPE HTML>
 <html lang="es">
 <head>
 	<title>SAGYC</title>
  <link rel="icon" type="image/png" href="../../img/favicon.ico">
 	<meta charset="utf-8">
 	<meta http-equiv="X-UA-Compatible" content="IE=edge">
 	<meta name="viewport" content="width=device-width, initial-scale=1">

 	<meta http-equiv="Expires" content="0">
 	<meta http-equiv="Last-Modified" content="0">
 	<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
 	<meta http-equiv="Pragma" content="no-cache">

 	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
 	<link rel="stylesheet" href="../../lib/swal/dist/sweetalert2.min.css">
 	<link rel="stylesheet" href="login.css">
 </head>
 <body>
   <div class="container" style='overflow: hidden;'>
       <div class="card card-container login">
         <img id="profile-img" src="../../img/logo.png" width='250px'/>
         <p id="profile-name" class="profile-name-card"></p>

        <form is="f-login" id="form_login" db="login" fun="acceso" des="/">
          <div class="input-group mb-3">
            <input type="text" id="userAcceso"  name="userAcceso" class="form-control" placeholder="Usuario" required autofocus>
          </div>
          <div class="input-group mb-3">
            <input type="password" id="passAcceso" name="passAcceso" class="form-control" placeholder="Contraseña" required>
          </div>
           <button class="btn btn-danger btn-block" type="submit">Ingresar</button>
        </form>

       </div>
     </div>
 </body>

 	<!--   Core JS Files   -->
 	<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
 	<script src="../../lib/swal/dist/sweetalert2.min.js"></script>
 	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2&display=swap" rel="stylesheet">
 	<script>

  onload = ()=> {
    const form = document.getElementById('form_login');
    form.addEventListener('submit', logSubmit);
  };

  function logSubmit(e) {
    e.preventDefault();

    var userAcceso=document.getElementById("userAcceso").value;
    var passAcceso=document.getElementById("passAcceso").value;

    var formData = new FormData();
    formData.append("userAcceso", userAcceso);
    formData.append("passAcceso", passAcceso);
    formData.append("function", "acceso");
    formData.append("ctrl", "control");
    let xhr = new XMLHttpRequest();
    xhr.open('POST',"login.php");
    xhr.addEventListener('load',(data)=>{
      console.log(data.target.response);
      var data = JSON.parse(data.target.response);
      if (data.acceso==1){
        location.href="../";
      }
      else{
        Swal.fire({
          type: 'error',
          title: 'Usuario o contraseña incorrecta',
          showConfirmButton: false,
          timer: 1000
        })
      }
    });
    xhr.onerror = (e)=>{
      console.log(e);
    };
    xhr.send(formData);
  }

  </script>
 </html>
