<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<body>
	<?php
		include 'settings.php';
		include 'utils/mysql.php';
		// nustatome pasirinktą modulį
		$module = '';
		if(isset($_GET['module'])) {
			$module = mysql::escape($_GET['module']);
		}
		$id = '';
		if(isset($_GET['id'])) {
			$id = mysql::escape($_GET['id']);
		}
		$action = '';
		if(isset($_GET['action'])) {
			$action = mysql::escape($_GET['action']);
		}
		$success = '';
		if(isset($_GET['success'])) {
			$success = mysql::escape($_GET['success']);
		}
		$filters = '';
		if(isset($_GET['filters'])) {
			$filters = mysql::escape($_GET['filters']);
		}
		$contract = '';
		if(isset($_GET['contract'])) {
			$contract = mysql::escape($_GET['contract']);
		}
		$register = '';
		if(isset($_GET['register'])) {
			$register = mysql::escape($_GET['register']);
		}
		/*
		// jeigu pasirinktas elementas (sutartis, automobilis ir kt.), nustatome elemento id
		$id = '';
		if(isset($_GET['id'])) {
			$id = mysql::escape($_GET['id']);
		}

		// nustatome, kokia funkcija kviečiama
		$action = '';
		if(isset($_GET['action'])) {
			$action = mysql::escape($_GET['action']);
		}

		// nustatome elementų sąrašo puslapio numerį
		*/$pageId = 1;
		if(!empty($_GET['page'])) {
			$pageId = mysql::escape($_GET['page']);
		}

		// nustatome, kurį valdiklį įtraukti šablone
		$actionFile = "";
		if(!empty($module)) {
			include "controls/{$module}.php";
			//include 'templates/{$module}.html';
		}
		else{
			include 'templates/pagrindinis_meniu.html';
		}

?>
</body>
</html>
