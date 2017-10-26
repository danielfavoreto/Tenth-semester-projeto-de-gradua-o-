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

	if (isset($_GET['dataInicio'])){

		$dataInicioPost = $_GET['dataInicio'];

		$dataInicio = date('Y-d-m', strtotime($dataInicioPost));

		if (isset($_GET['dataFim'])){

			$dataFimPost = $_GET['dataFim'];

			$dataFim = date('Y-d-m', strtotime($dataFimPost));

			$sql = mysqli_query($conexao,"SELECT lat,lng FROM alertas WHERE data >= '$dataInicio' AND data <= '$dataFim'");	
			
			if ($sql === FALSE){ // deu ruim na consulta

				$data = [];
				echo json_encode($data);
			}
			else {

				$rows = array();

				while ($r = mysqli_fetch_assoc($sql)) {
	    			
	    			$rows[] = $r;
				}

				$json = json_encode($rows);
				echo $json;
			}
		}
		else {

			$data = [];
			echo json_encode($data);	
		}
	}
	else {

		$data = [];
		echo json_encode($data);
	}
?>