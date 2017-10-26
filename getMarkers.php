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
	if (isset($_GET['tempo'])){

		$tempo = $_GET['tempo'];

		if (isset($_GET['t'])){

			//$t = $_GET['t'];
						
			$sql = mysqli_query($conexao,"SELECT * FROM alertas WHERE TIMESTAMPDIFF(HOUR, concat(data,' ',hora) , NOW()) <= '$tempo'");
//concat(data,' ',hora);

			if ($sql == FALSE){ // deu ruim na consulta

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