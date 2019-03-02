<?php 

$headers=getallheaders();
$httpAuthorization = $headers ['Authorization'];


echo "<pre>";

print_r($_SERVER);