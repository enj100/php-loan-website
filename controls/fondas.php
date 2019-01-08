<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
if(isset($_SESSION['logged'])){
	include ('lib/fondas.php');

	$fondasObj = new fondas();

	$money = $fondasObj->gauti_fondo_pinigus();


	include ('templates/fondo_informacija.html');
}

else{
	include ('templates/pagrindinis_meniu.html');
}
?>