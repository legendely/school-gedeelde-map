<?php
	//Controleer login status.
	function login_status() {
		if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] == FALSE) {
			$_SESSION["errors"][] = "U moet eerst inloggen";
			header("Location: login.php");
		}
	}
	
	//Output van foutmeldingen.
	function print_errors($array) {
		//Message start als NULL, krijgt pas waarde als er errors zijn.
		$msg = "Er zijn een aantal fouten opgetreden: ";
		$msg .= "<ul>";
		foreach($array as $fouten) {
			$msg .= "<li>" . $fouten . "</li>";
		}
		$msg .= "</ul> <br/>";
			
		//Zet session variable weer op NULL om te voorkomen dat de
		//messages er in blijven staan.
		$_SESSION["errors"] = NULL;
		return $msg;
	}
	
	//Output van berichten
	function print_msg($array) {
		$msg = "";
		foreach($array as $bericht) {
			$msg .= $bericht;
		}
		$msg .= "<br/> <br/>";
			
		//Zet session variable weer op NULL om te voorkomen dat de
		//messages er in blijven staan.
		$_SESSION["message"] = NULL;
		return $msg;
	}
	
	//Controleer op maximale lengte.
	function check_max_len($var, $lengte) {
		return (strlen($var) <= $lengte);
	}
	
function get_artikelen($where="") {
		//Verkrijg de connectie.
		global $connection;
		
		//Aanmaak en uitvoer query
		$query = "SELECT * FROM employees {$where} ORDER BY employeeid;";
		$result = mysqli_query($connection, $query);
		return $result;
	}
?>