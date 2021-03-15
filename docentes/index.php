<?php if( !isset($_COOKIE['ckAtiende']) ){ header("Location: ../index.php"); die(); } ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Docentes - Certificados</title>
	<?php include "../headers.php"; ?>
</head>
<body>
<style>
.custom-file-input ~ .custom-file-label::after {
	content: "Buscar...";
}
</style>
	<div id="app">
		<div id="contenedorPrincipal">
			<div class="container">
				<h1>Panel de Docentes</h1>
				<p>Listado de docentes en INAPROF:</p>
				<button class="btn btn-outline-primary mb-3" @click="crearDocente()"><i class="bi bi-bookmark-plus"></i> Registrar docente</button>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>Nombre</th>
							<th>Cargo</th>
							<th>Área</th>
							<th>Firma</th>
							<th>@</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(docente, index) in docentes">
							<td>{{index+1}}</td>
							<td>{{docente.nombre}}</td>
							<td>{{docente.cargo}}</td>
							<td>{{docente.area}}</td>
							<td><img :src="docente.firma" style="width:50px;" @click="llamarFoto($event)" ></td>
							<td><button class="btn btn-outline-primary btn-sm" @click="editarDocente(docente.id);"><i class="bi bi-brush"></i></button></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- Modal para: -->
		<div class='modal' id='modalEditarDocente' tabindex='-1'>
			<div class='modal-dialog modal-dialog-centered'>
				<div class='modal-content'>
					<div class='modal-body'>
						<button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden='true'>&times;</span></button>
						<h5 class='modal-title'>Editar Datos de Docente</h5>
						<p>Rellene los campos requeridos:</p>
						<div class="form-group">
							<label >Nombres y Apellidos:</label>
							<input type="text" class="form-control" id="" v-model="docActual.nombre">
						</div>
						<div class="form-group">
							<label >Cargo:</label>
							<input type="text" class="form-control" id="" v-model="docActual.cargo">
						</div>
						<div class="form-group">
							<label >Área</label>
							<input type="text" class="form-control" id="" v-model="docActual.area">
						</div>
						<div class="form-group" v-if="docActual.firma==''">
							<label >Firma</label>
							<div class="input-group mb-3">
								<div class="custom-file">
									<input type="file" class="custom-file-input" ref="archivoASubir" accept="image/*" >
									<label class="custom-file-label" for="cargarExcel">Buscar firma</label>
								</div>
							</div>
						</div>
						<div class='d-flex justify-content-between'>
							<button v-if="!crearDoc" type='button' class='btn btn-outline-danger' data-dismiss="modal" @click="borrarDocente()"><i class="bi bi-trash"></i> Eliminar Docente</button>
							<button v-if="!crearDoc" type='button' class='btn btn-outline-primary' data-dismiss="modal" @click="uploadFile"><i class="bi bi-save"></i> Guardar cambios</button>
							<button v-else type='button' class='btn btn-outline-primary' data-dismiss="modal" @click="registrarDocente"><i class="bi bi-save"></i> Registrar Docente</button>
						</div>

						<div class="form-group" v-if="docActual.firma!=''">
							<img :src="docActual.firma" class="img-fluid" style="max-width: 100%;">
							<button class="btn btn-outline-danger btn-sm" @click="borrarFirma()"><i class="bi bi-folder-x"></i> Borrar firma</button>
						</div>

					</div>
				</div>
			</div>
		</div>

		<!-- Modal para: -->
		<div class='modal' id='modalFirma' tabindex='-1'>
			<div class='modal-dialog modal-dialog-centered'>
				<div class='modal-content'>
					<div class='modal-body'>
						<button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden='true'>&times;</span></button>
						<img src="" class="img-fluid" id="imgFirma">
					</div>
				</div>
			</div>
		</div>

	</div>


<script>
	var app = new Vue({
		el: '#app',
		data: {
			docentes: [],
			docActual: {id:'', nombre: '', cargo:'', area:'', firma:''},
			archivoSeleccionado: null,
			archivo:'', crearDoc:false
		},
		methods:{
			editarDocente(idDoc){
				this.crearDoc=false;
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
				this.limpiarActgual()	
				$('#modalEditarDocente').modal('show');
			},
			limpiarActgual(){
				this.docActual.nombre='';
				this.docActual.cargo='';
				this.docActual.area='';
				this.docActual.firma='';
				this.docActual.id='';
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
				
			},
			borrarDocente(){
				axios.post('php/borrarDocente.php', {idDocente: this.docActual.id })
				.then(function (response) { console.log( response.data );
					if(response.data=='ok'){
						let indice =  app.docentes.map(doce => doce.id).indexOf( app.docActual.id );
						app.docentes.splice(indice, 1);
						app.limpiarActgual()
					}
				})
				.catch(function (error) { console.log(error); });
			}
		},
		mounted(){
			this.solicitarDocentes();
		}
	});
	$('#app').on('change','.custom-file-input',function(){
		
    let fileName = $(this).val().split('\\').pop(); 
   $(this).next('.custom-file-label').addClass("selected").html(fileName);
	})
	
	
</script>

</body>
</html>