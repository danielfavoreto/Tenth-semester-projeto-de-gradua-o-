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

		if (isset($_POST['categoria'])){

			$categoria = $_POST['categoria'];

			$sql = mysqli_query($conexao,"UPDATE alertas SET status = $categoria WHERE id = $id");

			if ($sql){

				echo "true";
			}
			else {

				echo "false na consulta sql";
			}
		}
		else {
			echo "false no alerta[categoria]";
		}
	}
	else {
		echo "false no id";
	}
?>