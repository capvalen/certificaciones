<?php

unset($_COOKIE['ckAtiende']);
unset($_COOKIE['ckPower']);
unset($_COOKIE['ckidUsuario']);

setcookie('ckAtiende', "", time() - 3600, '/');
setcookie('ckPower', "", time() - 3600, '/');
setcookie('ckidUsuario', "", time() - 3600, '/');

header("location: ../");
?>