<?php 

include '../phpqrcode/qrlib.php';

//QRcode::png('https://www.facebook.com/', "qr/". uniqid(). '.png')
QRcode::png($_GET['web']);
?>