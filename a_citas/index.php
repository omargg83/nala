<?php
  require_once("db_.php");

 ?>

 <nav class='navbar navbar-expand-sm navbar-light bg-light'>
 		  <a class='navbar-brand' ><i class="fas fa-shopping-basket"></i>Citas/Agenda</a>
 		  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
 			<span class='navbar-toggler-icon'></span>
 		  </button>
 		  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
 			<ul class='navbar-nav mr-auto'>

        <form class='form-inline my-2 my-lg-0' is="b-submit" id="daigual" des="a_citas/lista" dix='trabajo' >
          <div class="input-group  mr-sm-2">
            <input type="text" class="form-control form-control-sm" placeholder="Buscar" aria-label="Buscar" aria-describedby="basic-addon2"  name='buscar' id='buscar'>
            <div class="input-group-append">
              <button class="btn btn-warning btn-sm" type="submit" ><i class='fas fa-search'></i></button>
            </div>
          </div>
        </form>

        <li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='calendario' onclick='calendar_load(1)'><i class='far fa-calendar-alt'></i><span>Calendario</span></a></li>

        <li class='nav-item active'><a class='nav-link barranav' is='a-link' title='Mostrar todo' id='lista_prod' des='a_citas/lista' dix='trabajo'><i class='fas fa-list-ul'></i><span>Lista</span></a></li>

        <?php
        //if($db->nivel_captura==1){
          echo "<li class='nav-item active'><a class='nav-link barranav izq' is='a-link' title='Nuevo' id='new_poliza' des='a_citas/editar' v_idcita='0' dix='trabajo'><i class='fas fa-plus'></i><span>Nueva cita/agenda</span></a></li></button>";

    //    }
  //  print_r($db);
        ?>

      </li>

 			</ul>
 		</div>
 	  </div>
 	</nav>
<?php

   echo "<div id='trabajo'  style='background-color:".$_SESSION['cfondo']."; '>";
    include 'lista.php';
   echo "</div>";

 ?>
<script type="text/javascript">

  $(function(){
    calendar_load(1);
  });


  function editar_cita(id){
    $.ajax({
      data:  {
        "id":id
      },
      url:   'a_citas/editar.php',
      type:  'post',
      beforeSend: function () {
          $('#myModal').modal('hide');
      },
      success:  function (response) {
        $("#trabajo").html(response);
      }
    });
  }
  function buscar_cliente(){
    var texto=$("#prod_venta").val();
    var idcliente=$("#idcliente").val();
    var idcita=$("#idcita").val();
    if(texto.length>=-1){
      $.ajax({
        data:  {
          "texto":texto,
          "idcliente":idcliente,
          "idcita":idcita,
          "function":"busca_cliente"
        },
        url:   "a_citas/db_.php",
        type:  'post',
        beforeSend: function () {
          $("#resultadosx").html("buscando...");
        },
        success:  function (response) {
          $("#resultadosx").html(response);
          $("#prod_venta").val();
        }
      });
    }
  }
  function cliente_add(idcliente,idcita){
    $.confirm({
      title: 'Cliente',
      content: '¿Desea agregar el cliente seleccionado?',
      buttons: {
        Aceptar: function () {
          $.ajax({
            data:  {
              "idcliente":idcliente,
              "idcita":idcita,
              "function":"agrega_cliente"
            },
            url:   "a_citas/db_.php",
            type:  'post',
            success:  function (response) {
              var datos = JSON.parse(response);
              if (datos.idcliente>0){
                $("#idcliente").val(datos.idcliente);
                $("#nombre").val(datos.nombre);
                $("#correo").val(datos.correo);
                $("#telefono").val(datos.telefono);
                Swal.fire({
                  type: 'success',
                  title: "Se agregó correctamente",
                  showConfirmButton: false,
                  timer: 1000
                });
                $('#myModal').modal('hide');
              }
              else{
                $.alert(datos.terror);
              }
            }
          });
        },
        Cancelar: function () {

        }
      }
    });
  }
  function buscar_pedido(){
    var buscar = $("#buscar").val();
    $.ajax({
      data:  {
        "buscar":buscar
      },
      url:   'a_citas/lista.php',
      type:  'post',
      success:  function (response) {
        $("#trabajo").html(response);
      }
    });
  }
  function buscar_prodpedido(){
  	var texto=$("#prod_venta").val();
  	var idproducto=$("#idproducto").val();
  	var idcitas=$("#idcitas").val();
  	if(texto.length>=-1){
  		$.ajax({
  			data:  {
  				"texto":texto,
  				"idproducto":idproducto,
  				"idcitas":idcitas,
  				"function":"busca_producto"
  			},
  			url:   "a_citas/db_.php",
  			type:  'post',
  			beforeSend: function () {
  				$("#resultadosx").html("buscando...");
  			},
  			success:  function (response) {
  				$("#resultadosx").html(response);
  				$("#prod_venta").val();
  			}
  		});
  	}
  }
  function sel_prod(idproducto,idcitas){
  	$.ajax({
  		data:  {
  			"idproducto":idproducto,
  			"idcitas":idcitas,
  			"function":"selecciona_producto"
  		},
  		url:   "a_citas/db_.php",
  		type:  'post',
  		success:  function (response) {
  			$("#resultadosx").html(response);
  		}
  	});
  }
  function prod_add(id,idpedido){
    var cantidad=$("#cantidad_"+id).val();
    $.confirm({
      title: 'Producto',
      content: '¿Desea agregar el producto seleccionado?',
      buttons: {
        Aceptar: function () {
          $.ajax({
            data:  {
              "id":id,
              "idpedido":idpedido,
              "cantidad":cantidad,
              "function":"producto_add"
            },
            url:   "a_citas/db_.php",
            type:  'post',
            success:  function (response) {
              console.log(response);
              var datos = JSON.parse(response);
              if (datos.error==0){
                $.ajax({
                  data:  {
                    "id":datos.id
                  },
                  url:   'a_citas/editar.php',
                  type:  'post',
                  success:  function (response) {
                    $("#trabajo").html(response);
                  }
                });
                Swal.fire({
                  type: 'success',
                  title: "Se agregó correctamente",
                  showConfirmButton: false,
                  timer: 1000
                });
                $('#myModal').modal('hide');
              }
              else{
                $.alert(datos.terror);
              }
            }
          });
        },
        Cancelar: function () {
          $.alert('Canceled!');
        }
      }
    });
  }
  function elimina_cuadmin(id,idpedido){
    $.confirm({
        title: 'Cupon',
        content: '¿Desea eliminar el cupón?',
        buttons: {
          Eliminar: function () {
            $.ajax({
              data:  {
                "id":id,
                "idpedido":idpedido,
                "function":"elimina_cupon"
              },
              url:   'a_citas/db_.php',
              type:  'post',
              timeout:3000,
              beforeSend: function () {

              },
              success:  function (response) {
                $("#trabajo").load("a_citas/editar.php?id="+idpedido);
              },
              error: function(jqXHR, textStatus, errorThrown) {

              }
            });

          },
          Cancelar: function () {

          }
        }
      });
  }
</script>
