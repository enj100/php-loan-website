<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('lib/klientai.php');
include ('lib/paskolos.php');

$klientaiObj = new klientai();
$paskolosObj = new paskolos();

$data = $klientaiObj->gauti_visus_duomenis();

$result = $paskolosObj->gauti_grafikus($data[0]['id']);

if($action == "pay"){
	$success1 = $paskolosObj->grazinti_dali($id, $data[0]['id']);
	if($success1==1){
		header("Location: index.php?module=mokejimo_grafikas&success=1");
		exit;
	}
	else{
		header("Location: index.php?module=mokejimo_grafikas");
		exit;
	}
}

if($action == "payAll"){
	$paskolosObj->grazinti_viska($data[0]['id']);
	header("Location: index.php?module=mokejimo_grafikas");
	exit;
}
else{
	include ('templates/mokejimo_grafikas.html');
}
?>