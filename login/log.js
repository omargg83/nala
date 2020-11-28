onload = ()=> {
  cargando(false);
};
function cargando(valor) {
  let element = document.getElementById("cargando_div");
  if(valor){
    element.classList.add("is-active");
  }
  else{
    element.classList.remove("is-active");
  }
}
function isJSON (something) {
  if (typeof something != 'string')
      something = JSON.stringify(something);
  try {
      JSON.parse(something);
      return true;
  } catch (e) {
      return false;
  }
}
function regresar(){
  let xhr = new XMLHttpRequest();
  xhr.open('POST',"log.php");
  xhr.addEventListener('load',(data)=>{
    document.getElementById("login").innerHTML =data.target.response;
  });
  xhr.send();
}
$(document).on('submit',"#form_login",function(e){
  e.preventDefault();

  let elemento = document.getElementById("form_login");
  var formData = new FormData(elemento);
  formData.append("function", "acceso");
  formData.append("ctrl", "control");
  let xhr = new XMLHttpRequest();
  xhr.open('POST',"db_.php");
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
  xhr.send(formData);
});
$(document).on('submit',"#form_rec",function(e){
  e.preventDefault();
  let elemento = document.getElementById("form_rec");
  var formData = new FormData(elemento);
  formData.append("function", "rec");
  formData.append("ctrl", "control");
  let xhr = new XMLHttpRequest();
  xhr.open('POST',"db_.php");
  xhr.addEventListener('loadstart',(data)=>{
    cargando(true);
  });
  xhr.addEventListener('load',(data)=>{
    console.log("algo:"+data.target.response);
    if (isJSON(data.target.response)){
      var datos = JSON.parse(data.target.response);
      if (datos.error==0){
        Swal.fire({
          type: 'success',
          title: "Enviado",
          text: datos.terror
        }).then((result) => {
          if (result.value) {
            regresar();
          }
        });
      }
      else{
        Swal.fire({
          type: 'error',
          title: 'Error',
          text:datos.terror,
          showConfirmButton: false,
          timer: 4000
        })
      }
    }
    cargando(false);
  });
  xhr.send(formData);
});
$(document).on('click',"#recuperar",function(e){
  e.preventDefault();
  let xhr = new XMLHttpRequest();
  xhr.open('POST',"recuperar.php");
  xhr.addEventListener('load',(data)=>{
    document.getElementById("login").innerHTML =data.target.response;
  });
  xhr.send();
});
$(document).on('click',"#reg",function(e){
  regresar();
});
