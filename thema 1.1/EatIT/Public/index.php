<?php
	//Includes en variabelen.
	include("../Includes/session.php");
	include("../Includes/Functions.php");
	$page = "Index";
	include("../Includes/Layouts/header.php");
?>

<!-- Content van pagina -->
<div id="content">
	<h2> Welkom bij EatIT </h2>
	
	<?php
		//Output eventuele berichten.
		if (!empty($_SESSION["message"])) {
			echo print_msg($_SESSION["message"]);
		}
	?>
	

	
	Het menu voor deze week is:
	<ul>
		<li> ..... </li>
		<li> ...... </li>
		<li> ....... </li>
	</ul> <br/>
	
	Na een &eacute;&eacute;nmalige registratie kunt u beginnen met bestellen.
	
</div>

<?php
	//Include footer.
	include("../Includes/Layouts/footer.php");
?>