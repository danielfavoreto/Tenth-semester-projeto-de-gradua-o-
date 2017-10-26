<html>
<head>
	<title></title>
	<script type="text/javascript">

		function loginOk(){
			setTimeout("window.location = 'map.php'", 1);
		}	

		function loginFail(){
			setTimeout("window.location = 'login.php'", 1);
		}

	</script>
</head>
<body>
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
		$user = $_POST['user'];
		$password = $_POST['password'];
		$sql = "SELECT * FROM usuarios WHERE login = '$user' AND senha = '$password'";

		$rs = $conexao->query($sql);
 
		if ($rs === false) {
		  trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conexao->error, E_USER_ERROR);
		}

		$row = $rs->num_rows;

		if ($row > 0){
			session_start();
			$_SESSION['user'] = $_POST['user'];
			$_SESSION['password'] = $_POST['password'];
			echo "<script> loginOk() </script>";
		}
		else {

			echo "<script> loginFail() </script>";
		}
	?>
</body>
</html>
