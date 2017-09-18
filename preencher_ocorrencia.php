<?php
	$host = 'alertaUfes';
	$user = 'root';
	$pass = '';
	$database_name = 'ufes';
	$conexao = new mysqli($host, $user, $pass, $database_name);
	if ($conexao->connect_error) {
			trigger_error('Database connection failed: '  . $conexao->connect_error, E_USER_ERROR);
	}
?>    	
<?php		
	if (isset($_GET['id'])){
						
		$id = $_GET['id'];

		$sql = mysqli_query($conexao,"SELECT nome, telefone FROM alertas WHERE id = '$id'");

		$rows = array();

		while ($r = mysqli_fetch_assoc($sql)) {
			
			$rows[] = $r;
		}

		$json = json_encode($rows);
		echo $json;
	}
	else {
		
		echo "";
	}    	
	$html = "<div id='info-painel-preencher-ocorrencia'><p1><b></b></p1><br><p2></p2><br><p3></p3></div><div><form id='w0' action='/web/index.php?r=alerta%2Fupdate-ocorrencia&amp;id=254' method='post'><div class='row'><div class='col-md-12'><div class='form-group field-alerta-categoria'><label class='control-label' for='alerta-categoria'> Categoria </label><select id='alerta-categoria' class='form-control' name='Alerta[categoria]'><option value=''></option><option value='Furto'>Furto</option><option value='Acidente' selected=''>Acidente</option><option value='Outros'>Outros</option><option value='Falso'>Falso</option></select></div></div></div><div class='row'><div class='col-md-12'><a class='btn btn-primary' onclick='updateOcorrencia(254)'>Salvar</a><a class='btn btn-default' style='margin-left:10px;' onclick='fecharPainelPreencherOcorrencia()'>Fechar</a></div></div></form></div>";
	echo $html;
?>