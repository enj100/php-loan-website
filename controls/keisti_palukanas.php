<?php 
include ('lib/paskolos.php');

$paskolosObj = new paskolos();

//privalomi laukai
$required = array(); 
$validations = array();
$maxLengths = array();
$errors = array();


if(isset($action) && $action == 'change'){
	if(!empty($_POST['saugoti_palukanas'])){
		include 'utils/validator.php';
		$validator = new validator($validations, $required, $maxLengths);
		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$palukanos = $validator->preparePostFieldsForSQL();
			//tikriname ar teisingas slaptažodis ir ar nėra jau tokio vartotojo
			$data = $paskolosObj->keisti_palukanas($palukanos['percent']);
		}
	}
}

$data = $paskolosObj->gauti_palukanas();


include ('templates/palukanu_langas.html');
?>