
<div class='modal-header'>
  <h5 class='modal-title'>Checador de Precio y Stock</h5>
</div>

<div class='modal-body' >
  <form is="b-submit" id="form_busca" des="a_precios/productos_lista" dix='resultado_precio' >
    <div clas='row'>
        <div class="input-group mb-3">
        <input type="text" class="form-control" name="prod_venta" id='prod_venta' placeholder='buscar producto' aria-label="buscar producto" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <button class="btn btn-warning btn-sm" type="submit" ><i class='fas fa-search'></i>Buscar</button>
        </div>
      </div>
    </div>
  </form>
  <div clas='row' id='resultado_precio'>

  </div>
</div>

<div class='modal-footer' >
  <button class="btn btn-warning btn-sm" type="button" is="b-link" cmodal='1' ><i class="fas fa-sign-out-alt"></i>Salir</button>
</div>
