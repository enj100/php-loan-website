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

//privalomi laukai
$required = array(); 
$validations = array();
$maxLengths = array();
$errors = array();

$formErrors = null;

$data = $klientaiObj->gauti_visus_duomenis();
$interest = $paskolosObj->gauti_palukanas();

if(!empty($_POST['loan'])){
	include 'utils/validator.php';
	$validator = new validator($validations, $required, $maxLengths);
	if($validator->validate($_POST)){
		$dataPrepared = $validator->preparePostFieldsForSQL();
		$success = $paskolosObj->imti_paskola($interest[0]['dydis'], $dataPrepared['money'], $dataPrepared['term'], $data);
		header("Location: index.php?module=mokejimo_grafikas");
		exit;
	}
}

include ('templates/paskolos_langas.html');
?>