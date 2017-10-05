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
	if (isset($_POST['id'])){

		$id = $_POST['id'];

		$sql = mysqli_query($conexao,"SELECT resposta FROM alertas WHERE id = '$id'");

		if ($sql){

			$rows = mysqli_fetch_array($sql);

			$resposta = $rows['resposta'];

			if ($resposta){

				$data = [ 'status' => 'true', 'respostaRapida' => $resposta];
				header('Content-Type: application/json');
				echo json_encode($data);	   							
			}
			else{
					
				$data = [ 'status' => 'false'];
				header('Content-Type: application/json');
				echo json_encode($data);
			}
		}
		else {
			$data = [ 'status' => 'false'];
			header('Content-Type: application/json');
			echo json_encode($data);
		}
	}
	else {
		$data = [ 'status' => 'false'];
		header('Content-Type: application/json');
		echo json_encode($data);
	}
?>