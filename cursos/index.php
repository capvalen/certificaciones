<?php if( !isset($_COOKIE['ckAtiende']) ){ header("Location: ../index.php"); die(); } ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Panel de cursos</title>
	<?php include "../headers.php"; ?>
</head>
<body>
<style>
  img.activo{ border: 4px solid #f7b520; padding: 2px; transition: all .3s }
  img{ border: none;}
</style>

	
	<div class="container" id="app">
		<h1>Panel de Cursos</h1>
		<p>Listado de cursos dictados en INAPROF:</p>
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
							<td><img :src="curso.fondo" style="width:50px;" @click="llamarFoto($event)" ></td>
							<td>
								<button class="btn btn-outline-primary btn-sm" @click="editarCurso(curso.id);"><i class="bi bi-brush"></i></button>
								<a class="btn btn-outline-secondary btn-sm" :href="'subirAlumnos.php?idCurso='+ curso.id" ><i class="bi bi-diagram-3-fill"></i></a>
							</td>
					</tr>
			</tbody>
		</table>
	

	<!-- Modal para: -->
	<div class='modal fade' id='modalEditarCurso' tabindex='-1'>
		<div class='modal-dialog modal-lg modal-dialog-centered'>
			<div class='modal-content'>
				<div class='modal-body'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden='true'>&times;</span></button>
					<h5 class='modal-title'>Editar Datos de Curso</h5>
					<p>Rellene los campos requeridos:</p>
					<div class="row">
						<div class="col col-md-6">
							<div class="form-group">
								<label >Título del curso:</label>
								<input type="text" class="form-control" id="" v-model="curActual.titulo">
							</div>
							<div class="form-group">
								<label >Subtitulo del curso:</label>
								<input type="text" class="form-control" id="" v-model="curActual.subTitulo">
							</div>
							<div class="form-group">
								<label >Ponente:</label>
								<input type="text" class="form-control" id="" v-model="curActual.ponente">
								<small class="form-text text-muted">Ejm: Carlos Pariona Valencia.</small>
							</div>
							<div class="form-group">
								<label >Fecha intervalo:</label>
								<input type="text" class="form-control" id="" v-model="curActual.fechaIntervalo">
								<small class="form-text text-muted">Ejm: 25 de marzo al 30 noviembre.</small>
							</div>
							<div class="form-group">
								<label >Fecha generación Certificado:</label>
								<input type="date" class="form-control" id="" v-model="curActual.fechaGeneracion">
							</div>
							<div class="form-group">
								<label >Horas:</label>
								<input type="number" class="form-control" id="" v-model="curActual.horas" min=0 step=1 >
							</div>
						
						</div>
						<div class="col col-md-6">
						<div class="form-group">
								<label >Docente N° 1</label>
								<select name="" id="" class="form-control" v-model="curActual.firma1">
									<option value="-1">Seleccione un docente</option>
									<option v-for="docente in docentes" :value="docente.id">{{docente.nombre}}</option>
								</select>
							</div>
							<div class="form-group">
								<label >Docente N° 2</label>
								<select name="" id="" class="form-control" v-model="curActual.firma2">
									<option value="-1">Seleccione un docente</option>
									<option v-for="docente in docentes" :value="docente.id">{{docente.nombre}}</option>
								</select>
							</div>
							<div class="form-group">
								<label >Aprobado:</label>
								<input type="text" class="form-control" id="" v-model="curActual.resolucion">
								<small class="form-text text-muted">Ejm: Resolución Directorial N° 000-2000-DCP/INAPROF.</small>
							</div>
							<div class="form-group">
								<label >Registro:</label>
								<input type="text" class="form-control" id="" v-model="curActual.registro">
								<small class="form-text text-muted">Ejm: Libro de eventos académicos.</small>
							</div>
							<div class="form-group">
								<label >Tomo:</label>
								<input type="text" class="form-control" id="" v-model="curActual.tomo">
								<small class="form-text text-muted">Ejm: XIX.</small>
							</div>
							<div class="form-group">
								<label >Código:</label>
								<input type="text" class="form-control" id="" v-model="curActual.codigo">
								<small class="form-text text-muted">Ejm: 2021/DCP.</small>
							</div>
							<div class="form-group" v-if="curActual.fondo==''">
								<label >Fondo <small>Suba un fondo de medidas sugeridas 1300x919 pixeles. O seleccione uno de la <a class="text-decoration-none" href="#!" data-dismiss="modal" @click="abrirGaleria">galería existente</a></small></label>
								<input type="file" class="form-control"  ref="archivoASubir" accept="image/*" />
							</div>
						</div>
					</div>
					
					
					<div class='d-flex justify-content-between mb-3'>
						<button v-if="!crearDoc" type='button' class='btn btn-outline-danger' data-dismiss="modal" @click="borrarCurso()"><i class="bi bi-trash"></i> Eliminar Curso</button>
						<button v-if="!crearDoc" type='button' class='btn btn-outline-primary' data-dismiss="modal" @click="uploadFile"><i class="bi bi-save"></i> Guardar cambios</button>
						<button v-else type='button' class='btn btn-outline-primary' data-dismiss="modal" @click="registrarCurso()"><i class="bi bi-save"></i> Registrar Curso</button>
					</div>
					

					<div class="form-group" v-if="curActual.fondo!=''" >
						<img :src="curActual.fondo" class="img-fluid" style="max-width: 100%;">
						<button class="btn btn-outline-warning btn-sm mt-3" @click="borrarFondo()"><i class="bi bi-folder-x"></i> Borrar Fondo</button>
					</div>

				</div>
			</div>
		</div>
	</div>

	<!-- Modal para: -->
	<div class='modal fade' id='modalFirma' tabindex='-1'>
		<div class='modal-dialog modal-lg modal-dialog-centered'>
			<div class='modal-content'>
				<div class='modal-body'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden='true'>&times;</span></button>
					<img src="" class="img-fluid" id="imgFirma">
				</div>
			</div>
		</div>
	</div>

	<!-- Modal para: -->
	<div class='modal fade' id='modalGaleria' tabindex='-1'>
		<div class='modal-dialog modal-lg modal-dialog-centered'>
			<div class='modal-content'>
				<div class='modal-body'>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden='true'>&times;</span></button>
					<h5 class='modal-title'>Galería</h5>
					<p>Seleccione un fondo.</p>
					<div class="row">
						<div class="col-4" v-for="fondo in fondos"> <img :src="'fondos/'+fondo" alt="" @click="activarCasilla($event)" class="img-fluid"> </div>
					</div>
					<div class='d-flex justify-content-end'>
						<button type='button' class='btn btn-primary' @click="seleccionarFondo()" data-dismiss="modal">Seleccionar fondo</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	</div> <!-- fin de app -->

	<script>
		var app = new Vue({
		el: '#app',
		data: {
			cursos: [],
			curActual: {id:'', nombre: '', ponenteId:'', ponente:'', fechaIntervalo:'', fechaGeneracion:'', horas:'', firma1:'', firma2:'', resolucion:'', registro:'', tomo:'', código:'', fondo:'', copia:0},
			docentes: [],
			archivoSeleccionado: null,
			archivo:'', crearDoc:false, fondos:null
		},
		methods:{
			editarCurso(idDoc){
				let indice = this.cursos.map(doce => doce.id).indexOf(idDoc);
				this.curActual.id = this.cursos[indice].id;
				this.curActual.titulo = this.cursos[indice].titulo;
				this.curActual.subTitulo = this.cursos[indice].subTitulo;
				this.curActual.ponente = this.cursos[indice].ponente;
				this.curActual.fechaIntervalo = this.cursos[indice].fechaIntervalo;
				this.curActual.fechaGeneracion = this.cursos[indice].fechaGeneracion;
				this.curActual.horas = this.cursos[indice].horas;
				this.curActual.firma1 = this.cursos[indice].firma1;
				this.curActual.firma2 = this.cursos[indice].firma2;
				this.curActual.resolucion = this.cursos[indice].resolucion;
				this.curActual.registro = this.cursos[indice].registro;
				this.curActual.tomo = this.cursos[indice].tomo;
				this.curActual.codigo = this.cursos[indice].codigo;
				this.curActual.fondo = this.cursos[indice].fondo;
				this.curActual.copia = this.cursos[indice].copia;
				$('#modalEditarCurso').modal('show');
			},
			llamarFoto(eve){
				$('#imgFirma').attr('src', eve.srcElement.src);
				$('#modalFirma').modal('show');
			},
			solicitarCursos(){
				axios.post('php/listarTodosCursos.php')
				.then(function(respuesta){ //console.log( respuesta.data );
					app.cursos= respuesta.data;
				})
				.catch(function(error){ console.log( error ); })
				axios.post('../docentes/php/listarTodosDocentes.php')
				.then(function(respuesta){ //console.log( respuesta.data );
					app.docentes= respuesta.data;
				})
				.catch(function(error){ console.log( error ); })
			},
			actualizarCurso(){
				axios.post('php/actualizarCurso.php', {curActual: this.curActual} )
				.then(function(respuesta){ console.log( respuesta.data );
					if(respuesta.data =='ok'){
						let indice =  app.docentes.map(doce => doce.id).indexOf( app.curActual.id );
						this.cursos[indice].id = this.curActual.id;
						this.cursos[indice].titulo = this.curActual.titulo;
						this.cursos[indice].subTitulo = this.curActual.subTitulo;
						this.cursos[indice].ponente = this.curActual.ponente;
						this.cursos[indice].fechaIntervalo = this.curActual.fechaIntervalo;
						this.cursos[indice].fechaGeneracion = this.curActual.fechaGeneracion;
						this.cursos[indice].horas = this.curActual.horas;
						this.cursos[indice].firma1 = this.curActual.firma1;
						this.cursos[indice].firma2 = this.curActual.firma2;
						this.cursos[indice].resolucion = this.curActual.resolucion;
						this.cursos[indice].registro = this.curActual.registro;
						this.cursos[indice].tomo = this.curActual.tomo;
						this.cursos[indice].codigo = this.curActual.codigo;
						this.cursos[indice].fondo = this.curActual.fondo;
						this.cursos[indice].copia = this.curActual.copia;
					}
				})
				.catch(function(error){ console.log( error ); })	
			},
			borrarFondo(){
				axios.post('php/eliminarFondo.php', {fondo: this.curActual.fondo, id: this.curActual.id  })
				.then(resp => {
					//console.log( resp.data );
					let indice =  app.cursos.map(cur => cur.id).indexOf( app.curActual.id );
					this.cursos[indice].fondo='';
					this.curActual.fondo='';
					this.actualizarCurso();
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
							app.curActual.firma=response.data;
							app.actualizarDocente()
						}
					})
					.catch(function (error) { console.log(error); });
				}
			},
			crearCurso(){
				this.crearDoc=true;
				this.limpiarActual();
				$('#modalEditarCurso').modal('show');
			},
			limpiarActual(){
				this.curActual.titulo='';
				this.curActual.subTitulo='';
				this.curActual.ponente='';
				this.curActual.fechaIntervalo='';
				this.curActual.fechaGeneracion='';
				this.curActual.horas='';
				this.curActual.firma1=-1;
				this.curActual.firma2=-1;
				this.curActual.resolucion='';
				this.curActual.registro='';
				this.curActual.tomo='';
				this.curActual.codigo='';
				this.curActual.fondo="";
				this.curActual.copia=0;
			},
			creaCurso(){
				axios.post('php/crearCurso.php', { curActual: this.curActual })
					.then(function (response) { console.log( response.data );
						app.curActual.id = response.data;
						app.llenarValoresActual(response.data);
					})
					.catch(function (error) { console.log(error); });
			},
			registrarCurso(){
				if(this.curActual.copia==1){
					this.creaCurso();
				}else{
					this.archivo = this.$refs.archivoASubir.files[0];
					if( this.$refs.archivoASubir.files.length==0 ){
						this.creaCurso();
					}else{
						let formData = new FormData();
						formData.append('file', this.archivo);
						formData.append('numero', 9);
		
						axios.post('php/copiarFondo.php', formData,{
							headers: { 'Content-Type': 'multipart/form-data' }
						})
						.then(function (response) { console.log( response.data );
							app.curActual.fondo = response.data;

							axios.post('php/crearCurso.php', { curActual: app.curActual })
							.then(function (respuesta) { console.log( respuesta.data );
								app.curActual.id = response.data;
								app.llenarValoresActual(respuesta.data);
							})
							.catch(function (error) { console.log(error); });

						})
						.catch(function (error) { console.log(error); });
					}
				}
				
			},
			borrarCurso(id){
				axios.post('php/borrarCurso.php', {idCurso: this.curActual.id})
				.then(function (response) { console.log( response.data );
					if(response.data=='ok'){
						let indice =  app.cursos.map(cur => cur.id).indexOf( app.curActual.id );
						app.cursos.splice(indice, 1);
						app.limpiarActual()
					}
				})
				.catch(function (error) { console.log(error); });
			},
			llenarValoresActual(id){
				if(id>=-1){ this.curActual.id=id; }
				this.cursos.push({
					id: this.curActual.id,
					titulo: this.curActual.titulo,
					subTitulo: this.curActual.subTitulo,
					ponente: this.curActual.ponente,
					fechaIntervalo: this.curActual.fechaIntervalo,
					fechaGeneracion: this.curActual.fechaGeneracion,
					horas: this.curActual.horas,
					firma1: this.curActual.firma1,
					firma2: this.curActual.firma2,
					resolucion: this.curActual.resolucion,
					registro: this.curActual.registro,
					tomo: this.curActual.tomo,
					codigo: this.curActual.codigo,
					fondo: this.curActual.fondo,
					copia: this.curActual.copia
				})
			},
			abrirGaleria(){
				axios.post('php/listarFondos.php')
				.then(response=>{ //console.log( response.data );
					app.fondos=response.data;
					$('#modalGaleria').modal('show');
				})
				.catch(error=>{
					console.log( error );
				})
			},
			activarCasilla(e){
				document.querySelectorAll('#modalGaleria img').forEach(item => item.classList.remove('activo'))
				e.target.classList.toggle('activo')
			},
			seleccionarFondo(){
				const imag= document.querySelector('img.activo');
				const ruta = imag.getAttribute('src');
				if(this.crearDoc){
					this.curActual.fondo=ruta;
					this.curActual.copia=1
				}else{
					axios.post('php/actualizarFondoCopiado.php', {fondo: ruta, id: this.curActual.id})
					.then(response=>{ //console.log( response.data );
						if(response.data =='ok'){
							let indice =  app.cursos.map(cur => cur.id).indexOf( app.curActual.id );
							app.curActual.fondo=ruta;
							app.cursos[indice].fondo=ruta;
						}
					})
					.catch(error=>{
						console.log( error );
					})
				}
			}
		},
		mounted(){
			this.solicitarCursos();
		}
	});
	$('#app').on('hidden.bs.modal', '#modalGaleria', function () { 
		if(app.crearDoc){
			$('#modalEditarCurso').modal('show');
		}
	});
	</script>
</body>
</html>