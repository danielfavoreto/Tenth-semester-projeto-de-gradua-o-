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
	if (isset($_GET['refresh'])){

		//$refresh = $_GET['refresh'];

		if (isset($_GET['t'])){

			//$t = $_GET['t'];
						
			$sql = mysqli_query($conexao,"SELECT * FROM alertas");

			$rows = array();

			while ($r = mysqli_fetch_assoc($sql)) {
    			
    			$rows[] = $r;
			}

			$json = json_encode($rows);
			echo $json;

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