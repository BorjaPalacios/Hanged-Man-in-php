<?php 


	function formIden(){

		echo "<form action='index.php' method='post'>\n";
		echo "<label>Nombre:</label>\n";
		echo "<input type='text' name='nombre'><br>\n";
		echo "<label>Contraseña:</label>\n";
		echo "<input type='password' name='contraseña'><br>\n";
		echo "<input type='submit' value='Enviar'>\n";
		echo "</form>\n";
	}

	function elegirJson($nivel){

		$data = file_get_contents("./jsons/palabras.json");
		$palabras = json_decode($data,true);	

		return $palabras["$nivel"];
	}

	function rndPalabra($palabras){

		shuffle($palabras);

		return $palabras[0];
	}

	function checkPalabra($palabra,$letra){

		$fallo = true;
		foreach ($_SESSION['letras'] as $cosa) {

			if($cosa == $letra) {

				$fallo = false;
				upDateLetras($letra);
				break;

			}

		}

		return $fallo;

	}

	function upDateLetras($letra){

		$_SESSION['letras'] = array_diff($_SESSION['letras'], [$letra]);

	}

	function fillLetras(){
		
			$_SESSION['letras'] = array_diff($_SESSION['letras'], $_SESSION['usadas']);
	}

	function pintarPalabra($letras,$aciertos){

		$check = false;
			
		for ($i=0; $i < count($letras); $i++) {

			for ($j=0; $j < count($aciertos); $j++) { 
			
				if($letras[$i] == $aciertos[$j]) {
				
					$check = true;

				}

			}

			if ($check) {
				
				echo "".$letras[$i]." ";
				$check = false;

			}else{

				echo "___ ";

			}
						
		}	

		echo "<br><br>";

		
	}

	function pintarIntroducirLetra(){

		echo "<form action='ahorcado.php' method='post'>\n";
		echo "<label>Introduzca letra</label>\n";
		echo "<input type='text' name='letra' maxlength=1 size='3' autocomplete='off' value='' autofocus>\n";
		echo "<input type='submit' value='Probar'><br><br>";
		echo "</form><br>";
	}

	function pintarMonigote($fallos){

		echo "<img src='./imagenes/".$fallos.".png'>";

	}

	function contarLetras($palabra){

		return  str_split($palabra);
	}

	function probarPalabra(){

		echo "<form action='ahorcado.php' method='post'>\n";
		echo "<label>Introduzca palabra</label>\n";
		echo "<input type='text' name='palabra' value='' autocomplete='off' placeholder='El fallo cuenta doble'>\n";
		echo "<input type='submit' value='Probar'><br><br>";
		echo "</form>";
	}

	function cerrarSession(){

		echo "<form action='ahorcado.php' method='post'>\n";
		echo "<input type='submit' name='cerrar' value='Cerrar sesion'>";
		echo "</form>";
	}

	function rellenarJson($json){

		$jsonew = ["password" => $_SESSION['contraseña'], "nivel" => $_SESSION['nivel']];
		$json['users'][$_SESSION['nombre']] = $jsonew;
		file_put_contents('./jsons/jugadores.json', json_encode($json));   

	}

	function pintarPros($nivel,$pros){

		echo "<p>El nivel maximo al que se ha llegado es ".$nivel."</p>";
		echo "<p>Los jugadores que han conseguido este logro son: <br>".implode("<br> ", $pros)."</p>";
	}


 ?>