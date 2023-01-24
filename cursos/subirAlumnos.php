<?php if( !isset($_COOKIE['ckAtiende']) ){ header("Location: ../index.php"); die(); } ?>
<?php if( !isset($_GET['idCurso']) ) header("location: ../index.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>

	<title>Panel de alumnos en el curso</title>
	<?php include "../headers.php"; ?>
</head>
<body>
<style>
.txtAsistentes{
	width: 90%!important;
}
.custom-file-input ~ .custom-file-label::after {
	content: "Buscar...";
}
#overlay{
  position: fixed; /* Sit on top of the page content */
  display: none; /* Hidden by default */
  width: 100%; /* Full width (cover the whole page) */
  height: 100%; /* Full height (cover the whole page) */
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0,0,0,0.5); /* Black background with opacity */
  z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
}
#overlay>.text{
	position: absolute;
	top: 50%;
	left: 50%;
	font-size: 35px;
	color: white;
	user-select: none;
	transform: translate(-50%,-50%);
	-ms-transform: translate(-50%,-50%);
	margin-top: 30px;
}
.spinner {
       position: absolute;
       left: 50%;
       top: 50%;
       height:60px;
       width:60px;
       margin:0px auto;
       -webkit-animation: rotation .6s infinite linear;
       -moz-animation: rotation .6s infinite linear;
       -o-animation: rotation .6s infinite linear;
       animation: rotation .6s infinite linear;
       border-left:6px solid rgba(0,174,239,.15);
       border-right:6px solid rgba(0,174,239,.15);
       border-bottom:6px solid rgba(0,174,239,.15);
       border-top:6px solid rgba(0,174,239,.8);
       border-radius:100%;
    }
    
    @-webkit-keyframes rotation {
       from {-webkit-transform: rotate(0deg);}
       to {-webkit-transform: rotate(359deg);}
    }
    @-moz-keyframes rotation {
       from {-moz-transform: rotate(0deg);}
       to {-moz-transform: rotate(359deg);}
    }
    @-o-keyframes rotation {
       from {-o-transform: rotate(0deg);}
       to {-o-transform: rotate(359deg);}
    }
    @keyframes rotation {
       from {transform: rotate(0deg);}
       to {transform: rotate(359deg);}
    }
</style>
	<div class="container-fluid px-5">
		<h1>Gestión de alumnos por curso: </h1>
		<p>Para cargar correctamente los alumnos, por favor <a href="assets/alumnos_v1.xlsx">descarga este archivo</a> como plantilla</p>
		<div class="row mb-3">
			<div class="col-md-6">
				<div class="input-group mb-3">
					<div class="custom-file">
						<input type="file" class="custom-file-input" id="cargarExcel" >
						<label class="custom-file-label" for="cargarExcel">Buscar Excel</label>
					</div>
				</div>

			</div>
			<div class="col-md-6 d-none" id="divbotones">
				<button class="btn btn-outline-secondary" id="btnLimpiar"><i class="bi bi-eraser"></i> Limpiar</button>
				<button class="btn btn-outline-success" id="cargarAlumnos"><i class="bi bi-arrow-up-square"></i> Cargar alumnos</button>
				</div>
		</div>

		<div id="importado">
		</div>
		<div id="app">
			<h5 class="lead"><?= $_GET['titulo']?></h5>
			<h5>Alumnos ya agregados:</h5>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>N°</th>
						<th>Código</th>
						<th>Nombres y Apellidos</th>
						<th>DNI</th>
						<th>Rol</th>
						<th>Nota</th>
						<th><i class="bi bi-envelope"></i></th>
						<th>@</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(alumno, index) in alumnos">
						<td>{{index+1}}</td>
						<td>
							<div>
								<small class="" onclick="habilitar(this)" @click="personalizado=alumno.aluCodPersonalizado; idAlumnoSelect=alumno.idAlumno"><i class="bi bi-pencil"></i></small>
								<span>{{alumno.aluCodPersonalizado}}</span>
							</div>
							<div class="form-inline d-none" ><input type="text" class="form-control" v-model="personalizado">
								<span class="text-danger" onclick="desHabilitar(this)" ><i class="bi bi-x"></i></span> 
								<span class="text-success" onclick="desHabilitar(this)" @click="updateCodigoPersonalizado()" ><i class="bi bi-check2"></i></span>
							</div>
						</td>
						<td class="text-capitalize">{{alumno.aluNombre}}</td>
						<td>
							<div>
								<small class="" onclick="habilitar(this)" @click="dni=alumno.aluDNI; idAlumnoSelect=alumno.idAlumno"><i class="bi bi-pencil"></i></small>
								<span>{{alumno.aluDNI}}</span>
							</div>
							<div class="form-inline d-none" ><input type="text" class="form-control txtDnis" v-model="dni">
								<span class="text-danger" onclick="desHabilitar(this)" ><i class="bi bi-x"></i></span> 
								<span class="text-success" onclick="desHabilitar(this)" @click="updateDniPersonalizado()" ><i class="bi bi-check2"></i></span>
							</div>
						</td>
						<td>
							<div>
								<small class="" onclick="habilitar(this)" @click="asistente=alumno.aluAsistente; idAlumnoSelect=alumno.idAlumno"><i class="bi bi-pencil"></i></small>
								<span>{{alumno.aluAsistente}}</span>
							</div>
							<div class="form-inline d-none" ><input type="text" class="form-control txtAsistentes" v-model="asistente">
								<span class="text-danger" onclick="desHabilitar(this)" ><i class="bi bi-x"></i></span> 
								<span class="text-success" onclick="desHabilitar(this)" @click="updateAsistentePersonalizado()" ><i class="bi bi-check2"></i></span>
							</div>
						</td>
						<td>
							<div>
								<small class="" onclick="habilitar(this)" @click="nota=alumno.nota; idAlumnoSelect=alumno.idAlumno"><i class="bi bi-pencil"></i></small>
								<span>{{alumno.nota}}</span>
							</div>
							<div class="form-inline d-none" ><input type="text" class="form-control txtNotas" v-model="nota">
								<span class="text-danger" onclick="desHabilitar(this)" ><i class="bi bi-x"></i></span> 
								<span class="text-success" onclick="desHabilitar(this)" @click="updateNotaPersonalizado()" ><i class="bi bi-check2"></i></span>
							</div>
						</td>
						<td>
							<div><span>{{alumno.correo}}</span></div>
						</td>
						<td>
							<a class="btn btn-outline-primary btn-sm miTooltip" :href="'<?= $rutaServidor."/certificados/index.php?codigo="?>'+ b64EncodeUnicode(alumno.idAlumno)" target="_blank" data-toggle="tooltip" data-placement="top" title="Ver certificado"><i class="bi bi-bookmark-star"></i></a>
							<button class="btn btn-outline-danger btn-sm" @click="borrarAlumno(alumno.idAlumno)" ><i class="bi bi-x"></i></button>
							</td>
					</tr>
				</tbody>
			</table>

			<div id="overlay">
				<div class="spinner"></div>
				<div class="text">
					<br>
					<br>
					Guardando y enviando correos. Espere porfavor.
				</div>
			</div>
		</div>
</div>

<script>
	
	$('#cargarExcel').change(function(e) {
		var reader = new FileReader();

		reader.readAsArrayBuffer(e.target.files[0]);

		reader.onload = function(e){
			var data = new Uint8Array(reader.result);
			var wb = XLSX.read(data, {type: 'array'});

			var htmlstr = XLSX.write(wb, {sheet:'Hoja1', type: 'binary', codepage:65001, bookType: 'html'});

			$('#importado')[0].innerHTML += htmlstr;
			document.querySelector('#importado table').classList.add("table");
			$('#divbotones').removeClass('d-none')
		}
	});
	$('#btnLimpiar').click(function() {
		$('#cargarExcel').val('');
		$('#importado').html('');
	});
	function habilitar(caja){ $(caja).parent().next().toggleClass('d-none'); $(caja).parent().toggleClass('d-none') }
	function desHabilitar(caja){ $(caja).parent().prev().toggleClass('d-none'); $(caja).parent().toggleClass('d-none') }

	function encode_utf8( s ) {return unescape( encodeURIComponent( s ) ); }
	function decode_utf8( s ) { return decodeURIComponent( escape( s ) ); }

	$('#cargarAlumnos').click(function() {
		document.getElementById("overlay").style.display = "block";
		var alumnado =[]
		$.each($('#importado tr'), function (index, alumno) {
			//console.log( $(alumno).children().eq(1).text() );
			if( $(alumno).children().eq(0).text()=='DNI'){ console.log( 'encontre DNI' );}
			else{
				var persCodigo = '';
				if( $(alumno).children().eq(4).text() !=''){ persCodigo = decode_utf8($(alumno).children().eq(4).text()) }
				if( $.trim($(alumno).children().eq(1).text())!='' ){ //Verificar que este lleno
					alumnado.push({
						dni: $(alumno).children().eq(0).text(),
						nombre: decode_utf8($(alumno).children().eq(1).text()),
						nota: $(alumno).children().eq(2).text(),
						correo: decode_utf8($(alumno).children().eq(3).text()),
						codPers: persCodigo
					});
				}
			}
		});
		$.ajax({url: 'php/cargarAlumnado.php', type: 'POST', data: {
			alumnado: alumnado, idCurso: <?= $_GET['idCurso']; ?>, nombreCurso: app.nombreCurso,
			aTipo : "<?= $_GET['tipo']?>"
		}}).done(function(resp) {
			console.log(resp)
			document.getElementById("overlay").style.display = "none";

			if(resp=='ok'){
				location.reload();
			}
		});
	});
	var app = new Vue({
		el: '#app',
		data: {
			alumnos:[],
			personalizado:'', idAlumnoSelect:'', nombreCurso:"<?= $_GET['titulo']?>", nota:'', dni:'',
			asistente:''
		},
		methods:{
			listarTodosAlumnos(){
				axios.post('php/listarAlumnosCurso.php', {idCurso: <?= $_GET['idCurso']; ?>} )
				.then(function(respuesta){ console.log( respuesta.data );
					app.alumnos = respuesta.data;
				})
				.catch(function(error){ console.log( error ); })
				
			},
			updateCodigoPersonalizado(){
				axios.post('php/actualizarCodPersonalizado.php', {id: this.idAlumnoSelect, codPers:this.personalizado } )
				.then(function(respuesta){ console.log( respuesta.data );
					if(respuesta.data=='ok'){
						let indice =  app.alumnos.map(alu => alu.idAlumno).indexOf( app.idAlumnoSelect );
						app.alumnos[indice].aluCodPersonalizado = app.personalizado;
					}
				})
				.catch(function(error){ console.log( error ); })
			},
			updateAsistentePersonalizado(){
				axios.post('php/actualizarAsistPersonalizado.php', {id: this.idAlumnoSelect, asistPers:this.asistente } )
				.then(function(respuesta){ console.log( respuesta.data );
					if(respuesta.data=='ok'){
						let indice =  app.alumnos.map(alu => alu.idAlumno).indexOf( app.idAlumnoSelect );
						app.alumnos[indice].aluAsistente = app.asistente;
					}
				})
				.catch(function(error){ console.log( error ); })
			},
			updateNotaPersonalizado(){
				axios.post('php/actualizarNotaPersonalizado.php', {id: this.idAlumnoSelect, nota:this.nota } )
				.then(function(respuesta){ console.log( respuesta.data );
					if(respuesta.data=='ok'){
						let indice =  app.alumnos.map(alu => alu.idAlumno).indexOf( app.idAlumnoSelect );
						app.alumnos[indice].nota = app.nota;
					}
				})
				.catch(function(error){ console.log( error ); })
			},
			updateDniPersonalizado(){
				axios.post('php/actualizarDNIPersonalizado.php', {id: this.idAlumnoSelect, dni:this.dni } )
				.then(function(respuesta){ console.log( respuesta.data );
					if(respuesta.data=='ok'){
						let indice =  app.alumnos.map(alu => alu.idAlumno).indexOf( app.idAlumnoSelect );
						app.alumnos[indice].aluDNI = app.dni;
					}
				})
				.catch(function(error){ console.log( error ); })
			},
			borrarAlumno(idAlu){
				axios.post('php/borrarAlumnoCertificado.php', {id: idAlu })
				.then(function(respuesta){ console.log( respuesta.data );
					if(respuesta.data=='ok'){
						let indice =  app.alumnos.map(alu => alu.idAlumno).indexOf( idAlu );
						app.alumnos.splice(indice, 1);
					}
				})
				.catch(function(error){ console.log( error ); })
			},
			b64EncodeUnicode(str) {
				return encodeURIComponent(btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
					return String.fromCharCode('0x' + p1);
			})));
			}
		},
		mounted(){
			this.listarTodosAlumnos();
			
			$('.miTooltip').tooltip()
		}
		});
</script>
</body>
</html>