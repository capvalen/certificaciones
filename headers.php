<?php
$candado="https://";
$rutaServidor =$candado.$_SERVER['SERVER_NAME']."/certificados";
$donde= $_SERVER['PHP_SELF'];
?>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>


<?php if($donde!='/certificados/index.php'): ?>
<!-- As a heading -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4" style="background-color: #071323!important;">
	<div class="container">
		<a class="navbar-brand " href="#"> <img src="https://inteslaeducation.com/wp-content/uploads/2021/08/logo.svg" width="250px"> <span class="mx-3">Sist. Certificados</span></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item ">
					<a class="nav-link" href="<?= $rutaServidor."/cursos"; ?>">Cursos</a>
				</li>
				<li class="nav-item ">
					<a class="nav-link" href="<?= $rutaServidor."/docentes"; ?>">Firmas</a>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item ">
					<a class="nav-link" href="<?= $rutaServidor."/php/desconectar.php"; ?>"><i class="bi bi-x-square"></i> Salir</a>
				</li>
			</ul>
		
		</div>
	</div>
</nav>
<?php endif; ?>