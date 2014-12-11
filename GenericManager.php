<?php
class GenericManager {
	public $host;
	public $user;
	public $pass;
	public $db;
	public $conexao;
	
	public function __construct() {
		$this->host = "localhost";
		$this->user = "totuus_user";
		$this->pass = "Sefaz2012";
		$this->db = "totuus_db";
	}

	private function openConnection() {
		$this->conexao = mysql_connect($this->host, $this->user, $this->pass) or die(mysql_error());
		mysql_select_db($this->db, $this->conexao) or die(mysql_error());
	}
	
	public function executeQuery($query) {
		$this->openConnection();
		$result = mysql_query($query, $this->conexao) or die(mysql_error());
		return $result;
	}
	
}
?>
