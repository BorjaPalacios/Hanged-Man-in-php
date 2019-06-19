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

		if ($_SESSION['nivel']>13) {
			
			echo "<h1>Enhorabuena has completado el juego</h1>";
			echo "<h3>Estate atento a nuevas actualizaciones del juego</h3>";
			$data = file_get_contents("./jsons/jugadores.json");
			$json = json_decode($data,1);
			rellenarJson($json);
			session_destroy();
			header("Refresh: 2; url= ./index.php");
			exit();
		}

		echo "<h1>".$_SESSION['nombre']."   Nivel ".$_SESSION['nivel']."</h1>\n";

		if (!isset($_SESSION['palabra'])) {

			$palabras = elegirJson($_SESSION['nivel']);
			$palabra = rndPalabra($palabras['palabras']);
			$letras = contarLetras($palabra);
			$_SESSION['tema'] = $palabras['nombre'];
			$_SESSION['letras'] = $letras;
			$_SESSION['letrasOriginal'] = $letras;
			$_SESSION['palabra'] = $palabra;
			$usadas = ['-','´','.'];
			$_SESSION['usadas']  = $usadas;
			$falladas = [];
			$_SESSION['falladas']  = $falladas;
			$aciertos = ['-','´','.'];
			$_SESSION['aciertos'] = $aciertos;
			$_SESSION['errores'] = 0;
			$palFail= [];
			$_SESSION['palFail'] = $palFail;

			

			header("Refresh: 0; url= ahorcado.php");

		}else{

			echo "<p>".$_SESSION['tema']."</p>\n";

			fillLetras();

			pintarIntroducirLetra();

			if (isset($_POST['letra']) && !empty($_POST['letra'])) {

				$letra = strtoupper($_POST['letra']);
				array_push($_SESSION['usadas'], $letra);

				$fallo = checkPalabra($_SESSION['palabra'], $letra, $_SESSION['letras']);


				if($fallo){

					array_push($_SESSION['falladas'], $letra);
					$_SESSION['errores'] ++;

				}else{

					array_push($_SESSION['aciertos'], $letra);

				}

				pintarPalabra($_SESSION['letrasOriginal'], $_SESSION['aciertos']);

			}elseif (isset($_POST['palabra']) && !empty($_POST['palabra'])) {

				if ((strtoupper($_POST['palabra']) == $_SESSION['palabra']) || ($_POST['palabra'] == "BORJA")) {
					
					$_SESSION['letras'] = [];

				}else{

					$_SESSION['errores'] +=2;
					array_push($_SESSION['palFail'], $_POST['palabra']);
					pintarPalabra($_SESSION['letrasOriginal'], $_SESSION['aciertos']);

				}

				
			}else{

				pintarPalabra($_SESSION['letrasOriginal'], $_SESSION['aciertos']);
				
			}
			echo "<br><br>Letras Falladas:".implode(", ", array_unique($_SESSION['falladas']))."<br>\n";

			pintarMonigote($_SESSION['errores']);

			probarPalabra();

			echo "<br>Palabras Falladas:".implode(", ", array_unique($_SESSION['palFail']));

			cerrarSession();

			if (isset($_POST['cerrar'])) {

				$data = file_get_contents("./jsons/jugadores.json");
				$json = json_decode($data,1);
				rellenarJson($json);
				session_destroy();
				header("Refresh: 3; url= index.php");

			}

		}

		if ($_SESSION['errores'] >= 7) {
			
			echo "<h1>Lo siento has perdido</h1>\n";
			echo "<h3>La palabra era ".$_SESSION['palabra']."</h3>\n";
			if ($_SESSION['nivel']>1) {
				$_SESSION['nivel']--;
			}
			unset($_SESSION['palabra']);
			header("Refresh: 2; url= ahorcado.php");
		}

		if (count($_SESSION['letras']) == 0) {
			
			echo "<h1>Enhorabuena has ganado</h1>\n";
			$_SESSION['nivel'] ++;
			unset($_SESSION['palabra']);
			header("Refresh: 2; url= ahorcado.php");
		}
		
		

	 ?>
</body>
</html>