
	let intval=""
	let debugx=1;
	let db_inicial="admin_db.php";

	onload = ()=> {
		if(intval==""){
			intval=setInterval(function(){ sesion_ver(); }, 10000);
		}
		loadContent(location.hash.slice(1));
	};

	let url=window.location.href;
	let hash=url.substring(url.indexOf("#")+1);
	if(hash===url || hash===''){
		hash='dash/index';
	}

	window.addEventListener("hashchange", (e)=>{
		loadContent(location.hash.slice(1));
	},false);	///////////////////para el hash

	function loadContent(hash){
		cargando(true);
		let formData = new FormData();
		let arrayDeCadenas = hash.split("?");
		let nhash=arrayDeCadenas[0];
		if(arrayDeCadenas.length>1){
			let query=arrayDeCadenas[1];
			var vars = query.split("&");
			for (var i=0; i < vars.length; i++) {
			var pair = vars[i].split("=");
				formData.append(pair[0],pair[1]);
			}
		}
		if(nhash==''){
			nhash= 'dash/index';
		}
		let destino=nhash + '.php';
		let xhr = new XMLHttpRequest();
		xhr.open('POST',destino);
		xhr.addEventListener('load',(data)=>{
			document.getElementById("contenido").innerHTML =data.target.response;
			var scripts = document.getElementById("contenido").getElementsByTagName("script");
			for (var i = 0; i < scripts.length; i++) {
				eval(scripts[i].innerText);
			}
			cargando(false);
		});
		xhr.onerror = (e)=>{
			cargando(false);
		};
		xhr.send(formData);
	}

	function fechas () {
		$.datepicker.regional['es'] = {
			 closeText: 'Cerrar',
			 yearRange: '1910:2040',
			 prevText: '<Ant',
			 nextText: 'Sig>',
			 currentText: 'Hoy',
			 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
			 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
			 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
			 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
			 weekHeader: 'Sm',
			 dateFormat: 'dd-mm-yy',
			 firstDay: 0,
			 isRTL: false,
			 showMonthAfterYear: false,
			 yearSuffix: ''
		 };

		$.datepicker.setDefaults($.datepicker.regional['es']);
		$(".fechaclass").datepicker();
	};
	function salir(){
		var formData = new FormData();
		formData.append("function", "salir");
		formData.append("ctrl", "control");
		let xhr = new XMLHttpRequest();
		xhr.open('POST',db_inicial);
		xhr.addEventListener('load',(data)=>{
			location.href ="login/";
		});
		xhr.onerror = (e)=>{
			console.log(e);
		};
		xhr.send(formData);
	}
	function sesion_ver(){
		var formData = new FormData();
		formData.append("function", "ses");
		formData.append("ctrl", "control");

		let xhr = new XMLHttpRequest();
		xhr.open('POST',db_inicial);
		xhr.addEventListener('load',(data)=>{
			var datos = JSON.parse(data.target.response);
			if (datos.sess=="cerrada"){
				location.href ="login/";
			}
		});
		xhr.onerror = (e)=>{
		};
		xhr.send(formData);
	}

	class MenuLink extends HTMLAnchorElement {
		connectedCallback() {
			this.addEventListener('click', (e) => {cargando(true);
				cargando(true);
				if(document.querySelector('.activeside')){
					document.querySelector('.activeside').classList.remove('activeside');
					this.classList.add('activeside');
				}
				else{
					this.classList.add('activeside');
				}
				let formData = new FormData();
				let hash=e.currentTarget.hash.slice(1);
				let arrayDeCadenas = hash.split("?");
				let nhash=arrayDeCadenas[0];
				if(arrayDeCadenas.length>1){
					let query=arrayDeCadenas[1];
					var vars = query.split("&");
					for (var i=0; i < vars.length; i++) {
					var pair = vars[i].split("=");
						formData.append(pair[0],pair[1]);
					}
				}
				let datos = new Object();
				datos.des=nhash+".php";
				datos.dix="contenido";
				redirige_div(formData,datos);
			});
		}
	}
	customElements.define("menu-link", MenuLink, { extends: "a" });

	class ConfirmLink extends HTMLAnchorElement {
	  connectedCallback() {
	    this.addEventListener('click', (e) => {
	      proceso_db(e);
	    });
	  }
	}
	customElements.define("a-link", ConfirmLink, { extends: "a" });

	class CiLink extends HTMLLIElement {
	  connectedCallback() {
	    this.addEventListener('click', (e) => {
				cargando(true);
	      proceso_db(e);
	    });
	  }
	}
	customElements.define("li-link", CiLink, { extends: "li" });

	////////////////////boton normal
	class Buttonlink extends HTMLButtonElement  {
		connectedCallback() {
			this.addEventListener('click', (e) => {
				proceso_db(e);
			});
		}
	}
	customElements.define("b-link", Buttonlink, { extends: "button" });

	////////////////////boton imprimir
	class Buttonprint extends HTMLButtonElement  {
		connectedCallback() {
			this.addEventListener('click', (e) => {
				cargando(true);
				let des;	/////////////el destino
				e.currentTarget.attributes.des!==undefined ? des=e.currentTarget.attributes.des.nodeValue : des="";
				des+=".php";

				let cadena="?";
				for(let contar=0;contar<e.currentTarget.attributes.length; contar++){
					let arrayDeCadenas = e.currentTarget.attributes[contar].name.split("_");
					if(arrayDeCadenas.length>1){
						cadena+=arrayDeCadenas[1]+"="+e.currentTarget.attributes[contar].value+"&";
					}
				}
				VentanaCentrada(des+cadena,'Impresion','','1024','768','true');
				cargando(false);
			});
		}
	}
	customElements.define("b-print", Buttonprint, { extends: "button" });

	//////////////////////////especial submit para control
	class Sublink extends HTMLInputElement   {
		connectedCallback() {
			this.addEventListener('change', (e) => {
				e.preventDefault();
				cargando(true);
				//////////id del formulario
		 		let id=e.target.form.id;
		 		let elemento = document.getElementById(id);

				/////////API que procesa el form
		 		let db;
				(elemento.attributes.db !== undefined) ? db=elemento.attributes.db.nodeValue : db="";

				/////////funcion del api que procesa el form
		 		let fun;
				(elemento.attributes.fun !== undefined) ? fun=elemento.attributes.fun.nodeValue : fun="";

				/////////Div de destino despues de guardar
				let dix;
				(elemento.attributes.dix !== undefined) ? dix=elemento.attributes.dix.nodeValue : dix="trabajo";

				/////////div destino despues de guardar
		 		let des;
				(elemento.attributes.des !== undefined) ? des=elemento.attributes.des.nodeValue : des="";

				let desid;
				(elemento.attributes.desid !== undefined) ? desid=elemento.attributes.desid.nodeValue : desid="";

				////////FORM pertenece a ventanamodal
		 		let cmodal;
				(elemento.attributes.cmodal !== undefined) ? cmodal=elemento.attributes.cmodal.nodeValue : cmodal="";

				let datos = new Object();
				datos.des=des+".php";
				datos.desid=desid;
				datos.db=db+".php";
				datos.dix=dix;
				datos.fun=fun;
				datos.cmodal=cmodal;
				var formDestino = new FormData();

				var formData = new FormData(elemento);
				formData.append("function", datos.fun);

				/////////esto es para todas las variables
				let variables = new Object();
				for(let contar=0;contar<elemento.attributes.length; contar++){
					let arrayDeCadenas = elemento.attributes[contar].name.split("_");
					if(arrayDeCadenas.length>1){
						formData.append(arrayDeCadenas[1], elemento.attributes[contar].value);
						formDestino.append(arrayDeCadenas[1], elemento.attributes[contar].value);
					}
				}

				if(db.length>4){
					cargando(true);
					let xhr = new XMLHttpRequest();
					xhr.open('POST',datos.db);
					xhr.addEventListener('load',(data)=>{
						if (!isJSON(data.target.response)){
							Swal.fire({
								type: 'error',
								title: "Error favor de verificar",
								showConfirmButton: false,
								timer: 1000
							});
							console.log(data.target.response);
							cargando(false);
							return;
						}

						var respon = JSON.parse(data.target.response);
						if (respon.error==0){
							if (datos.desid !== undefined && datos.desid.length>0) {
								document.getElementById(datos.desid).value=respon.id1;
								formDestino.append(datos.desid, respon.id1);
								cargando(false);
							}
							if (datos.des !== undefined && datos.des.length>4) {
								redirige_div(formDestino,datos);
							}
							if(datos.cmodal==1){
								$('#myModal').modal('hide');
								cargando(false);
							}

							Swal.fire({
								type: 'success',
								title: "Se guardó correctamente ",
								showConfirmButton: false,
								timer: 500
							});
						}
						else{
							cargando(false);
							Swal.fire({
								type: 'info',
								title: respon.terror,
								showConfirmButton: false,
								timer: 1000
							});
						}
					});
					xhr.onerror =  ()=>{
						cargando(false);
						console.log("error");
					};
					xhr.send(formData);

				}
			});
		}
	}
	customElements.define("s-submit", Sublink, { extends: "input" });

	////////////////////submit general
	class Formsubmit extends HTMLFormElement {
		connectedCallback() {
		 this.addEventListener('submit', (e) => {
			 	e.preventDefault();

				//////////id del formulario
		 		let id=e.currentTarget.attributes.id.nodeValue;
		 		let elemento = document.getElementById(id);

				/////////API que procesa el form
		 		let db;
				(elemento.attributes.db !== undefined) ? db=elemento.attributes.db.nodeValue : db="";

				/////////funcion del api que procesa el form
		 		let fun;
				(elemento.attributes.fun !== undefined) ? fun=elemento.attributes.fun.nodeValue : fun="";

				/////////Div de destino despues de guardar
				let dix;
				(elemento.attributes.dix !== undefined) ? dix=elemento.attributes.dix.nodeValue : dix="trabajo";

				/////////div destino despues de guardar
		 		let des;
				(elemento.attributes.des !== undefined) ? des=elemento.attributes.des.nodeValue : des="";

				let desid;
				(elemento.attributes.desid !== undefined) ? desid=elemento.attributes.desid.nodeValue : desid="";

				////////FORM pertenece a ventanamodal
		 		let cmodal;
				(elemento.attributes.cmodal !== undefined) ? cmodal=elemento.attributes.cmodal.nodeValue : cmodal="";

				let datos = new Object();
				datos.des=des+".php";
				datos.desid=desid;
				datos.db=db+".php";
				datos.dix=dix;
				datos.fun=fun;
				datos.cmodal=cmodal;
				var formDestino = new FormData();

				var formData = new FormData(elemento);
				formData.append("function", datos.fun);

				/////////esto es para todas las variables
				let variables = new Object();
				for(let contar=0;contar<elemento.attributes.length; contar++){
					let arrayDeCadenas = elemento.attributes[contar].name.split("_");
					if(arrayDeCadenas.length>1){
						formData.append(arrayDeCadenas[1], elemento.attributes[contar].value);
						formDestino.append(arrayDeCadenas[1], elemento.attributes[contar].value);
					}
				}

				if(db.length>4){
					Swal.fire({
						title: '¿Desea procesar los cambios realizados?',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Guardar'
					}).then((result) => {
						if (result.value) {
							cargando(true);
							let xhr = new XMLHttpRequest();
							xhr.open('POST',datos.db);
							xhr.addEventListener('load',(data)=>{


								if (!isJSON(data.target.response)){
									Swal.fire({
										type: 'error',
										title: "Error favor de verificar",
										showConfirmButton: false,
										timer: 1000
									});
									console.log(data.target.response);
									cargando(false);
									return;
								}

								var respon = JSON.parse(data.target.response);
								if (respon.error==0){

									if (datos.desid !== undefined && datos.desid.length>0) {
										document.getElementById(datos.desid).value=respon.id;
										formDestino.append(datos.desid, respon.id);
										cargando(false);
									}
									if (datos.des !== undefined && datos.des.length>4) {
										redirige_div(formDestino,datos);
									}
									else{
										cargando(false);
									}
									if(datos.cmodal==1){
										$('#myModal').modal('hide');
										cargando(false);
									}

									Swal.fire({
										type: 'success',
										title: "Se guardó correctamente ",
										showConfirmButton: false,
										timer: 1000
									});
								}
								else{
									cargando(false);
									Swal.fire({
										type: 'info',
										title: respon.terror,
										showConfirmButton: false,
										timer: 1000
									});
								}
							});
							xhr.onerror =  ()=>{
								cargando(false);
								console.log("error");
							};
							xhr.send(formData);
						}
					});
				}
				else{
					let xhr = new XMLHttpRequest();
					xhr.open('POST',datos.des);
					xhr.addEventListener('load',(data)=>{
						document.getElementById(datos.dix).innerHTML = data.target.response;
						cargando(false);
					});
					xhr.onerror =  ()=>{
						cargando(false);
						console.log("error");
					};
					xhr.send(formData);
				}
		 })
		}
	}
	customElements.define("f-submit", Formsubmit, { extends: "form" });

	////////////////////submit de busqueda
	class Formbuscar extends HTMLFormElement {
		connectedCallback() {
		 this.addEventListener('submit', (e) => {
				e.preventDefault();
				cargando(true);
				//////////id del formulario
				let id=e.currentTarget.attributes.id.nodeValue;
				let elemento = document.getElementById(id);

				/////////funcion del api que procesa el form
				let fun;
				(elemento.attributes.fun !== undefined) ? fun=elemento.attributes.fun.nodeValue : fun="";

				/////////Div de destino despues de guardar
				let dix;
				(elemento.attributes.dix !== undefined) ? dix=elemento.attributes.dix.nodeValue : dix="trabajo";

				/////////div destino despues de guardar
		 		let des;
				(elemento.attributes.des !== undefined) ? des=elemento.attributes.des.nodeValue : des="";

				////////FORM pertenece a ventanamodal
		 		let cmodal;
				(elemento.attributes.cmodal !== undefined) ? cmodal=elemento.attributes.cmodal.nodeValue : cmodal="";

				let datos = new Object();
				datos.des=des+".php";
				datos.dix=dix;
				datos.fun=fun;

				datos.cmodal=cmodal;
				var formDestino = new FormData();

				var formData = new FormData(elemento);
				formData.append("function", datos.fun);

				/////////esto es para todas las variables
				let variables = new Object();
				for(let contar=0;contar<elemento.attributes.length; contar++){
					let arrayDeCadenas = elemento.attributes[contar].name.split("_");
					if(arrayDeCadenas.length>1){
						formData.append(arrayDeCadenas[1], elemento.attributes[contar].value);
						formDestino.append(arrayDeCadenas[1], elemento.attributes[contar].value);
					}
				}
				let xhr = new XMLHttpRequest();
				xhr.open('POST',datos.des);
				xhr.addEventListener('load',(data)=>{
					document.getElementById(datos.dix).innerHTML = data.target.response;
					cargando(false);
				});
				xhr.onerror =  ()=>{
					cargando(false);
					console.log("error");
				};
				xhr.send(formData);

		 })
		}
	}
	customElements.define("b-submit", Formbuscar, { extends: "form" });

	//////////////////////////Solo para un proceso antes del flujo ejem. al borrar que primero borre y luego redirive_div
	function proceso_db(e){
		let des;	/////////////el destino
		e.currentTarget.attributes.des!==undefined ? des=e.currentTarget.attributes.des.nodeValue : des="";

		let dix; 	/////////////	el div donde se pone el destino
		e.currentTarget.attributes.dix!==undefined ? dix=e.currentTarget.attributes.dix.nodeValue : dix="";

		let db;		/////////////en caso de base de datos
		e.currentTarget.attributes.db!==undefined ? db=e.currentTarget.attributes.db.nodeValue : db="";

		let fun;	///////////// la funcion a ejecutar
		e.currentTarget.attributes.fun!==undefined ? fun=e.currentTarget.attributes.fun.nodeValue : fun="";

		let tp;	///////////// la funcion a ejecutar
		e.currentTarget.attributes.tp!==undefined ? tp=e.currentTarget.attributes.tp.nodeValue : tp="";

		let desid;
		e.currentTarget.attributes.desid!==undefined ? desid=e.currentTarget.attributes.desid.nodeValue : desid="";

		let omodal;
		e.currentTarget.attributes.omodal!==undefined ? omodal=e.currentTarget.attributes.omodal.nodeValue : omodal="";

		let cmodal;
		(e.currentTarget.attributes.cmodal !== undefined) ? cmodal=e.currentTarget.attributes.cmodal.nodeValue : cmodal="0";

		let datos = new Object();
		datos.des=des+".php";
		datos.db=db+".php";
		datos.dix=dix;
		datos.fun=fun;
		datos.tp=tp;
		datos.desid=desid;
		datos.omodal=omodal;
		datos.cmodal=cmodal;

		/////////esto es para todas las variables
		var variables = new FormData();
		var formData = new FormData();
		for(let contar=0;contar<e.currentTarget.attributes.length; contar++){
			let arrayDeCadenas = e.currentTarget.attributes[contar].name.split("_");
			if(arrayDeCadenas.length>1){
				formData.append(arrayDeCadenas[1], e.currentTarget.attributes[contar].value);
				variables.append(arrayDeCadenas[1], e.currentTarget.attributes[contar].value);
			}
		}
		if(datos.cmodal==1){
			$('#myModal').modal('hide');
			cargando(false);
			return;
		}
		if(datos.cmodal==2){
			$('#myModal').modal('hide');
		}
		//////////////poner aqui proceso en caso de existir funcion
		if(fun.length>0){
			if(datos.fun.length>0){
				formData.append("function", datos.fun);
			}
			if(datos.tp.length>0){
				Swal.fire({
					title: datos.tp,
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Aceptar'
				}).then((result) => {
					if (result.value) {
						proceso_f(formData,variables,datos);
					}
				});
			}
			else{
				proceso_f(formData,variables,datos);
			}
		}
		else{
			redirige_div(formData,datos);
		}
	}
	function proceso_f(formData, variables, datos){
		let variable=0;
		cargando(true);
		let xhr = new XMLHttpRequest();
		xhr.open('POST',datos.db);
		xhr.addEventListener('load',(data)=>{
			if (!isJSON(data.target.response)){
				Swal.fire({
					type: 'error',
					title: "Error favor de verificar",
					showConfirmButton: false,
					timer: 1000
				});
				console.log(data.target.response);
				cargando(false);
				return;
			}

			var respon = JSON.parse(data.target.response);
			if (datos.desid !== undefined && datos.desid.length>0) {
				variables.set(datos.desid, respon.id);
			}

			if (respon.error==0){
				if (datos.des.length>0){
					redirige_div(variables,datos);
				}
				else{
					cargando(false);
					Swal.fire({
						type: 'success',
						title: "Listo",
						showConfirmButton: false,
						timer: 1000
					});
				}
			}
			else{
				Swal.fire({
					type: 'info',
					title: respon.terror,
					showConfirmButton: false,
					timer: 1000
				});
				cargando(false);
			}
		});
		xhr.onerror = (e)=>{
		};
		xhr.send(formData);
	}
	//////////////////////////redirige si es necesario
	function redirige_div(formData,datos){
		//console.log(datos);
		//for(var pair of formData.entries()) {
   		//console.log(pair[0]+ ', '+ pair[1]);
		//}
		let xhr = new XMLHttpRequest();
		xhr.open('POST', datos.des);
		xhr.addEventListener('load',(datares)=>{
			if(datares.target.status=="404"){
				Swal.fire({
						type: 'error',
						title: "No encontrado: "+datos.des,
						showConfirmButton: false,
				})
				cargando(false);
				return 0;
			}
			else{
				if(datos.omodal==1){
					$('#myModal').modal('show');
					datos.dix="modal_form";
				}
				document.getElementById(datos.dix).innerHTML = datares.target.responseText;
				var scripts = document.getElementById(datos.dix).getElementsByTagName("script");
				for (var i = 0; i < scripts.length; i++) {
			    eval(scripts[i].innerText);
				}
				if (datos.tp !== undefined && datos.tp.length>0) {
					Swal.fire({
						type: 'success',
						title: "Listo",
						showConfirmButton: false,
						timer: 1000
					});
				}
				cargando(false);
			}
		});
		xhr.onerror = (e)=>{
			console.log(e);
		};
		xhr.send(formData);

	}
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
