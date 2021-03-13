<!DOCTYPE html>
<html lang="es">
<head>
	<title>Document</title>
	<?php include "../headers.php"; ?>
</head>
<body>
	
	<div class="container" id="app">
		<h1>Panel de Cursos</h1>
		<p>Listado de docentes en INAPROF:</p>
		<button class="btn btn-outline-primary mb-3" @click="crearCurso()"><i class="bi bi-bookmark-plus"></i> Registrar curso</button>
		<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Titulo</th>
						<th>SubTitulo</th>
						<th>Fecha</th>
						<th>Ponente</th>
						<th>Fondo</th>
						<th>@</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(curso, index) in cursos">
						<td>{{index+1}}</td>
						<td>{{curso.titulo}}</td>
						<td>{{curso.subTitulo}}</td>
						<td class="text-capitalize">{{curso.fechaGeneracion}}</td>
						<td>{{curso.ponente}}</td>
						<td><img :src="curso.firma" style="width:50px;" @click="llamarFoto($event)" ></td>
						<td><button class="btn btn-outline-primary btn-sm" @click="editarDocente(docente.id);"><i class="bi bi-arrow-up-square"></i></button></td>
					</tr>
				</tbody>
			</table>
	</div>

	<!-- Modal para: -->
	<div class='modal' id='modalEditarCurso' tabindex='-1'>
		<div class='modal-dialog modal-dialog-centered'>
			<div class='modal-content'>
				<div class='modal-body'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden='true'>&times;</span></button>
					<h5 class='modal-title'>Editar Datos de Curso</h5>
					<p>Rellene los campos requeridos:</p>
					<div class="form-group">
						<label >Título del curso:</label>
						<input type="text" class="form-control" id="" v-model="docActual.nombre">
					</div>
					<div class="form-group">
						<label >Subtitulo del curso:</label>
						<input type="text" class="form-control" id="" v-model="docActual.nombre">
					</div>
					<div class="form-group">
						<label >Ponente:</label>
						<input type="text" class="form-control" id="" v-model="docActual.cargo">
					</div>
					<div class="form-group">
						<label >Fecha intervalo:</label>
						<input type="text" class="form-control" id="" v-model="docActual.cargo">
					</div>
					<div class="form-group">
						<label >Fecha generación Certificado:</label>
						<input type="text" class="form-control" id="" v-model="docActual.cargo">
					</div>
					<div class="form-group">
						<label >Horas:</label>
						<input type="text" class="form-control" id="" v-model="docActual.cargo">
					</div>
					<div class="form-group">
						<label >Firma 1</label>
						<input type="text" class="form-control" id="" v-model="docActual.area">
					</div>
					<div class="form-group" v-if="docActual.firma==''">
						<label >Firma 2</label>
						<input type="file" class="form-control"  ref="archivoASubir" accept="image/*" />
					</div>
					<div class="form-group">
						<label >Aprobado Resolución:</label>
						<input type="text" class="form-control" id="" v-model="docActual.cargo">
					</div>
					<div class="form-group">
						<label >Registro:</label>
						<input type="text" class="form-control" id="" v-model="docActual.cargo">
					</div>
					<div class="form-group">
						<label >Tomo:</label>
						<input type="text" class="form-control" id="" v-model="docActual.cargo">
					</div>
					<div class="form-group">
						<label >Código:</label>
						<input type="text" class="form-control" id="" v-model="docActual.cargo">
					</div>
					<div class="form-group" v-if="docActual.firma==''">
							<label >Fondo</label>
							<input type="file" class="form-control"  ref="archivoASubir" accept="image/*" />
						</div>
					

					<div class="form-group" v-if="docActual.firma!=''">
						<img :src="docActual.firma" class="img-fluid" style="max-width: 100%;">
						<button class="btn btn-outline-danger btn-sm" @click="borrarFondo()"><i class="bi bi-folder-x"></i> Borrar Fondo</button>
					</div>

				</div>
			</div>
		</div>
	</div>

	<script>
		var app = new Vue({
		el: '#app',
		data: {
			cursos: [
				{id:'1', titulo: 'Derecho Civil', subTitulo:'Propiedad, tercería de propiedad', ponente:'Dr. Julio Cesar Escobar', fechaIntervalo:'08 de noviembre al 29 de noviembre', fechaGeneracion:'noviembre de 2020', horas:'90', firma1:'1', firma2:'2', resolucion:'Resolución Directorial N° 071-2020-DCP/INAPROF', tomo:'III', código:'324-2020/DCP', fondo:''}
			],
			curActual: {id:'', nombre: '', ponenteId:'', ponente:'', fechaIntervalo:'', fechaGeneracion:'', horas:'', firma1:'', firma2:'', resolucion:'', tomo:'', código:'', fondo:''},
			archivoSeleccionado: null,
			archivo:'', crearDoc:false
		},
		methods:{
			editarDocente(idDoc){
				let indice = this.docentes.map(doce => doce.id).indexOf(idDoc);
				this.docActual.id=this.docentes[indice].id;
				this.docActual.nombre=this.docentes[indice].nombre;
				this.docActual.cargo=this.docentes[indice].cargo;
				this.docActual.area=this.docentes[indice].area;
				this.docActual.firma=this.docentes[indice].firma;
				$('#modalEditarDocente').modal('show');
			},
			llamarFoto(eve){
				$('#imgFirma').attr('src', eve.srcElement.src);
				$('#modalFirma').modal('show');
			},
			solicitarDocentes(){
				axios.post('php/listarTodosDocentes.php')
				.then(function(respuesta){ //console.log( respuesta.data );
					app.docentes= respuesta.data;
				})
				.catch(function(error){ console.log( error ); })
			},
			actualizarDocente(){
				axios.post('php/actualizarDocente.php', {docActual: this.docActual} )
				.then(function(respuesta){ console.log( respuesta.data );
					if(respuesta.data =='ok'){
						let indice =  app.docentes.map(doce => doce.id).indexOf( app.docActual.id );
						app.docentes[indice].nombre=app.docActual.nombre;
						app.docentes[indice].cargo=app.docActual.cargo;
						app.docentes[indice].area=app.docActual.area;
						app.docentes[indice].firma=app.docActual.firma;
					}
				})
				.catch(function(error){ console.log( error ); })
				
			},
			borrarFirma(){
				axios.post('php/eliminarFirma.php', {firma: this.docActual.firma })
				.then(resp => {
					//console.log( resp.data );
					this.docActual.firma='';
					this.actualizarDocente();
				})
				.catch( err => { console.log( err ); alert(err); })
			},
			
			subirArchivo(){
				const fd = new FormData();
				fd.append('image', this.archivoSeleccionado, this.archivoSeleccionado, name)
				axios.post('php/copiarFirma.php', fd)
				.then(res => { console.log( res );})
				.catch(err => {console.log( err );})
			},
			uploadFile(){
				this.archivo = this.$refs.archivoASubir.files[0];
				
				if( this.$refs.archivoASubir.files.length==0 ){
					this.actualizarDocente()
				}else{
					let formData = new FormData();
					formData.append('file', this.archivo);
					formData.append('numero', 9);
	
					axios.post('php/copiarFirma.php', formData,{
						headers: { 'Content-Type': 'multipart/form-data' }
					})
					.then(function (response) {
						if(response.data==-1){
							alert('Archivo no subido, intentelo nuevamente.');
						}else{
							//alert('File uploaded successfully.');
							app.docActual.firma=response.data;
							app.actualizarDocente()
						}
					})
					.catch(function (error) { console.log(error); });
				}
			},
			crearDocente(){
				this.crearDoc=true;
				this.docActual.nombre='';
				this.docActual.cargo='';
				this.docActual.area='';
				this.docActual.firma='';
				$('#modalEditarDocente').modal('show');
			},
			registrarDocente(){
				this.archivo = this.$refs.archivoASubir.files[0];
				if( this.$refs.archivoASubir.files.length==0 ){
					axios.post('php/crearDocente.php', { docActual: this.docActual })
					.then(function (response) { console.log( response.data ); })
					.catch(function (error) { console.log(error); });
				}else{
					let formData = new FormData();
					formData.append('file', this.archivo);
					formData.append('numero', 9);
	
					axios.post('php/copiarFirma.php', formData,{
						headers: { 'Content-Type': 'multipart/form-data' }
					})
					.then(function (response) { console.log( response.data );
						app.docActual.firma = response.data;

						axios.post('php/crearDocente.php', { docActual: app.docActual })
						.then(function (response) { console.log( response.data );
							app.docentes.push({id: response.data, nombre: app.docActual.nombre, cargo:app.docActual.cargo, area:app.docActual.area, firma:app.docActual.firma})
						})
						.catch(function (error) { console.log(error); });

					})
					.catch(function (error) { console.log(error); });
				}
				
			}
		},
		mounted(){
			this.solicitarDocentes();
		}
	});
	</script>
</body>
</html>