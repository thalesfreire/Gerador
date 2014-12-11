<?php 

require_once("classes/Gerador.php");

?>

<html>


<link href="css/style.css" type="text/css" rel="stylesheet" />
	<body>
		<form action="tabelas.php" >
	
			<table>
			
				<tr>
					<th>Classe</th>
				</tr>	


				<?php
				
				$gerador = new Gerador();
				$tabelas = $_REQUEST['tabelas'];
				
				if(isset($_POST['tabelas'])) {
					for($i = 0; $i < count($_POST['tabelas']); $i++) {
						$gerador->gerarClasse($_POST['tabelas'][$i], $_REQUEST['banco']);
						echo '<tr><td>'.str_replace($_REQUEST['banco'].".","",$_POST['tabelas'][$i]).'</td></tr>';
					}
				} else {
					echo '<tr><td>Nenhuma cor foi selecionada!</td></tr>';
				}
				
				
				
				?>

			</table>

		</form>
	</body>
</html>



