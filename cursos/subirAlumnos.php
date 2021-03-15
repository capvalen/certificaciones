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
</style>
	<div class="container">
		<h1>Gestión de alumnos por curso: </h1>
		<p>Para cargar correctamente los alumnos, por favor <a href="assets/alumnos.xlsx">descarga este archivo</a> como plantilla</p>
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
		<h5>Alumnos ya agregados:</h5>
		<div id="app">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>N°</th>
						<th>Código</th>
						<th>Nombres y Apellidos</th>
						<th>DNI</th>
						<th>Rol</th>
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
						<td>{{alumno.aluNombre}}</td>
						<td>{{alumno.aluDNI}}</td>
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
							<a class="btn btn-outline-primary btn-sm miTooltip" :href="'<?= $rutaServidor."/certificados/index.php?codigo="?>'+ b64EncodeUnicode(alumno.idAlumno)" target="_blank" data-toggle="tooltip" data-placement="top" title="Ver certificado"><i class="bi bi-bookmark-star"></i></a>
							<button class="btn btn-outline-danger btn-sm" @click="borrarAlumno(alumno.idAlumno)" ><i class="bi bi-x"></i></button>
							</td>
					</tr>
				</tbody>
			</table>
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
		var alumnado =[]
		$.each($('#importado tr'), function (index, alumno) {
			//console.log( $(alumno).children().eq(1).text() );
			if( $(alumno).children().eq(0).text()=='DNI'){ console.log( 'encontre DNI' );}
			else{
				alumnado.push({dni: $(alumno).children().eq(0).text(), nombre: decode_utf8($(alumno).children().eq(1).text()) });
			}
		});
		$.ajax({url: 'php/cargarAlumnado.php', type: 'POST', data: { alumnado: alumnado, idCurso: <?= $_GET['idCurso']; ?> }}).done(function(resp) {
			console.log(resp)
			if(resp=='ok'){
				location.reload();
			}
		});
	});
	var app = new Vue({
		el: '#app',
		data: {
			alumnos:[],
			personalizado:'', idAlumnoSelect:'',
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