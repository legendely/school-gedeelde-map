<?php include("../Includes/session.php"); ?>
<!DOCTYPE HTML>
<html>
	<head>
		<title> EatIT - Maaltijd service </title>
		<link rel="stylesheet" type="text/css" href="Stylesheets/stylesheet.css"/>
	</head>
	
	<body>
		<div id="header">
			<a href="index.php"> <img src="Images/logoeatit.png" alt="EatIT Logo" style="float:left"/> </a>
			<h1> eatIT </h1>
		</div>
		
		<div class="navigation">
			<!-- Links naar andere paginas. -->
			<ul>
				<li> <a <?php echo ($page == "Index") ? "class='selected'" : ""; ?> href="index.php"> Home </a> </li>
				
				<?php
					//Laat verschillende links zien in navigatie afhankelijk van of iemand ingelogd is of niet.
					if (!isset($_SESSION["logged_in"])) {
						echo "<li> <a "; 
						echo ($page == "Login") ? "class='selected'" : "";
						echo " href='login.php'> Login </a> </li>";
					} else {
						echo "<li> <a "; 
						echo ($page == "Admin") ? "class='selected'" : "";
						echo " href='admin.php'> Controlepaneel </a> </li>";
					
						echo "<li> <a "; 
						echo ($page == "Login") ? "class='selected'" : "";
						echo " href='logout.php'> Logout </a> </li>";
					}
				?>
				
				<li> <a <?php echo ($page == "Registreren") ? "class='selected'" : ""; ?> href="registreren_klant.php"> Registreren </a> </li>
				<li> <a <?php echo ($page == "Bestellen") ? "class='selected'" : ""; ?> href="bestellen_maaltijd.php"> Bestellen </a> </li>
				<li> <a <?php echo ($page == "Contact") ? "class='selected'" : ""; ?> href="contact.php"> Contact </a> </li>
			</ul>
		</div>
		