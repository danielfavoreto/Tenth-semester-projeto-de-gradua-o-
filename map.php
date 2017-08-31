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
	session_start();
	$sessaoUsuario = $_SESSION["user"];
	$sessaoSenha = $_SESSION["password"];
	if (!isset($sessaoUsuario) || !isset($sessaoSenha)) {
		header("Location: login.php");
		exit;
	}
?>

<!DOCTYPE html>
<html>
	<head>

		<link rel="stylesheet" type="text/css" href="leaflet/leaflet.css" />

		<script src="leaflet/leaflet.js"></script>

		<script src="leaflet/embedMap.js"></script>

		<title>AlertaUfes</title>

		<style>
			html, body, #map {
		      height:100%;
		      width:100%;
		      padding:0px;
		      margin:0px;
		   	}
		</style>
		
	</head>

	<body>
		<div id = "map"> </div>
			<script>
				initmap();	
			</script>
			<?php
				$atual = $_GET["a"];
				echo "valor: $atual";
			?>
	</body>
</html>