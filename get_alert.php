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
	if (isset($_POST['usr'])){

		$usr = $_POST['usr'];

		if (isset($_POST['name'])){

			$name = $_POST['name'];

			if (isset($_POST['tel'])){

				$tel = $_POST['tel'];

				if (isset($_POST['lat'])){

					$lat = $_POST['lat'];

					if (isset($_POST['lng'])){

						$lng = $_POST['lng'];
						
						$sql = mysqli_query($conexao,"INSERT INTO alertas (nome,telefone,status,dataHora,lat,lng,login) values ('$name', '$tel', '0', NOW(), '$lat', '$lng', '$usr')");

						if ($sql){
							
							$id = mysqli_query($conexao,"SELECT LAST_INSERT_ID()");	

							echo "$id";
						}
						else {
							echo "false";
						}
					}
					else {
						echo "false";
					}
				}
				else {
					echo "false";
				}
			}
			else {
				echo "false";
			}
		}
		else {
			echo "false";
		}
	}
	else {
		echo "false";
	}
?>