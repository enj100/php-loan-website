<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
if(isset($_SESSION['logged'])){
	include ('lib/klientai.php');
	$required = array(); 
	$validations = array();
	$maxLengths = array();
	$errors = array();

	$data = array();
	//neteisingai įvestiems laukams
	$formErrors = null;
	$klientaiObj = new klientai();

	$email = $klientaiObj->gauti_duomenis();


	if(!empty($_POST['edit'])){
		include 'utils/validator.php';
		$validator = new validator($validations, $required, $maxLengths);

		if($validator->validate($_POST)){
			$dataPrepared = $validator->preparePostFieldsForSQL();
			$success = $klientaiObj->keisti_duomenis($dataPrepared);
			header("Location: index.php?module=nustatymai");
			exit;
		}
	}
	include ('templates/nustatymu_langas.html');
}
else{
	include ('templates/pagrindinis_meniu.html');
}
?>