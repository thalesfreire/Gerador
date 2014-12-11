<?php 

require_once("Field.php");

class Gerador {

	public $connection;
	
	public $sequencialDaTabela;
	
	public $nomeBanco;
	
	public $nomeClasse;
	
	public $nomeObjParam;

	public function __construct() {
		$this->connection = mysql_connect("localhost","root","");
	}


	public function listarBD() {
	
		$db_list = mysql_list_dbs($this->connection);
		$bds = array();

		while ($row = mysql_fetch_object($db_list)) {
			 $bds[] = $row->Database;
		}
		
		return $bds;
		
	}
	
	public function listarTabelas($nomeBD) {
	
		$sql = "SHOW TABLES FROM " . $nomeBD;
		$result = mysql_query($sql);
		$tables = array();
		
		while ($row = mysql_fetch_row($result)) {
		   	$tables[] = $nomeBD.".".$row[0];
		}
	
		return $tables;
	}
	
	public function listarCampos($nomeTabela) {
	
		$result = mysql_query("SHOW COLUMNS FROM ".$nomeTabela);
		$fields = array();
		
		while ($row = mysql_fetch_assoc($result)) {
			$field = new Field();
			$field->name = $row['Field'];
			$field->type = $row['Type'];
			$field->isPK = $row['Key'] == 'PRI' ? true : false ;
			
			
			$fields[] = $field;
		}
		
		return $fields;
	
	}
	
	
	public function gerarClasse($nomeTabela, $nomeDB) { 
	
	
		//Gerando o nome da Tabela :
		$tabelaSplited = explode("_",str_replace($nomeDB.".","",$nomeTabela));
		
		$this->nomeClasse = ucfirst($tabelaSplited[0]).ucfirst($tabelaSplited[1]).ucfirst($tabelaSplited[2]).ucfirst($tabelaSplited[3]);
		$this->nomeObjParam = $tabelaSplited[0].ucfirst($tabelaSplited[1]).ucfirst($tabelaSplited[2]).ucfirst($tabelaSplited[3]);
		
	
	
		$campos = $this->listarCampos($nomeTabela);
	
		$classModelString = "require_once (\"GenericManager.php\");\n\n";
	
		$classModelString .= "class ".$this->nomeClasse." {  \n ";
		$classModelString .= "\n ";
		
		foreach ($campos as $campo) {
		
			$campoSplited = explode("_", $campo->name);
			
			$classModelString .= "	public \$".$campoSplited[0].ucfirst($campoSplited[1]).ucfirst($campoSplited[2]).ucfirst($campoSplited[3]).ucfirst($campoSplited[4]).ucfirst($campoSplited[5]).ucfirst($campoSplited[6]).";\n";	

		}
	
		$classModelString .= "\n";
		$classModelString .= "}\n\n";
		
		
		$classManagerString = "class ".$this->nomeClasse."Manager extends GenericManager {\n";
		
		$classManagerString .= "\n";
		
		//Metodo SAVE
		$classManagerString .= "	public function save($".$this->nomeObjParam.") {\n\n";
		
		$classManagerString .= "		\$query = \"INSERT INTO ".str_replace($nomeDB.".","",$nomeTabela) ." (";
		
		$camposQuery;
		
		foreach ($campos as $campo) {
			if(!$campo->isPK){
				$camposQuery .= $campo->name . ", ";
			} else {
				$this->sequencialDaTabela = $campo->name;
			}
		}
		
		$classManagerString .=substr($camposQuery, 0, -2). ") ";
		
		$classManagerString .= "VALUES (";
		
		$valuesCamposQuery;
		
		foreach ($campos as $campo) {
			
			$campoSplited = explode("_", $campo->name);
			
			if(!$campo->isPK){
			
				if(strtolower(substr($campo->type, 0, 7)) == 'varchar') {
					$valuesCamposQuery .= "'\".\$".$this->nomeObjParam."->". $campoSplited[0].ucfirst($campoSplited[1]).ucfirst($campoSplited[2]).ucfirst($campoSplited[3]).ucfirst($campoSplited[4]).ucfirst($campoSplited[5]).ucfirst($campoSplited[6]). ".\"', ";
				} 
				
				if(strtolower(substr($campo->type, 0, 3)) == 'int' || strtolower(substr($campo->type, 0, 6)) == 'bigint'){
					$valuesCamposQuery .= "\".\$".$this->nomeObjParam."->". $campoSplited[0].ucfirst($campoSplited[1]).ucfirst($campoSplited[2]).ucfirst($campoSplited[3]).ucfirst($campoSplited[4]).ucfirst($campoSplited[5]).ucfirst($campoSplited[6]). ".\", ";
				}
				
				if(strtolower($campo->type) == 'timestamp'){
					$valuesCamposQuery .= "timestamp(str_to_date('\".\$".$this->nomeObjParam."->". $campoSplited[0].ucfirst($campoSplited[1]).ucfirst($campoSplited[2]).ucfirst($campoSplited[3]).ucfirst($campoSplited[4]).ucfirst($campoSplited[5]).ucfirst($campoSplited[6]). ".\"', '%d/%m/%Y')), ";
				}
				
			}
		}
		
		
		$classManagerString .= substr($valuesCamposQuery, 0, -2);
		
		$classManagerString .= ")\";\n\n";
		
		
		$classManagerString .= "		return "."\$this->executeQuery(\$query);\n";
		
		
		$classManagerString .= "\n";
		
		$classManagerString .= "	}\n\n";
		
		
		
		//Metodo DELETE
		$classManagerString .= "	public function delete(\$id".$this->nomeClasse.") {\n\n";
		
		$classManagerString .= "		\$query = \"DELETE FROM ".str_replace($nomeDB.".","",$nomeTabela)." WHERE ".$this->sequencialDaTabela." = \".\$id". $this->nomeClasse . ";\n\n";
		
		$classManagerString .= "		return " ."\$this->executeQuery(\$query);\n\n ";
		
		$classManagerString .= "	}\n\n";
		
		
		//Metodo UPDATE
		$classManagerString .= "	public function update($".$this->nomeObjParam.") {\n\n";
		
		$classManagerString .= "		\$query = \"UPDATE ".str_replace($nomeDB.".","",$nomeTabela)." SET \".  \n  ";
		
		$seqTabela;
		$idClasse;
		
		foreach($campos as $campo) {
		
			$campoSplited = explode("_", $campo->name);
		
			if(!$campo->isPK){ 
			
				if(strtolower(substr($campo->type, 0, 7)) == 'varchar') {
					$classManagerString .= " \" ".$campo->name. " = '\".\$".$this->nomeObjParam."->".$campoSplited[0].ucfirst($campoSplited[1]).ucfirst($campoSplited[2]).ucfirst($campoSplited[3]).ucfirst($campoSplited[4]).ucfirst($campoSplited[5]).ucfirst($campoSplited[6]).".\"', \"."." \n ";
				} 
				
				if(strtolower(substr($campo->type, 0, 3)) == 'int' || strtolower(substr($campo->type, 0, 6)) == 'bigint'){
					$classManagerString .= " \"".$campo->name. " = \".\$".$this->nomeObjParam."->".$campoSplited[0].ucfirst($campoSplited[1]).ucfirst($campoSplited[2]).ucfirst($campoSplited[3]).ucfirst($campoSplited[4]).ucfirst($campoSplited[5]).ucfirst($campoSplited[6]).".\", \". \n";
				}
			
				if(strtolower($campo->type) == 'timestamp'){
					 $classManagerString .= " \" ".$campo->name. " = timestamp(str_to_date('\".\$".$this->nomeObjParam."->". $campoSplited[0].ucfirst($campoSplited[1]).ucfirst($campoSplited[2]).ucfirst($campoSplited[3]).ucfirst($campoSplited[4]).ucfirst($campoSplited[5]).ucfirst($campoSplited[6]). ".\"', '%d/%m/%Y')), \". \n";
				}
			
			} else {
				$idClasse = $campoSplited[0].ucfirst($campoSplited[1]).ucfirst($campoSplited[2]).ucfirst($campoSplited[3]).ucfirst($campoSplited[4]).ucfirst($campoSplited[5]).ucfirst($campoSplited[6]);
				
			}
			
			
		
		}
		
		$classManagerString = substr($classManagerString, 0, - 7);
		
		$classManagerString .= " WHERE ".$this->sequencialDaTabela." = \".$".$this->nomeObjParam."->".$idClasse. " ;\n\n";
		
		$classManagerString .= "		return " ."\$this->executeQuery(\$query);\n\n";
		
		$classManagerString .= "	}\n\n";
		
		
		//Metodo FIND BY ID
		$classManagerString .= "	public function findById(\$id".$this->nomeClasse.") {\n\n";
		
		$classManagerString .= "		\$query = \"SELECT * FROM ".str_replace($nomeDB.".","",$nomeTabela)." WHERE ".$this->sequencialDaTabela." = \".\$id".$this->nomeClasse.";\n\n";
		
		
		$classManagerString .= "		\$result = \$this->executeQuery(\$query);\n\n";
		
		$classManagerString .= "		\$".$this->nomeObjParam." = new ".$this->nomeClasse."();\n\n";
		
		
		
		$classManagerString .= "		while(\$item = mysql_fetch_array(\$result)) \n";
		$classManagerString .= "		{\n";
		
		
		foreach($campos as $campo) {
		
			$campoSplited = explode("_", $campo->name);
			$classManagerString .= "			\$".$this->nomeObjParam."->".$campoSplited[0].ucfirst($campoSplited[1]).ucfirst($campoSplited[2]).ucfirst($campoSplited[3]).ucfirst($campoSplited[4]).ucfirst($campoSplited[5]).ucfirst($campoSplited[6])." = \$item['".$campo->name."'];   \n";
		
		}
		
		
		
		$classManagerString .= "		}\n\n";
		
		
		
		
		$classManagerString .= "		return \$".$this->nomeObjParam.";\n\n";
		
		$classManagerString .= "	}\n\n";
		
		
		//Metodo FIND BY ID
		$classManagerString .= "	public function findAll() {\n\n";
		
		$classManagerString .= "		\$query = \"SELECT * FROM ".str_replace($nomeDB.".","",$nomeTabela)."\";\n\n";
		
		$classManagerString .= "		return \$this->executeQuery(\$query);\n\n";
		
		$classManagerString .= "	}\n\n";
		
		$classManagerString .= "}\n\n";
		
		//echo $classManagerString;
		
		
		$aberturaArquivoPHP = "<?php \n\n";
		$fechamentoArquivoPHP = "?>";
		
		
		file_put_contents("Gerados/".$this->nomeClasse."Manager.php", $aberturaArquivoPHP. $classModelString . $classManagerString . $fechamentoArquivoPHP);
		
		
	}
	

}


?>