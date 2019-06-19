<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>ahorcado</title>
	<link rel="stylesheet" type="text/css" href="ahorcado.css">
</head>
<body>
	<?php 

		require_once './functions.php';

		session_start();

		if(!isset($_SESSION['contraseña'])){

			$data = file_get_contents('./jsons/jugadores.json');
			$users = json_decode($data,1);
			$niveles = [];
			$pros = [];
			foreach ($users['users'] as $key => $patata) {
			
				array_push($niveles, $patata['nivel']);

			}

			$nivel = max($niveles);

			foreach ($users['users'] as $key => $pimpollos) {
				
				if ($pimpollos['nivel'] == $nivel) {
					
					array_push($pros, $key);

				}

			}

			if (!isset($_POST['contraseña']) || empty($_POST['contraseña'])) {

				formIden();
				pintarPros($nivel,$pros);


			}else{
				
				if (isset($users['users'][$_POST['nombre']])) {

					$user = $users['users'][$_POST['nombre']];

					if ($user['password'] == $_POST['contraseña']) {

						
						$_SESSION['contraseña'] = $_POST['contraseña'];
						$_SESSION['nombre'] = $_POST['nombre'];
						$_SESSION['nivel'] = $user['nivel'];

					}else{


						echo "<h1>Contraseña incorrecta o usuario ya existente</h1>";
						header("Refresh: 2; url= index.php");

					}
				}else{


					$_SESSION['contraseña'] = $_POST['contraseña'];
					$_SESSION['nombre'] = $_POST['nombre'];
					$_SESSION['nivel'] = 1;
				}
				
				header("Refresh: 2; url= index.php");

			}
			
		}else{

			echo "<p>Bienvenido al apasionante juego del ahorcado</p>\n";
			echo "<p>Esperamos que disfrute de su experiencia</p>\n";
			header("Refresh: 2; url= ahorcado.php");

		}

	 ?>
</body>
</html>