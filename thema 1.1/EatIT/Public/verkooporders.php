<?php
	//Includes en variabelen.
	include("../Includes/session.php");
	include("../Includes/Functions.php");
	$page = "Admin";
	include("../Includes/db_connect.php");
	include("../Includes/Layouts/header.php");
	
	if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] == FALSE) {
		$_SESSION["errors"][] = "U moet eerst inloggen";
		header("Location: login.php");
	}
	
	//Ophalen verkooporders uit database.
	$query = "SELECT * FROM verkooporder ORDER BY vOrdernr";
	$vOrder_result = mysqli_query($connection, $query);
	if(!$vOrder_result) {
		DIE(mysqli_error($connection));
	}
	
?>

<!-- Content van pagina -->
<div id="content">
	<h2> Overzicht Verkooporders: </h2>
	
	
	<?php
		//Toon eventuele berichten/errors.
		if (!empty($_SESSION["message"])) {
			echo print_msg($_SESSION["message"]);
		}
		
		if (!empty($_SESSION["errors"])) {
			echo print_errors($_SESSION["errors"]);
		}
	?>
	
	<table width="90%">
		<th> <b> Ordernr </b> </th>
		<th> <b> Klantcode </b> </th>
		<th> <b> Besteldatum </b> </th>
		<th> <b> Aflevertijd </b> </th>
		<th> <b> Totaalprijs (&euro;) </b> </th>
		<th> <b> Afleveradres </b> </th>
		<th> <b> Statuscode </b> </th>
		
		<?php			
			//Aanmaken rest van de tabel.
			while ($vOrder = mysqli_fetch_assoc($vOrder_result)) {
				echo "<tr align='center'>";
				foreach($vOrder as $field) {
					echo "<td>" . $field . "</td>";
				}
					
				//Link naar edit pagina, stuur medewerker id via GET.
				echo "<td>" . "<a href='vorder_details.php?id={$vOrder["vOrdernr"]}' style='color:blue'> Edit </a>" . "</td>";
				echo "</tr>";
			}
		?>
	</table>
	
</div>

<?php
	mysqli_free_result($vOrder_result);
	mysqli_close($connection);
	
	//Include footer.
	include("../Includes/Layouts/footer.php");
?>