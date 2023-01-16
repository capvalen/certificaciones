<?php 

include '../conectkarl.php';
$_POST = json_decode(file_get_contents('php://input'),true);

$clavePrivada= 'Es sencillo hacer que las cosas sean complicadas, pero difícil hacer que sean sencillas. Friedrich Nietzsche';
$local='/';
$sql="SELECT * FROM `usuarios`
where usuario = '{$_POST['usuario']}' and clave = md5('{$_POST['passw']}') and activo = 1;";
$log = mysqli_query($cadena, $sql);
//echo "select * from  usuario u  where usuNick = '".$_POST['user']."' and usuPass='".md5($_POST['pws'])."';";

$row = mysqli_fetch_array($log, MYSQLI_ASSOC);
$expira=time()+60*60*3; //cookie para 3 horas
if ( mysqli_num_rows($log)==1 ){
			
		setcookie('ckAtiende', 'Acceso', $expira, $local);
		setcookie('ckPower', 1, $expira, $local);
		setcookie('ckidUsuario', 1, $expira, $local);
	
		echo 'ok';
	
}else{
	echo 'nada';
}




/* liberar la serie de resultados */
mysqli_free_result($log);
/* cerrar la conexión */
mysqli_close($cadena);
?>