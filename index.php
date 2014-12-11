<?php 

require_once("classes/Gerador.php");

?>

<html>


<link href="css/style.css" type="text/css" rel="stylesheet" />
<body>
<form action="tabelas.php" >
<table>

	<tr>
		<th>Banco de Daodos</th>
		<th></th>
	</tr>
	
	


<?php
$gerador = new Gerador();
$bancos = $gerador->listarBD();

foreach($bancos as $banco) {

?>

	<tr>
		<td><?=$banco?></td>
		<td><input type="radio" value="<?=$banco?>" name="banco" id="<?=$banco?>"/></td>
	</tr>

<?php
} 
?>	

</table>

<p>

<input type="submit" value="Listar tabelas" />

</p>

</form>
</body>
</html>



