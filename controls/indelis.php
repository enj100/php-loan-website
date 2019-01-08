<?php
if(!isset($_SESSION))
{
    session_start();
}
include ('lib/klientai.php');
include ('lib/fondas.php');


$klientaiObj = new klientai();
$fondasObj = new fondas();
//privalomi laukai
$required = array();
$validations = array();
$maxLengths = array();
$errors = array();

$data2 = array();
//neteisingai įvestiems laukams
$formErrors = null;

if($action == "mano"){
	$data = $klientaiObj->gauti_visus_duomenis();
	if(!empty($id)){
		$success = $fondasObj->pasiimti_indeli($id, $data[0]['id']);

	}

	$deposits = $fondasObj->gauti_mano_indelius($data[0]['id']);

	include ('templates/mano_indeliu_langas.html');
	exit;
}
else{
	$data = $klientaiObj->gauti_visus_duomenis();

	if(!empty($_POST['deposit'])){
		include 'utils/validator.php';
		$validator = new validator($validations, $required, $maxLengths);


		if($validator->validate($_POST)){
			$dataPrepared = $validator->preparePostFieldsForSQL();
			$success = $fondasObj->padeti_indeli($dataPrepared['money'], $data[0]['id']);
			$klientaiObj->atnaujinti_lygi_i_indelininko($data[0]['id']);
		}

	}
}


include ('templates/indelio_langas.html');
?>
