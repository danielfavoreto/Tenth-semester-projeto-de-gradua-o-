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

		if (isset($_GET['resposta'])){

			$resposta = $_GET['resposta'];

			$sql = mysqli_query($conexao,"UPDATE alertas SET resposta = '$resposta' WHERE id = $id");

			if ($sql){

				echo "true";
			}
			else {

				echo "false na consulta sql";
			}
		}
		else {
			echo "false no alerta[resposta]";
		}
	}
	else {
		echo "false no id";
	}
?>