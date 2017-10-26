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

	if (isset($_POST['dataInicio'])){

		$dataInicioPost = $_POST['dataInicio'];

		$dataInicio = date('Y-d-m', strtotime($dataInicioPost));

		if (isset($_POST['dataFim'])){

			$dataFimPost = $_POST['dataFim'];

			$dataFim = date('Y-d-m', strtotime($dataFimPost));

			$sql = mysqli_query($conexao,"SELECT id,nomePessoa,telefone, IF(status = 0, 'Novo', IF(status = 1, 'Assalto', IF(status = 2, 'Acidente', IF(status = 3, 'Outros', 'Falso')))) AS status ,date_format(data,'%d/%m/%Y') as data,hora,lat,lng,login,resposta FROM alertas WHERE data >= '$dataInicio' AND data <= '$dataFim'");	
			
			if ($sql === FALSE){

				echo "false";
			}
			else {

				// output headers so that the file is downloaded rather than displayed
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=data.csv');

				// create a file pointer connected to the output stream
				$output = fopen('php://output', 'w');

				// output the column headings
				fputcsv($output, array('Id', 'Nome', 'Telefone', 'Status', 'Data', 'Hora', 'Latitude', 'Longitude', 'Login', 'Resposta'));

				// loop over the rows, outputting them
				while ($row = mysqli_fetch_assoc($sql)) fputcsv($output, $row);

				fclose($output);

			}
		}
		else {

			echo "false no data fim";		
		}
	}
	else {

		echo "false no data inicio";
	}
?>