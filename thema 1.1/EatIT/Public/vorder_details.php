<?php
	//Includes en variabelen.
	include("../Includes/session.php");
	include("../Includes/Functions.php");
	$page = "Admin";
	include("../Includes/db_connect.php");
	include("../Includes/Layouts/header.php");
	
	//Controleer login status
	if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] == FALSE) {
		$_SESSION["errors"][] = "U moet eerst inloggen";
		header("Location: login.php");
	}
	
	//Redirect terug naar overzicht als bezoeker niet via GET is 
	//gekomen of geen id is ingevuld.
	if (!isset($_GET["id"]) || $_GET["id"] == "") {
		$_SESSION["errors"][] = "Selecteer eerst verkooporder om in te zien.";
		header("Location: verkooporders.php");
		exit();
	}
	
	$vordnr = mysqli_real_escape_string($connection, $_GET["id"]);
	
	//Ophalen gegevens order uit database - TODO: Functies van maken.
	$order_details = "SELECT * FROM verkooporder WHERE vOrdernr='{$vordnr}'";
	$order_result = mysqli_query($connection, $order_details);
	if(!$order_result) {
		DIE(mysqli_error($connection));
	}
	$verkooporder = mysqli_fetch_assoc($order_result);
	
	//Ophalen artikelen van order.
	$ord_artikelen = "SELECT * FROM verkooporderregel WHERE vOrdernr='{$vordnr}'";
	$artikelen_result = mysqli_query($connection, $ord_artikelen);
	if(!$artikelen_result) {
		DIE(mysqli_error($connection));
	}
	
	//Ophalen verschillende statussen.
	$status = "SELECT * FROM verstatus ORDER BY statuscode";
	$st_result = mysqli_query($connection, $status);
	if(!$st_result) {
		DIE(mysqli_error($connection));
	}
	
	//Kijk of formulier verzonden is, update dan de database.
	if(isset($_POST["submit"])) {
		//Aanmaak en uitvoer UPDATE query.
		$update = "UPDATE verkooporder SET statuscode={$_POST["orderstatus"]} WHERE vOrdernr='{$vordnr}'";
		$status_update = mysqli_query($connection, $update);
		if ($update && (mysqli_affected_rows($connection) >= 0)) {
			$_SESSION["message"][] = "Status Gewijzigd.";
			header("Location: verkooporders.php");
		} else {
			DIE("Wijzigen status mislukt: " . mysqli_error($connection));
		}
		
	}
	
?>

<div id="content">
	<h2> Details Verkooporder: </h2>
	
	<!-- Formulier voor verkooporders -->
	<table width="400">
		<form action="" method="POST">
			<tr>
				<td> <b> Klantcode: </b> </td>
				<td> <?php echo $verkooporder["klantcode"] ?> </td>
			</tr>
			
			<tr>
				<td> <b> Ordernummer: </b> </td>
				<td> <?php echo $verkooporder["vOrdernr"]; ?> </td>
			</tr>
			
			<tr>
				<td> <b> Afleveradres: </b> </td>
				<td> <?php echo $verkooporder["afleveradres"]; ?> </td>
			</tr>
			
			<!-- Lege TR -->
			<tr><td>&nbsp;</td></tr>
			
			<tr>
				<td> <b> Besteldatum: </b> </td>
				<td> <?php echo $verkooporder["besteldatum"]; ?> </td>
			</tr>
			
			<tr>
				<td> <b> Aflevertijd: </b> </td>
				<td> <?php echo $verkooporder["aflevertijd"]; ?> </td>
			</tr>
			
			<!-- Lege TR -->
			<tr><td>&nbsp;</td></tr>
			
			<tr>
				<?php
					while($artikelen = mysqli_fetch_assoc($artikelen_result)) {
						echo "<tr>";
						echo "<td>" . "<b> Artikelnr: </b>" . $artikelen["artikelnr"] . "</td>";
						echo "<td>" . "<b> Aantal: </b>" . $artikelen["aantal"] . "</td>";
						echo "</tr>";
					}
				?>
			</tr>
			
			<tr>
				<td> <b> Totaalprijs </b> </td>
				<td> <?php echo "&euro;" . $verkooporder["totaalprijs"]; ?>
			</tr>
			
			<!-- Lege TR -->
			<tr><td>&nbsp;</td></tr>
			
			<tr>
				<td>
					<b> Status: </b>
					<select name="orderstatus"> 
						<?php
							while ($orderstatus = mysqli_fetch_assoc($st_result)) {
								echo "<option value='{$orderstatus["statuscode"]}'";
								//Vul manager automatisch in en onthoud keuze.
								if ($verkooporder["statuscode"] == $orderstatus["statuscode"]) {
									echo "SELECTED";
								}
								echo ">";
								echo $orderstatus["omschrijving"];
								echo "</option>";
							}
						?>
					</select>
				</td>
				
				<td>
					<input type="submit" name="submit" value="Verzenden"/>
				</td>
			</tr>
			
		</form>
	</table>
	
	<br/> <br/> <a href="verkooporders.php" style="color:blue"> Terug <a/>
</div>















<?php
	mysqli_free_result($order_result);
	mysqli_free_result($artikelen_result);
	mysqli_free_result($st_result);
	mysqli_close($connection);
	
	//Include footer.
	include("../Includes/Layouts/footer.php");
?>