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

		$sql = mysqli_query($conexao,"SELECT nome, telefone, status, resposta FROM alertas WHERE id = '$id'");

		$rows = mysqli_fetch_array($sql);

		$nome = $rows['nome'];
		$telefone = $rows['telefone'];
		$status = $rows['status'];
		$resposta = $rows['resposta'];

		$w0 = '';
		$w1 = '';
		$w2 = '';
		$w3 = '';

		$v0 = '';
		$v1 = '';
		$v2 = '';
		$v3 = '';
		$v4 = '';

		if ($resposta == 'Segurança a caminho'){
			$w0 = 'selected';
		}
		else if ($resposta == 'Visualizado'){
			$w1 = 'selected';
		}
		else if ($resposta == 'Aguarde'){
			$w2 = 'selected';
		}
		else{
			$w3 = 'selected';
		}

		if ($status === '1'){
			$v1 = 'selected';
		}
		else if ($status === '2'){
			$v2 = 'selected';
		}
		else if ($status === '3'){
			$v3 = 'selected';
		}
		else if ($status === '4'){
			$v4 = 'selected';
		}
		else {
			$v0 = 'selected';
		}

		$audio = $id ."audio.mp3";
		$html = "<div class = 'container'><div id='info-painel-preencher-ocorrencia'><p1><b>$nome</b></p1><br><p2>$telefone</p2><br></div>

		<div class = 'row'>
			<div class='col-md-12'>
				<label class='control-label' for='alerta-audio'> Áudio </label>
				<audio controls>
					<source src='uploads/$audio' type='audio/mpeg'>
					Áudio da ocorrência
				</audio>
			</div>
		</div>
		<div class='row'>

                <div class='col-md-12'>
                    
                    <div id='respostaFormGroup' class='form-group field-alerta-resposta'>
                        
                        <label class='control-label' for='alerta-resposta'>Resposta Rápida</label>
                        
                        <select class='form-control' id='fieldResposta' onchange='updateRespostaRapida($id)' for='alerta-resposta' name='Alerta[resposta]'>
                            <option value='' $w3></option><option value='Segurança a caminho' $w0>Segurança a Caminho</option><option value='Visualizado' $w1>Visualizado</option><option value='Aguarde' $w2>Aguarde</option>
                        </select>
                        
                    </div>

                </div>

            </div>
		<div id = 'container-preencher'><form id='w1'><div class='row'><div class='col-md-12'><div class='form-group field-alerta-categoria'><label class='control-label' for='alerta-categoria'> Categoria </label><select id='alerta-categoria' class='form-control' name='categoria'><option value='0' $v0></option><option value='1' $v1>Assalto</option><option value='2' $v2>Acidente</option><option value='3' $v3>Outros</option><option value='4' $v4>Falso</option></select></div></div></div><div class='row'><div class='col-md-12'><a class='btn btn-primary' onclick='updateOcorrencia($id)'>Salvar</a><a class='btn btn-default' style='margin-left:10px;' onclick='fecharPainelPreencherOcorrencia()'>Fechar</a></div></div></form></div></div>";

		echo $html;
	}
	else {
		
		echo "";
	}    	
?>