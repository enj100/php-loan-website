<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
class klientai {
	private $klientai_lentele = '';
	private $vartotojai_lentele = '';
	private $miestu_lentele = '';

	public function __construct(){
		$this->vartotojai_lentele = 'vartotojai';
	}

	public function gauti_visus_duomenis(){
		$query = "SELECT * FROM {$this->vartotojai_lentele} WHERE slapyvardis='{$_SESSION['username']}'";
		$result = mysql::select($query);
		return $result;
	}

	public function keisti_duomenis($data){
		if(!empty($data['password']) && !empty($data['email'])){
			$hashedPass = substr(hash('sha256', $data['password']), 5, 32);
			$query = "UPDATE {$this->vartotojai_lentele} SET slaptazodis='{$hashedPass}', email='{$data['email']}' WHERE slapyvardis='{$_SESSION['username']}'";
			mysql::query($query);
		}
		else if(!empty($data['password'])){
			$hashedPass = substr(hash('sha256', $data['password']), 5, 32);
			$query = "UPDATE {$this->vartotojai_lentele} SET slaptazodis='{$hashedPass}' WHERE slapyvardis='{$_SESSION['username']}'";
			mysql::query($query);
		}
		else if(!empty($data['email'])){
			$query = "UPDATE {$this->vartotojai_lentele} SET email='{$data['email']}'WHERE slapyvardis='{$_SESSION['username']}'";
			mysql::query($query);
		}
		return 1;
	}

	public function gauti_duomenis(){
		$query = "SELECT email FROM {$this->vartotojai_lentele} WHERE slapyvardis='{$_SESSION['username']}'";
		$result = mysql::select($query);
		return $result[0]['email'];
	}

	public function patikrinti_duomenis($data){
		$query = "SELECT * FROM {$this->vartotojai_lentele} WHERE slapyvardis='{$data['nickname']}'";
		$result = mysql::select($query);
		$error = array();
		//patiktini ar slaptažodžiai sutampa
		if(!empty($result)){
			$error[0] = "Vartotojo vardas jau egzistuoja! Prisijunkite!";
		}
		return $error;
	}

	public function patikrinti_prisijungimo_duomenis($data){
		$query = "SELECT* FROM {$this->vartotojai_lentele} WHERE slapyvardis='{$data['nickname']}'";
		$result = mysql::select($query);
		$error = array();
		if(empty($result)){
			$error[0] = "Vartotojas neegzistuoja. Prašome užsiregistruoti!";
		}
		else if(!empty($result)){
			if($result[0]['slaptazodis'] != $hashedPass = substr(hash('sha256', $data['password']), 5, 32)){
				$error[1] = "Neteisingas slaptažodis! Bandykite dar kartą.";
			}
		}
		return $error;
	}

	public function irasyti($data){
		$hashedPass = substr(hash('sha256', $data['password']), 5, 32);
		$today = date("Y-m-d");
		$queryUsers = "INSERT INTO {$this->vartotojai_lentele}
		(
			slapyvardis,
			slaptazodis,
			lygis,
			timestamp,
			vardas, 
			pavarde, 
			gimimo_data,
			adresas,
			telefonas,
			email,
			fk_palukanu_dydis,
			fk_paskola
		)
		VALUES
		(
			'{$data['nickname']}',
			'{$hashedPass}',
			'1',
			'{$today}',
			'{$data['name']}',
			'{$data['surename']}',
			'{$data['date']}',
			'{$data['address']}',
			'{$data['number']}',
			'{$data['email']}',
			NULL,
			NULL
		)";
		mysql::query($queryUsers);
	}

	public function atnaujinti_lygi_i_indelininko($id){
		$query = "UPDATE {$this->vartotojai_lentele} SET lygis='2' WHERE id='{$id}'";
		$_SESSION['ulevel'] = 2;
		mysql::query($query);
	}

	public function prijungti_vartotoja($data){
		$user=strtolower($data['nickname']); 
		$query = "SELECT * FROM {$this->vartotojai_lentele} WHERE slapyvardis='{$data['nickname']}'";

		$result = array();
		$result = mysql::select($query);

		$_SESSION['username'] = $user;
		$_SESSION['ulevel'] = $result[0]["lygis"];
	}


	
}

?>