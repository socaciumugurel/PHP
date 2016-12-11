<?php

$host =  'localhost';
$dbname =  'database';
$port =  '3306';
$username = 'root';
$password =  '';


try{
	$db = new PDO("mysql:host=$host;dbname=$dbname;port=$port","$username","$password");
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch(Exception $e){
	echo $e->getMessage();
	echo "unable to connect";
	exit;
}


?>