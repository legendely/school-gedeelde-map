<?php
	include("../Includes/session.php");
	
	if(!$_SESSION["logged_in"]) {
		header("Location: login.php");
	} else {
		session_destroy();
		$_SESSION["message"][] = "U bent nu uitgelogd";
		header("Location: index.php");
	}
?>