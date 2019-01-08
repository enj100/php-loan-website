<?php 
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
class paskolos {
	private $vartotojai_lentele = '';
	private $palukanu_lentele = '';
	private $paskolu_lentele = '';
	private $bendras_fondas_lente = '';
	private $grafiku_lentele = '';

	public function __construct(){
		$this->vartotojai_lentele = 'vartotojai';
		$this->palukanu_lentele = 'palukanu_dydis';
		$this->paskolu_lentele = 'paskolos';
		$this->bendras_fondas_lentele = 'bendras_fondas';
		$this->grafiku_lentele = 'grafikai';
	}

	public function gauti_skolininkus(){
		$query = "SELECT * FROM {$this->paskolu_lentele}";
		$result = mysql::select($query);
		return $result;
	}

	public function keisti_palukanas($dydis){
		$query = "UPDATE {$this->palukanu_lentele} SET dydis='{$dydis}' WHERE id='0'";
		mysql::query($query);
	}


	public function grazinti_viska($id){
		$query = "SELECT * FROM {$this->grafiku_lentele} WHERE vartotojo_id='{$id}'";
		$result = mysql::select($query);
		$amount = 0;
		foreach ($result as $value) {
			$sql = "DELETE FROM {$this->grafiku_lentele} WHERE id='{$value['id']}'";
			$amount = $amount + $value['suma'];
			mysql::query($sql);
		}

		$sql = "DELETE FROM {$this->paskolu_lentele} WHERE id='{$id}'";
		mysql::query($sql);

		$query = "SELECT bendra_fondo_suma FROM {$this->bendras_fondas_lentele} WHERE id='0'";
		$result = mysql::select($query);

		$newAmount = $result[0]['bendra_fondo_suma'] + $amount;
		$query = "UPDATE {$this->bendras_fondas_lentele} SET bendra_fondo_suma='{$newAmount}' WHERE id='0'";
		mysql::query($query);

		$query = "UPDATE {$this->vartotojai_lentele} SET lygis='1' WHERE id='{$id}'";
		$_SESSION['ulevel'] = 1;
		mysql::query($query);

	}

	public function grazinti_dali($idGraph, $userid){
		$success = 0;
		$query = "SELECT * FROM {$this->grafiku_lentele} WHERE id='{$idGraph}'";
		$result = mysql::select($query);
		$amount = 0;
		$sql = "DELETE FROM {$this->grafiku_lentele} WHERE id='{$idGraph}'";
		$amount = $amount + $result[0]['suma'];
		mysql::query($sql);

		$query = "SELECT bendra_fondo_suma FROM {$this->bendras_fondas_lentele} WHERE id='0'";
		$result = mysql::select($query);

		$newAmount = $result[0]['bendra_fondo_suma'] + $amount;
		$query = "UPDATE {$this->bendras_fondas_lentele} SET bendra_fondo_suma='{$newAmount}' WHERE id='0'";
		mysql::query($query);

		$query = "SELECT * FROM {$this->grafiku_lentele} WHERE fk_paskola='{$userid}'";
		$result = mysql::select($query);

		if(empty($result)){
			$sql = "DELETE FROM {$this->paskolu_lentele} WHERE id='{$userid}'";
			mysql::query($sql);

			$query = "UPDATE {$this->vartotojai_lentele} SET lygis='1' WHERE id='{$userid}'";
			$_SESSION['ulevel'] = 1;
			mysql::query($query);
			$success = 1;
		}
		return $success;
	}


	public function gauti_grafikus($id){
		$query = "SELECT * FROM {$this->grafiku_lentele} WHERE vartotojo_id='{$id}'";
		$result = mysql::select($query);
		return $result;
	}

	public function gauti_palukanas(){
		$query = "SELECT * FROM {$this->palukanu_lentele} WHERE id='0'";
		$result = mysql::select($query);
		return $result;
	}

	public function imti_paskola($interest, $amount, $term, $userData){
		$success = "Sėkmingai atsiemėte pinigus";
		$query = "SELECT bendra_fondo_suma FROM {$this->bendras_fondas_lentele} WHERE id='0'";
		$result = mysql::select($query);
		$today = date("Y-m-d");
		if(($result[0]['bendra_fondo_suma'] - $amount) >= 0){
			$query = "INSERT INTO {$this->paskolu_lentele}
			(
				id,
				vardas,
				pavarde, 
				data,
				palukanos,
				suma,
				grazinimo_terminas,
				fk_palukanu_dydis
			)
			VALUES
			(
				'{$userData[0]['id']}',
				'{$userData[0]['vardas']}',
				'{$userData[0]['pavarde']}',
				'{$today}',
				'{$interest}',
				'{$amount}',
				'{$term}',
				'0'

			)";
			mysql::query($query);

			$newAmount = $result[0]['bendra_fondo_suma'] - $amount;
			$query = "UPDATE {$this->bendras_fondas_lentele} SET bendra_fondo_suma='{$newAmount}' WHERE id='0'";
			mysql::query($query);

			$query = "UPDATE {$this->vartotojai_lentele} SET lygis='3' WHERE id='{$userData[0]['id']}'";
			$_SESSION['ulevel'] = 3;
			mysql::query($query);

			//sudarome mokėjimų grafiką
			$oneMonth = $amount / $term;
			$moneyToPay = array();

			for ($i=0; $i < $term; $i++) { 
				$moneyToPay[$i] = $oneMonth + ($oneMonth * ($interest/100));
			}
			for ($i=1; $i <= $term; $i++) { 
				$d = strtotime('+'.$i.' month', strtotime($today));
				$d = date('Y-m-d', $d);
				$pay = $moneyToPay[$i-1];
				$pay = number_format($pay, 2, '.', ',');
				$query = "INSERT INTO {$this->grafiku_lentele} (vartotojo_id, data, suma, fk_paskola) VALUES ('{$userData[0]['id']}', '$d', '$pay', '{$userData[0]['id']}')";
				mysql::query($query);
			}

		}
		else
		{
			$success = "Fonde neužtenka pinigų! MAX: " . $result[0]['bendra_fondo_suma'];
		}

		return $success;

	}

}


?>