<?php
echo "<div class='modal-header'>";
  echo "Agregar producto";
echo "</div>";
echo "<div class='card-body' >";
  echo "<form is='t-busca' id='form_busca' >";
    echo "<div clas='row'>";
        echo "<div class='input-group mb-3'>";
        echo "<input type='text' class='form-control form-control-sm' name='prod_venta' id='prod_venta' placeholder='buscar producto' aria-label='buscar producto' aria-describedby='basic-addon2'>";
        echo "<div class='input-group-append'>";
          echo "<button class='btn btn-warning btn-sm' type='submit' ><i class='fas fa-search'></i>Buscar</button>";
          echo "<button class='btn btn-warning btn-sm' type='button' is='b-link' cmodal='1' ><i class='fas fa-sign-out-alt'></i>Cerrar</button>";
        echo "</div>";
      echo "</div>";
    echo "</div>";
  echo "</form>";
echo "</div>";

echo "<div clas='row' id='resultadosx' style='max-height:400px; max-height: 500; overflow:auto;'>
</div>";

?>
