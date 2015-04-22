<?php	
	//Includes en variabelen.
	include("../Includes/session.php");
	require_once("../Includes/Functions.php");
	$page = "Admin";
	include("../Includes//db_connect.php");
	include("../Includes/Layouts/header.php");
	
	if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] == FALSE) {
		$_SESSION["errors"][] = "U moet eerst inloggen";
		header("Location: login.php");
	}
?>

<div id="content">
	<h2> Controlepaneel: </h2>
	
	<ul>
		<li> <a <?php echo ($page == "Admin") ? "class='selected'" : ""; ?> href="besteladvieslijst.php" style="color:red"> Besteladvieslijst </a> </li>
		<li> <a <?php echo ($page == "Admin") ? "class='selected'" : ""; ?> href="verkooporders.php" style="color:red"> Overzicht Verkooporders </a> </li>
		<!--<li> <a <?php echo ($page == "Admin") ? "class='selected'" : ""; ?> href="Routepage.php" style="color:red"> Route </a> </li>-->
	</ul>
	
</div>

<?php
	//Include footer.
	include("../Includes/Layouts/footer.php");
?>
