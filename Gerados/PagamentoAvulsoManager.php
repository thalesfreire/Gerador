<?php 

require_once ("GenericManager.php");

class PagamentoAvulso {  
 
 	public $seqPagamentoAvulso;
	public $seqUsuario;
	public $datPagamento;
	public $dscObservacao;
	public $vlrPagamento;
	public $staPagamento;

}

class PagamentoAvulsoManager extends GenericManager {

	public function save($pagamentoAvulso) {

		$query = "INSERT INTO pagamento_avulso (seq_usuario, dat_pagamento, dsc_observacao, vlr_pagamento, sta_pagamento) VALUES (".$pagamentoAvulso->seqUsuario.", timestamp(str_to_date('".$pagamentoAvulso->datPagamento."', '%d/%m/%Y')), '".$pagamentoAvulso->dscObservacao."', ".$pagamentoAvulso->staPagamento.")";

		return $this->executeQuery($query);

	}

	public function delete($idPagamentoAvulso) {

		$query = "DELETE FROM pagamento_avulso WHERE seq_pagamento_avulso = ".$idPagamentoAvulso;

		return $this->executeQuery($query);

 	}

	public function update($pagamentoAvulso) {

		$query = "UPDATE pagamento_avulso SET ".  
   "seq_usuario = ".$pagamentoAvulso->seqUsuario.", ". 
 " dat_pagamento = timestamp(str_to_date('".$pagamentoAvulso->datPagamento."', '%d/%m/%Y')), ". 
 " dsc_observacao = '".$pagamentoAvulso->dscObservacao."', ". 
  "sta_pagamento = ".$pagamentoAvulso->staPagamento. WHERE seq_pagamento_avulso = ".$pagamentoAvulso->seqPagamentoAvulso ;

		return $this->executeQuery($query);

	}

	public function findById($idPagamentoAvulso) {

		$query = "SELECT * FROM pagamento_avulso WHERE seq_pagamento_avulso = ".$idPagamentoAvulso;

		$result = $this->executeQuery($query);

		$pagamentoAvulso = new PagamentoAvulso();

		while($item = mysql_fetch_array($result)) 
		{
			$pagamentoAvulso->seqPagamentoAvulso = $item['seq_pagamento_avulso'];   
			$pagamentoAvulso->seqUsuario = $item['seq_usuario'];   
			$pagamentoAvulso->datPagamento = $item['dat_pagamento'];   
			$pagamentoAvulso->dscObservacao = $item['dsc_observacao'];   
			$pagamentoAvulso->vlrPagamento = $item['vlr_pagamento'];   
			$pagamentoAvulso->staPagamento = $item['sta_pagamento'];   
		}

		return $pagamentoAvulso;

	}

	public function findAll() {

		$query = "SELECT * FROM pagamento_avulso";

		return $this->executeQuery($query);

	}

}

?>