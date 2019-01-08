<?php 
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('lib/klientai.php');
include ('lib/fondas.php');
include ('lib/paskolos.php');


$klientaiObj = new klientai();
$fondasObj = new fondas();
$paskolosObj = new paskolos();

$result = $paskolosObj->gauti_skolininkus();


include 'templates/skolininku_langas.html';
?>