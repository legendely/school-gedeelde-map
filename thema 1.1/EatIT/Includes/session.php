<?php

	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	
	//Aanmaken array voor errors als deze nog niet bestaat.
	if(!isset($_SESSION["errors"])) {
		$_SESSION["errors"] = array();
	}	
	
	//Aanmaken array voor berichten als deze nog niet bestaat.
	if(!isset($_SESSION["message"])) {
		$_SESSION["message"] = array();
	}
	
?>
