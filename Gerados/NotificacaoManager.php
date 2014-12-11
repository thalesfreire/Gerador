<?php 

require_once ("GenericManager.php");

class Notificacao {  
 
 	public $seqNotificacao;
	public $seqUsuario;
	public $staNotificacao;
	public $dscTituloNotificacao;
	public $dscNotificacao;
	public $datNotificacao;

}

class NotificacaoManager extends GenericManager {

	public function save($notificacao) {

		$query = "INSERT INTO notificacao (seq_usuario, sta_notificacao, dsc_titulo_notificacao, dsc_notificacao, dat_notificacao) VALUES (".$notificacao->seqUsuario.", ".$notificacao->staNotificacao.", '".$notificacao->dscTituloNotificacao."', '".$notificacao->dscNotificacao."', timestamp(str_to_date('".$notificacao->datNotificacao."', '%d/%m/%Y')))";

		return $this->executeQuery($query);

	}

	public function delete($idNotificacao) {

		$query = "DELETE FROM notificacao WHERE seq_notificacao = ".$idNotificacao;

		return $this->executeQuery($query);

 	}

	public function update($notificacao) {

		$query = "UPDATE notificacao SET ".  
   "seq_usuario = ".$notificacao->seqUsuario.", ". 
 "sta_notificacao = ".$notificacao->staNotificacao.", ". 
 " dsc_titulo_notificacao = '".$notificacao->dscTituloNotificacao."', ". 
  " dsc_notificacao = '".$notificacao->dscNotificacao."', ". 
  " dat_notificacao = timestamp(str_to_date('".$notificacao->datNotificacao."', '%d/%m/%Y') WHERE seq_notificacao = ".$notificacao->seqNotificacao ;

		return $this->executeQuery($query);

	}

	public function findById($idNotificacao) {

		$query = "SELECT * FROM notificacao WHERE seq_notificacao = ".$idNotificacao;

		$result = $this->executeQuery($query);

		$notificacao = new Notificacao();

		while($item = mysql_fetch_array($result)) 
		{
			$notificacao->seqNotificacao = $item['seq_notificacao'];   
			$notificacao->seqUsuario = $item['seq_usuario'];   
			$notificacao->staNotificacao = $item['sta_notificacao'];   
			$notificacao->dscTituloNotificacao = $item['dsc_titulo_notificacao'];   
			$notificacao->dscNotificacao = $item['dsc_notificacao'];   
			$notificacao->datNotificacao = $item['dat_notificacao'];   
		}

		return $notificacao;

	}

	public function findAll() {

		$query = "SELECT * FROM notificacao";

		return $this->executeQuery($query);

	}

}

?>