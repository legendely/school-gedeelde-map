<?php
	//Vebinden met database EatIT.
	$dbhost = "localhost";
	$dbuser = "root";
	$dbpass = "";
	$dbname = "EatIT2";
	
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	//Verbinding succesvol?
	if (mysqli_connect_errno()) {
		DIE("Verbinding mislukt: " . mysqli_connect_error());
	}
?>