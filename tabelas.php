<?php 

require_once("classes/Gerador.php");

?>

<html>


<link href="css/style.css" type="text/css" rel="stylesheet" />
<body>
<form action="classes.php" method="post">
<table>

	<tr>
		<th>Tabela</th>
		<th></th>
	</tr>
	
	


<?php

$banco = $_REQUEST['banco'];

$gerador = new Gerador();
$tabelas = $gerador->listarTabelas($banco);

foreach($tabelas as $tabela) {

?>

	<tr>
		<td><?=$tabela?></td>
		<td><input type="checkbox" value="<?=$tabela?>" name="tabelas[]" id="<?=$tabela?>"/></td>
	</tr>

<?php
} 
?>	

</table>
<input type="hidden" id="banco" name="banco" value="<?=$_REQUEST['banco']?>" />
<p>

<input type="button" value="Voltar" onClick="javascript:history.back();" />

</p>

<p>

<input type="submit" value="Gerar Classes" />

</p>

</form>
</body>
</html>



