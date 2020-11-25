<?php
  require_once("db_.php");

 ?>

 <nav class='navbar navbar-expand-sm navbar-light bg-light'>
 		  <a class='navbar-brand' ><i class='fas fa-boxes'></i>Inventario</a>
 		  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
 			<span class='navbar-toggler-icon'></span>
 		  </button>
 		  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
 			<ul class='navbar-nav mr-auto'>

        <form class='form-inline my-2 my-lg-0' is="b-submit" id="form_busca" des="a_inventario/lista" dix='trabajo' >
          <div class="input-group  mr-sm-2">
            <input type="text" class="form-control form-control-sm" placeholder="Buscar" aria-label="Buscar" aria-describedby="basic-addon2"  name='buscar' id='buscar'>
            <div class="input-group-append">
              <button class="btn btn-warning btn-sm" type="submit" ><i class='fas fa-search'></i></button>
            </div>
          </div>
        </form>
        <li class='nav-item active'><a class='nav-link barranav izq' is='a-link' title='Nuevo' id='new_poliza' des='a_inventario/agregar' v_id='0' dix='trabajo'><i class='fas fa-plus'></i><span>Agregar producto</span></a></li>

        <li class='nav-item active'><a class='nav-link barranav' is='a-link' title='Mostrar todo' id='lista_inventario' des='a_inventario/lista' dix='trabajo'><i class='fas fa-list-ul'></i><span>Inventario</span></a></li>

        <li class='nav-item active'><a class='nav-link barranav' is='a-link' title='Mostrar todo' id='lista_servicios' des='a_inventario/lista_servicios' dix='trabajo'><i class='fas fa-list-ul'></i><span>Servicios</span></a></li>

        <li class='nav-item active'><a class='nav-link barranav' is='a-link' title='Mostrar todo' id='lista_servicios' des='a_inventario/excel' dix='trabajo'><i class='fas fa-list-ul'></i><span>Excel</span></a></li>

        <?php
        	if(array_key_exists('PRODUCTOS', $db->derecho)){
            if($_SESSION['a_sistema']==1){
            //
            }

            //echo "<li class='nav-item active'><a class='nav-link barranav' is='a-link' title='Mostrar todo' id='lista_prod' des='a_productos/lista' dix='trabajo'><i class='fab fa-product-hunt'></i><span>Productos</span></a></li>";

            echo "<li class='nav-item active'><a class='nav-link barranav' is='a-link' title='Mostrar todo' id='lista_comision' des='a_categorias/lista' dix='trabajo'><i class='fas fa-box'></i><span>Categorias</span></a></li>";
          }
        ?>



      </li>
 			</ul>
 		</div>
 	  </div>
 	</nav>

  <div id='trabajo'>
  	<?php
  		include 'lista.php';
  	?>
  </div>
