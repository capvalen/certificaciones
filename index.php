<!DOCTYPE html>
<html lang="es">
<head>

	<title>Certificados</title>
	<?php include 'headers.php'; ?>
	<link rel="stylesheet" href="css/alertify.min.css"/>	
</head>
<body>
<style>
	body{
		/* background: url('imgs/23382-borroso.jpg') no-repeat center center fixed;
		background-size: cover; */
		background: rgb(3,31,66);
		background: linear-gradient(192deg, rgba(3,31,66,1) 0%, rgba(8,16,26,1) 100%);
	}
	#divPrincipal{
		min-height: 100vh;
	}
	#txtBuscaCertificado,#sltCursos{
		background: transparent;
		color: white;
		border-radius: 3rem;
		height: calc(1.5em + .75rem + 2px);
	}
	#sltCursos{height: 3.3rem;
		font-size: 1.1em;  transition: all 0.25s ease;}
	#btnBuscar{
		border-radius: 3rem;	
		font-size: 1.1em;
		background-color: #0B2D56;
		border:0;
		color:white;
	}
	#divCentral select{
		appearance: none;
		-webkit-appearance: none;
		-moz-appearance: none;
	}
	#divCentral #flechita{
		position: absolute;
		right: 50px;
		top: calc(50% +25px);
		width: 16px;
		height: 16px;
		display: block;
		border-left: 3px solid #ffffff82;
		border-bottom: 3px solid #ffffff82;
		transform: rotate(-45deg); /* Giramos el cuadrado */
		transition: all 0.25s ease;
	}
	#sltCursos::-ms-expand {
		display: none;
	}

	#txtBuscaCertificado{font-size: 1.5rem;}
	#txtBuscaCertificado::placeholder{
		color:rgba(255, 255, 255, 0.693);
	}
	.form-control:focus{    box-shadow: 0 0 0 0.2rem rgb(235 240 245 / 25%);}
	option{
		background: #1e3b44;
	}
	.modal-content{
		background-color: #0f2034; color: white;
	}
	.close{
		color: red;
			opacity: 1;
			text-shadow: none;
	}
	/* .alertify-notifier.ajs-center{
		right: 30%!important;
	}
	.alertify-notifier.ajs-left{
		right: 25%!important;
	}
	*/
	.alertify-notifier .ajs-message.ajs-error {
		background: rgb(222 0 0 / 95%)!important;
		color: white!important;
	}
	.alertify-notifier .ajs-message.ajs-success{
		background: rgb(0 224 53 / 95%)!important;
		color: #1c5d27!important;
	}
	tr{cursor:pointer}
</style>

<div class="container" id="app">
	<div class="d-flex justify-content-center align-items-center" id="divPrincipal">
		<div class="col-sm-8 col-md-6 p-4 text-light mt-n5" id="divCentral">
			<div class="text-center">
				<!-- <img src="https://inaprof.com/wp-content/uploads/2020/10/LOGOIN_inap.png" class="img-responsive" alt=""> -->
				<a href="https://inteslaeducation.com/"><img src="https://inteslaeducation.com/wp-content/uploads/2021/08/logo.svg" class="img-fluid" alt=""></a>
			</div>
			<h3 class="display-4 text-center">Certificados</h3>
			<p class="text-center p-3">Rellene su DNI/Código para mostrar su certificado:</p>
			<select name="" id="sltCursos" class=" d-none form-control my-3" placeholder='Seleccione su Curso'>
				<option v-for="curso in cursos" :value="curso.id">{{curso.titulo}}</option>
			</select>
			<!-- <i id="flechita"></i> -->
			<input type="text" class="form-control my-3 text-center" id="txtBuscaCertificado" @keypress.enter="buscarCertificado()" v-model="texto" placeholder='' autocomplete="off"> <!-- onkeypress="return soloNuneros(event);" -->
	
			<button type="button" class="btn btn-block my-3 text-center py-3" id="btnBuscar" @click="buscarCertificado()" > <span><i class="bi bi-stickies"></i> Buscar certificado</span></button>
			<div class="d-flex justify-content-between">
				<p><a href="https://inteslaeducation.com/" class="text-light"><small><i class="bi bi-chevron-left"></i> Volver atrás</small></a></p> <!-- javascript:history.go(-1); -->
				<p><a href="#!" onclick="llamarSesiones();" class="text-light"><small><i class="bi bi-cloud"></i> Iniciar Sesión</small></a></p>
			</div>
		</div>
	</div>
	
	<!-- Modal para: -->
	<div class='modal' id='modalSesion' tabindex='-1'>
		<div class='modal-dialog modal-dialog-centered'>
			<div class='modal-content'>
				<div class='modal-body'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden='true'>&times;</span></button>
					<h5 class='modal-title'>Iniciar Sesión</h5>
					<p>Ingrese sus credenciales para administrar los certificados</p>
					<input class="form-control my-3" type="text" name="" placeholder='Usuario' v-model="usuario" @keypress="inciarSesion()">
					<input class="form-control my-3" type="password" name="" placeholder='Contraseña' v-model="passw" @keypress="inciarSesion()">
					<div class='d-flex justify-content-end'>
						<button type='button' class='btn btn-outline-secondary' @click="iniciarSesion()" >Iniciar sesión</button>
					</div>
	
				</div>
			</div>
		</div>
	</div>
	<!-- Modal para mostrar resultados -->
	<div class="modal fade" id="modalResultados" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true" >
		<div class="modal-dialog modal-dialog-centered modal-lg" >
			<div class="modal-content" style="background-color: white; color:#0B2D56">
				<div class="modal-body px-5 ">
					<a class="text-center" href="https://inteslaeducation.com/"><img src="https://inteslaeducation.com/certificados/imgs/LOGO-INTESLA-2023.png" class="img-fluid w-75"></a>
					
					<div v-if="resultados.length==0">
						<p>No existen coincidencias con el código o DNI: <strong>{{texto}}</strong></p>
						<button type="button" class="btn btn-outline-secondary mx-auto mt-2" data-dismiss="modal">Aceptar</button>
					</div>
					<div v-if="resultados.length==1">
						<strong>Intesla Education certifica que:</strong>
						<p class="mb-0 lead">{{resultados[0].aluNombre}}</p>
						<p class="mb-0">{{resultados[0].curTitulo}}</p>
						<p class="mb-0">{{resultados[0].aluAsistente}}</p>
						<p v-if="resultados[0].aTipo=='1'" class="mb-0">Nota final: {{resultados[0].nota}}</p>
						<p class="mb-0">Realizado el: {{resultados[0].curFechaGeneracion}}</p>
						<div class="d-flex justify-content-between">
							<button type="button" class="btn btn-outline-secondary mx-auto mt-2" data-dismiss="modal">Aceptar</button>
							<a type="button" class="btn btn-outline-primary mx-auto mt-2" :href="'https://inteslaeducation.com/certificados/certificados/index.php?codigo='+resultados[0].codEnc" target="_blank" ><i class="bi bi-file-pdf"></i> Ver PDF</a>
						</div>
					</div>
					<div v-if="resultados.length>1">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>N°</th>
									<th>Nombre</th>
									<th>Curso</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(resultado, index) in resultados" @click="resultados=[]; resultados[0] = resultado">
									<td>{{index+1}}</td>
									<td>{{resultado.aluNombre}}</td>
									<td>{{resultado.curTitulo}}</td>
									<td><button class="btn btn-outline-secondary btn-sm border-0"><i class="bi bi-box-arrow-in-right"></i></button></td>
									
								</tr>
							</tbody>
						</table>
						<button type="button" class="btn btn-outline-secondary mx-auto mt-2" data-dismiss="modal">Aceptar</button>
					</div>
					

				</div>
			
			</div>
		</div>
	</div>

</div>

<script src="js/alertify.min.js"></script>


<script>
	function llamarSesiones(){
		$('#modalSesion').modal('show');
	}
	function soloNuneros(evt){
    // code is the decimal ASCII representation of the pressed key.
    var code = (evt.which) ? evt.which : evt.keyCode;
    
    if(code==8) { // backspace.
      return true;
    } else if(code>=48 && code<=57) { // is a number.
      return true;
    } else{ // other keys.
      return false;
    }
	}
	/* $('#txtBuscaCertificado').keypress(function (e) { 
		if(e.keyCode == 13){ 
			buscarCertificado()
		}
	}); */
	function buscarCertificado(){
		if( $('#txtBuscaCertificado').val().length <8 ){
			console.log( 'no data' );
			alertify.set('notifier','position', 'top-left');
			var msg = alertify.error('<i class="bi bi-bug"></i> Formato de DNI incorrecto'); 
			msg.delay(10);
		}else if( isNaN(parseInt($('#txtBuscaCertificado').val())) ){
			alertify.set('notifier','position', 'top-left');
			var msg = alertify.error('<i class="bi bi-bug"></i> Dni no puede contener letras'); 
			msg.delay(10);
		}else{
			//console.log( 'ok' );
			alertify.set('notifier','position', 'top-left');
			var msg = alertify.success('<i class="bi bi-cloud-upload"></i> Buscando su DNI'); 
			msg.delay(2);
			axios.post('php/certificadoCoincidente.php', { idCurso: $('#sltCursos').val(), dni: $('#txtBuscaCertificado').val() })
			.then(respuesta => { console.log( respuesta.data );
				if(respuesta.data=='No existe certificado'){
					alertify.error('<i class="bi bi-broadcast"></i> No existe estudiante en el certificado solicitado').delay(15)
				}else if(respuesta.data=='Existe duplicados'){
					alertify.error('<i class="bi bi-broadcast"></i> Existe duplicados en su certificado, contáctese con administración').delay(15)
				}else{
					window.location.href = '<?= $candado;?>'+respuesta.data;
				}
			})
			.catch(error => { console.log( error ); })
		}
	}
	var app = new Vue({
  el: '#app',
  data: {
    cursos:null,
		usuario: '', passw:'', texto:'', resultados: []
  },
	methods:{
		cargarCursos(){
			axios.post('cursos/php/listarTodosCursos.php')
			.then(response=>{
				console.log( response.data );
				app.cursos = response.data;
			})
			.catch(error=>{
				console.log( error );
			});
		},
		iniciarSesion(){
			axios.post('php/validarSesion.php', {usuario: this.usuario , passw: this.passw })
			.then(response=>{ console.log( response.data );
				if(response.data=='nada'){
					alertify.set('notifier','position', 'top-left');
					alertify.error('<i class="bi bi-broadcast"></i> Usuario y/o contraseña incorrecta').delay(15)
				}
				if(response.data=='ok'){
					window.location.href = 'docentes';
				}
			})
			.catch(error=>{
				console.log( error );
			});
		},
		buscarCertificado(){
			alertify.set('notifier','position', 'top-right');

			if($.trim(this.texto)==''){
				var msg = alertify.error('<i class="bi bi-bug"></i> Debe rellenar un código o un DNI'); 
				msg.delay(10);
			}else{
				var msg = alertify.success('<i class="bi bi-cloud-upload"></i> Buscando su DNI'); 
				msg.delay(2);
				axios.post('php/certificadoCoincidente.php', { texto: $.trim(this.texto) })
				.then(respuesta => { console.log( respuesta.data );
					app.resultados = respuesta.data
					$('#modalResultados').modal('show');					
				})
				.catch(error => { console.log( error ); })
			}
		}
	},
	mounted(){
		//this.cargarCursos()
	}
})
</script>
</body>
</html>