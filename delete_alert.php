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
						
		$sql = mysqli_query($conexao,"DELETE FROM alertas WHERE id = $id");

		if ($sql){
			echo "true";			
		}
		else {
			echo "false";
		}

	}
	else {
		
		echo "false";
	}
?>