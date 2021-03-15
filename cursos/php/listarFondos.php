<?php 

$thefolder = "../fondos";
$fondos= array();
$i=0;
if ($handler = opendir($thefolder)) {
	while (false !== ($file = readdir($handler))) {
		if($file!='.' && $file!='..'){
			$fondos[]=$file;
		}
	}
	closedir($handler);
}
echo json_encode($fondos);

?>