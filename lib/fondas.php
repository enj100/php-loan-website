<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
class fondas {
	private $fondai_lentele = '';
	private $bendras_fondas_lentele = '';
	private $klientai_lentele = '';

	public function __construct(){
		$this->fondai_lentele = 'fondai';
		$this->bendras_fondas_lentele = 'bendras_fondas';
		$this->vartotojai_lentele = 'vartotojai';
	}

	public function gauti_fondo_pinigus(){
		$query = "SELECT bendra_fondo_suma FROM {$this->bendras_fondas_lentele} WHERE id='0'";
		$result = mysql::select($query);
		return $result[0]['bendra_fondo_suma'];
	}

	public function padeti_indeli($amount, $userid){
		$query = "INSERT INTO  {$this->fondai_lentele}
		(
			pinigu_suma,
			fk_vartotojas
		)
		VALUES
		(
			'{$amount}',
			'{$userid}'
		)";
		mysql::query($query);

		$query = "SELECT bendra_fondo_suma FROM {$this->bendras_fondas_lentele} WHERE id='0'";
		$result = mysql::select($query);

		$money = $result[0]['bendra_fondo_suma'];
		$money = $money + $amount;

		$query = "UPDATE {$this->bendras_fondas_lentele} SET bendra_fondo_suma='{$money}' WHERE id='0'";
		mysql::query($query);
		
		$success = "Sėkmingai pasidėjote pinigus į banką!";
		return $success;
	}

	public function gauti_mano_indelius($userid){
		$query = "SELECT * FROM {$this->fondai_lentele} WHERE fk_vartotojas='{$userid}'";
		$result = mysql::select($query);

		return $result;
	}

	public function pasiimti_indeli($id, $userid){
		$success = "Sėkmingai atsiemėte pinigus";
		$query = "SELECT bendra_fondo_suma FROM {$this->bendras_fondas_lentele} WHERE id='0'";
		$result = mysql::select($query);

		$query = "SELECT * FROM {$this->fondai_lentele} WHERE id='{$id}'";
		$result2 = mysql::select($query);

		if(($result[0]['bendra_fondo_suma'] - $result2[0]['pinigu_suma'] ) >= 0){
			$query = "DELETE FROM {$this->fondai_lentele} WHERE id='{$id}'";
			mysql::query($query);
			$newAmount = $result[0]['bendra_fondo_suma'] - $result2[0]['pinigu_suma'];
			$query = "UPDATE {$this->bendras_fondas_lentele} SET bendra_fondo_suma='{$newAmount}' WHERE id='0'";
			mysql::query($query);

			$query = "SELECT * FROM {$this->fondai_lentele} WHERE id='{$id}'";
			$check = mysql::select($query);
			if(empty($check)){
				$query = "UPDATE {$this->vartotojai_lentele} SET lygis='1' WHERE id='{$userid}'";
				$_SESSION['ulevel'] = 1;
				mysql::query($query);
			}
		}
		else{
			$success = "Atsiprašome, bet šiuo metu negalime grąžinti jums pinigų.";
		}

		return $success;
	}

}
?>