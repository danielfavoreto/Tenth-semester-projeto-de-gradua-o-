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

						$sql = mysqli_query($conexao,"INSERT INTO alertas (nomePessoa,telefone,status,data,hora,lat,lng,login) values ('$name', '$tel', '0', curdate(), curtime(), '$lat', '$lng', '$usr')");

						if ($sql){
							
							// pega o id do ultimo insert
							$id = $conexao->insert_id;	

							$file_path = "uploads/" .($conexao->insert_id);

	   						$file_path = $file_path . basename( $_FILES['uploaded_file']['name']);

	   						$tentativa = move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path);

	   						if ($tentativa){

								$data = [ 'status' => 'true', 'id' => $id];
				    			header('Content-Type: application/json');
								echo json_encode($data);	   							
	   						}
	   						else{

								$data = [ 'status' => ' erro ao enviar o áudio'];
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