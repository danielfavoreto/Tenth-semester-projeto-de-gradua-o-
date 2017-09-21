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
	if (isset($_POST["usr"])){
		$user = $_POST["usr"];
		if (isset($_POST['psw'])){
			$password = $_POST['psw'];
			if (isset($_POST['cel'])){
				$cellPhone = $_POST['cel'];
				$sql = mysqli_query($conexao,"SELECT nome FROM pessoas WHERE login = '$user' AND senha = '$password'");

				$row = mysqli_fetch_array($sql);
   				$result = $row[0];

			   	if($result){
			   		$updateCellPhone = "UPDATE pessoas SET telefone = '$cellPhone' WHERE login = '$user'";

			   		$data = [ 'status' => 'true', 'nomePessoa' => $result];
			    	header('Content-Type: application/json');
					echo json_encode($data);
			   	}
			   	else{
			   		$data = [ 'status' => 'false', 'nomePessoa' => 'erro na query principal' ];
    				header('Content-Type: application/json');
					echo json_encode($data);
			   	}
			}
			else {
				$data = [ 'status' => 'false', 'nomePessoa' => 'erro na query de cel' ];
    			header('Content-Type: application/json');
				echo json_encode($data);	
			}
		}
		else {
			$data = [ 'status' => 'false', 'nomePessoa' => 'erro na query de psw' ];
    		header('Content-Type: application/json');
			echo json_encode($data);
		}
	}
	else {
		$data = [ 'status' => 'false', 'nomePessoa' => 'erro na query de usr' ];
    	header('Content-Type: application/json');
		echo json_encode($data);	
	}
   	mysqli_close($conexao);
?>