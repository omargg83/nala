
////////////// Ventas
function cambio_total(){
  let total_g=document.getElementById("total_g").value;
  let efectivo_g=document.getElementById("efectivo_g").value;
  let total=(efectivo_g-total_g)*100;
  total=Math.round(total)/100
  document.getElementById("cambio_g").value=total;
}
function calendar_load(tipo){
  var fecha = new Date();
  var final="";

  $('#trabajo').html("");
  var calendarEl = document.getElementById('trabajo');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
    editable: true,
    defaultView: 'dayGridMonth',
    defaultDate: fecha,
    buttonText:{
      today:    'Hoy',
      month:    'Mes',
      week:     'Semana',
      day:      'Dia',
      list:     'Lista'
    },
    locale: 'es',
    eventColor: '#378006',
    minTime: "10:00:00",
    maxTime: "18:00:00",
    slotDuration: "00:15:00",
    businessHours: {
      start: '9:00',
      end: '16:00',
    },
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    events: {
      url: 'a_citas/eventos.php?tipo='+tipo,
      failure: function() {
        document.getElementById('script-warning').style.display = 'block'
      }
    },
    dateClick: function(info) {
      console.log('Date: ' + info);
     },
    eventClick: function(info) {
      $('#myModal').modal('show');
      $("#modal_form").load("a_citas/info.php?id="+info.event.id);
    },
    eventDrop: function (info) { // this function is called when something is dropped

      if(info.event.end){
        final=info.event.end.toLocaleString();
      }

      $.confirm({
        title: 'Fechas',
        content: '¿Desea mover la cita seleccionada?',
        buttons: {
          Mover: function () {
            $.ajax({
              data:  {
                "horario":info.event.start.toLocaleString(),
                "horario2":final,
                "idcita":info.event.id,
                "function":"cambiar_hora"
              },
              url:   "a_citas/db_.php",
              type:  'post',
              success:  function (response) {
                console.log(response);
                var datos = JSON.parse(response);
                if (datos.error==0){
                  Swal.fire({
                    type: 'success',
                    title: "Se modificó correctamente",
                    showConfirmButton: false,
                    timer: 1000
                  });
                }
                else{
                  info.revert();
                }
              }
            });
          },
          Cancelar: function () {
            info.revert();
          }
        }
      });

    },
    eventResize: function(info) {

      if(info.event.end){
        final=info.event.end.toLocaleString();
      }

      $.confirm({
        title: 'Fecha',
        content: '¿Desea mover la cita seleccionada?',
        buttons: {
          Mover: function () {
            $.ajax({
              data:  {
                "horario":info.event.start.toLocaleString(),
                "horario2":final,
                "idcita":info.event.id,
                "function":"cambiar_hora"
              },
              url:   "a_citas/db_.php",
              type:  'post',
              success:  function (response) {
                console.log(response);
                var datos = JSON.parse(response);
                if (datos.error==0){
                  Swal.fire({
                    type: 'success',
                    title: "Se modificó correctamente",
                    showConfirmButton: false,
                    timer: 1000
                  });
                }
                else{
                  info.revert();
                }
              }
            });
          },
          Cancelar: function () {
            info.revert();
          }
        }
      });
    }
  });
  calendar.render();
}
function fondos(){
  var formData = new FormData();
  formData.append("function", "fondo_carga");
  formData.append("ctrl", "control");
  let xhr = new XMLHttpRequest();
  xhr.open('POST',"control_db.php");
  xhr.addEventListener('load',(data)=>{
    document.getElementById("fondo").innerHTML = data.target.response;
  });
  xhr.onerror = (e)=>{
  };
  xhr.send(formData);
}
function fondo(archivo){
  var formData = new FormData();
  formData.append("function", "fondo");
  formData.append("imagen", archivo);
  formData.append("ctrl", "control");
  let xhr = new XMLHttpRequest();
  xhr.open('POST',"control_db.php");
  xhr.addEventListener('load',(data)=>{
    document.body.style.backgroundImage = "url('"+ archivo +"')";
  });
  xhr.onerror = (e)=>{
  };
  xhr.send(formData);
}
function fijar(){
  if(document.querySelector('.sidebar')){
    document.getElementById("navx").classList.remove('sidebar');
    document.getElementById("navx").classList.add('sidebar_fija');

    document.getElementById("contenido").classList.remove('main');
    document.getElementById("contenido").classList.add('main_fija');

  }
  else{
    document.getElementById("navx").classList.remove('sidebar_fija');
    document.getElementById("navx").classList.add('sidebar');

    document.getElementById("contenido").classList.remove('main_fija');
    document.getElementById("contenido").classList.add('main');
  }
}

///////////////////////////////////////////////ESPECIAL

$(document).on('submit',"[is*='p-busca']",function(e){
  e.preventDefault();
  cargando(true);
  let id=e.currentTarget.attributes.id.nodeValue;
  let elemento = document.getElementById(id);

  let idventa=document.getElementById("idventa").value;
  let prod_venta=document.getElementById("prod_venta").value;

  var formData = new FormData(elemento);
  formData.append("idventa", idventa);
  formData.append("prod_venta", prod_venta);

  let xhr = new XMLHttpRequest();
  xhr.open('POST',"a_venta/productos_lista.php");
  xhr.addEventListener('load',(data)=>{
    document.getElementById("resultadosx").innerHTML =data.target.response;
    cargando(false);
  });
  xhr.onerror =  ()=>{
    cargando(false);
  };
  xhr.send(formData);

});
$(document).on('submit',"[is*='is-selecciona']",function(e){
  e.preventDefault();
  cargando(true);
  let id=e.currentTarget.attributes.id.nodeValue;
  let elemento = document.getElementById(id);

  let idventa=document.getElementById("idventa").value;
  let idcliente=document.getElementById("idcliente").value;

  var formData = new FormData(elemento);
  formData.append("function", "agregaventa");
  formData.append("idventa", idventa);
  formData.append("idcliente", idcliente);

  for(let contar=0;contar<elemento.attributes.length; contar++){
    let arrayDeCadenas = elemento.attributes[contar].name.split("_");
    if(arrayDeCadenas.length>1){
      formData.append(arrayDeCadenas[1], elemento.attributes[contar].value);
    }
  }
  let xhr = new XMLHttpRequest();
  xhr.open('POST',"a_venta/db_.php");
  xhr.addEventListener('load',(data)=>{
    var datos = JSON.parse(data.target.response);
    if(datos.error==0){
      $('#myModal').modal('hide');
      document.getElementById("idventa").value=datos.idventa;
      document.getElementById("numero").value=datos.numero;
      document.getElementById("fecha").value=datos.fecha;
      document.getElementById("estado").value=datos.estado;
      document.getElementById("total").value=datos.total;
      lista(datos.idventa);
      document.getElementById("resultadosx").innerHTML ="";

    }
    else{
      cargando(false);
      Swal.fire({
        type: 'error',
        title: "Error: "+datos.terror,
        showConfirmButton: false,
        timer: 1000
      });
      return;
    }
  });
  xhr.onerror =  ()=>{
    cargando(false);
  };
  xhr.send(formData);
});
$(document).on('click',"[is*='is-borraprod']",function(e){
  e.preventDefault();
  let idventa=document.getElementById("idventa").value;
  let idbodega=e.currentTarget.attributes.v_idbodega.value;
  let formData = new FormData();

  $.confirm({
    title: 'Eliminar',
    content: '¿Desea eliminar el producto seleccionada?',
    buttons: {
      Eliminar: function () {
        cargando(true);
        formData.append("idventa", idventa);
        formData.append("idbodega", idbodega);
        formData.append("function", "borrar_venta");

        let xhr = new XMLHttpRequest();
        xhr.open('POST',"a_venta/db_.php");
        xhr.addEventListener('load',(data)=>{
          var datos = JSON.parse(data.target.response);
          document.getElementById("total").value=datos.total;
          lista(idventa);
        });
        xhr.onerror =  ()=>{
          cargando(false);
        };
        xhr.send(formData);
      },
      Cancelar: function () {

      }
    }
  });
});
$(document).on('click',"[is*='is-cliente']",function(e){
  e.preventDefault();
  cargando(true);
  let idventa=document.getElementById("idventa").value;
  let idcliente=e.currentTarget.attributes.v_idcliente.value;

  let formData = new FormData();
  formData.append("idventa", idventa);
  formData.append("idcliente", idcliente);
  formData.append("function", "agregar_cliente");

  let xhr = new XMLHttpRequest();
  xhr.open('POST',"a_venta/db_.php");
  xhr.addEventListener('load',(data)=>{
    cliente_datos(idcliente);
    $('#myModal').modal('hide');
    cargando(false);
  });
  xhr.onerror =  ()=>{
    cargando(false);
  };
  xhr.send(formData);

});
$(document).on('click',"[is*='is-finalizar']",function(e){
  e.preventDefault();
  let idventa=document.getElementById("idventa").value;
  $('#myModal').modal('show');

  let formData = new FormData();
  formData.append("idventa", idventa);

  let xhr = new XMLHttpRequest();
  xhr.open('POST',"a_venta/finalizar.php");
  xhr.addEventListener('load',(data)=>{
    document.getElementById("modal_form").innerHTML = data.target.response;
  });
  xhr.onerror =  ()=>{

  };
  xhr.send(formData);
});
$(document).on('submit',"[is*='is-totalv']",function(e){
  e.preventDefault();
  cargando(true);
  let idventa=document.getElementById("idventa").value;
  let tipo_pago=document.getElementById("tipo_pago").value;
  let total_g=document.getElementById("total_g").value;
  let efectivo_g=document.getElementById("efectivo_g").value;
  let cambio_g=document.getElementById("cambio_g").value;

  let formData = new FormData();
  formData.append("idventa", idventa);
  formData.append("tipo_pago", tipo_pago);
  formData.append("total_g", total_g);
  formData.append("efectivo_g", efectivo_g);
  formData.append("cambio_g", cambio_g);
  formData.append("function", "finalizar_venta");

  let xhr = new XMLHttpRequest();
  xhr.open('POST',"a_venta/db_.php");
  xhr.addEventListener('load',(data)=>{
    venta(idventa);
    cargando(false);
    $('#myModal').modal('hide');
    Swal.fire({
      type: 'success',
      title: "Se proceso correctamente",
      showConfirmButton: false,
      timer: 2000
    });
  });
  xhr.onerror =  ()=>{
    cargando(false);
  };
  xhr.send(formData);
});


function calcular(){

  let idproducto=document.getElementById("idproducto").value;
  let cantidad=document.getElementById("cantidad").value;

  let formData = new FormData();
  formData.append("function", "esquemas");
  formData.append("idproducto", idproducto);
  formData.append("cantidad", cantidad);


  let xhr = new XMLHttpRequest();
  xhr.open('POST',"a_venta/db_.php");
  xhr.addEventListener('load',(data)=>{
    var datos = JSON.parse(data.target.response);
    document.getElementById("normal").value=datos.total_menudeo;
    document.getElementById("mayoreo").value=datos.total_mayoreo;
    document.getElementById("distribuidor").value=datos.total_distribuidor;
    document.getElementById("precio").value=datos.precio;
  });
  xhr.onerror =  ()=>{
    cargando(false);
  };
  xhr.send(formData);


  console.log("entra");

}

function lista(idventa){
  var formData = new FormData();
  formData.append("idventa", idventa);
  let xhr = new XMLHttpRequest();
  xhr.open('POST',"a_venta/lista_pedido.php");
  xhr.addEventListener('load',(data)=>{
    document.getElementById("lista").innerHTML=data.target.response;
    cargando(false);
  });
  xhr.onerror =  ()=>{
    cargando(false);
  };
  xhr.send(formData);
}
function datos_compra(idventa){
  var formData = new FormData();
  formData.append("idventa", idventa);

  let xhr = new XMLHttpRequest();
  xhr.open('POST',"a_venta/dato_compra.php");
  xhr.addEventListener('load',(data)=>{
    document.getElementById("dato_compra").innerHTML =data.target.response;
  });
  xhr.onerror =  ()=>{
  };
  xhr.send(formData);
}
function cliente_datos(idcliente){
  var formData = new FormData();
  formData.append("idcliente", idcliente);

  let xhr = new XMLHttpRequest();
  xhr.open('POST',"a_venta/cliente_datos.php");
  xhr.addEventListener('load',(data)=>{
    document.getElementById("cliente_datos").innerHTML =data.target.response;
  });
  xhr.onerror =  ()=>{
  };
  xhr.send(formData);

}
function venta(idventa){
  let formData = new FormData();
  formData.append("idventa", idventa);
  let xhr = new XMLHttpRequest();
  xhr.open('POST',"a_venta/venta.php");
  xhr.addEventListener('load',(data)=>{
    document.getElementById("trabajo").innerHTML = data.target.response;
  });
  xhr.onerror =  ()=>{
  };
  xhr.send(formData);
}
