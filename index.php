<!DOCTYPE html>
<html lang="es">
<head>

	<title>Certificaciones</title>
	<?php include 'headers.php'; ?>
	
</head>
<body>
<style>
body{
	background: url('imgs/23382-borroso.jpg') no-repeat center center fixed;
	background-size: cover;
}
#divPrincipal{
	min-height: 100vh;
}
#txtDni,#sltCursos{
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
	background-color: #102c58;
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
	top: calc(50% - -2px);
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

#txtDni{font-size: 1.5rem;}
#txtDni::placeholder{
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
} */
.alertify-notifier.ajs-left{
	right: 25%!important;
}
.alertify-notifier .ajs-message.ajs-error {
	background: rgb(222 0 0 / 95%)!important;
	color: white!important;
}
.alertify-notifier .ajs-message.ajs-success{
	background: rgb(0 224 53 / 95%)!important;
	color: #1c5d27!important;
}
</style>

<div id="app">
	<div class="container d-flex justify-content-center align-items-center" id="divPrincipal">
		<div class="col-sm-8 col-md-6 p-4 text-light mt-n5" id="divCentral">
			<div class="text-center"><img src="https://inaprof.com/wp-content/uploads/2020/10/LOGOIN_inap.png" class="img-responsive" alt=""></div>
			<h3 class="display-4 text-center">Certificaciones</h3>
			<p class="text-center p-3">Seleccione su curso y rellene su DNI para mostrarle su certificado:</p>
			<select name="" id="sltCursos" class="form-control my-3" placeholder='Seleccione su Curso'>
				<option v-for="curso in cursos" :value="curso.id">{{curso.titulo}}</option>
			</select>
			<i id="flechita"></i>
			<input type="text" class="form-control my-3 text-center" id="txtDni" placeholder='D.N.I.' onkeypress="return soloNuneros(event);">
	
			<button type="button" class="btn btn-block my-3 text-center py-3" id="btnBuscar" onclick="buscarCertificado()" > <span><i class="bi bi-stickies"></i> Buscar certificado</span></button>
			<div class="d-flex justify-content-between">
				<p><a href="javascript:history.go(-1);" class="text-light"><small><i class="bi bi-chevron-left"></i> Volver atrás</small></a></p>
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
					<input class="form-control my-3" type="text" name="" placeholder='Usuario' v-model="usuario">
					<input class="form-control my-3" type="password" name="" placeholder='Contraseña' v-model="passw">
					<div class='d-flex justify-content-end'>
						<button type='button' class='btn btn-outline-secondary' @click="iniciarSesion" >Iniciar sesión</button>
					</div>
	
				</div>
			</div>
		</div>
	</div>
</div>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.rtl.min.css"/>


<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>


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
	$('#txtDni').keypress(function (e) { 
		if(e.keyCode == 13){ 
			buscarCertificado()
		}
	});
	function buscarCertificado(){
		if( $('#txtDni').val().length <8 ){
			console.log( 'no data' );
			alertify.set('notifier','position', 'top-left');
			var msg = alertify.error('<i class="bi bi-bug"></i> Formato de DNI incorrecto'); 
			msg.delay(10);
		}else if( isNaN(parseInt($('#txtDni').val())) ){
			alertify.set('notifier','position', 'top-left');
			var msg = alertify.error('<i class="bi bi-bug"></i> Dni no puede contener letras'); 
			msg.delay(10);
		}else{
			//console.log( 'ok' );
			alertify.set('notifier','position', 'top-left');
			var msg = alertify.success('<i class="bi bi-cloud-upload"></i> Buscando su DNI'); 
			msg.delay(2);
			axios.post('php/certificadoCoincidente.php', { idCurso: $('#sltCursos').val(), dni: $('#txtDni').val() })
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
		usuario: '', passw:''
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
		}
	},
	mounted(){
		this.cargarCursos()
	}
})
</script>
</body>
</html>