<?php
	//Includes en variabelen.
	include("../Includes/session.php");
	require_once("../Includes/Functions.php");
	$page = "Bestellen";
	include("../Includes//db_connect.php");
	include("../Includes/Layouts/header.php");
	
	//Ophalen artikelen uit database.
	$query = "SELECT * FROM artikel ORDER BY artikelnr";
	$artikel_result = mysqli_query($connection, $query);
	if (!$artikel_result) {
		DIE("Query mislukt, probeer opnieuw.");
	}
	
	//Ophalen gegevens klant uit database.
	$query = "SELECT * FROM klant WHERE klantcode={$_SESSION["klantcode"]}";
	$klant_result = mysqli_query($connection, $query);
	if(!$klant_result) {
		DIE("Query Mislukt");
	}
	$klant = mysqli_fetch_assoc($klant_result);
	
	if(!$klant) {
		$_SESSION["errors"][] = "Klantcode bestaat niet.";
		header("Location: bestellen_maaltijd.php");
		exit();
	}
	
	//Order inserten in inkooporder en inkooporderregel.
	if(isset($_POST["bevestig"])) {
		
		//Valideren van formulier.
		if(empty($_POST["afleveradres"]) || empty($_POST["plaats"])) {
			$_SESSION["errors"][] = "Vul uw adres en woonplaats in.";
		}
		
		
		//Als er geen errors zijn voor de queries uit.
		if(empty($_SESSION["errors"])) {
			//Variabelen voor datum/tijd
			$besteldatum 	= date("Y-m-d H:i:s", strtotime("+0 minutes"));
			$afleverdatum 	= date("Y-m-d H:i:s", strtotime("+30 minutes"));
		
			$query = "INSERT INTO verkooporder(klantcode, besteldatum, aflevertijd, totaalprijs, afleveradres, statuscode)
					  VALUES({$klant["klantcode"]},
							 '{$besteldatum}',
							 '{$afleverdatum}',
							 {$_POST["totaalprijs"]},
							 '{$_POST["afleveradres"]} {$_POST["plaats"]}',
							 1)";
			$insert_ord = mysqli_query($connection, $query);
			
			//Als insert gelukt is.
			if($insert_ord) {
				//Verkrijg het verkoopordernr dat net is ingevoerd.
				$vOrdernr = mysqli_insert_id($connection);
								
				//Insert elk artikel in de verkooporderregel.
				foreach($_SESSION["bestelling"] as $vOrdregel) {
					//vOrdegel[0] = Art nr. vOrdregel[1] = Aantal besteld.
					$vregel_query 	= "INSERT INTO verkooporderregel(vOrdernr, artikelnr, aantal)
									 VALUES({$vOrdernr}, {$vOrdregel[0]}, {$vOrdregel[1]});";
					$insert_vregel 	= mysqli_query($connection, $vregel_query);
					
					//Update de "in bestelling van elk ingredient van een artikel"
					$test = "SELECT * FROM recept WHERE artikelnr = {$vOrdregel[0]};";
					$recept_result = mysqli_query($connection, $test);
					while ($recept = mysqli_fetch_assoc($recept_result)) {
						$oud_GR = "SELECT GR FROM ingredienten WHERE ingrednr={$recept["ingrednr"]};";
						$GR_result = mysqli_query($connection, $oud_GR);
						$GR = mysqli_fetch_assoc($GR_result);
						
						$update_aantal = ($vOrdregel[1]*$recept["aantal"]) + $GR["GR"];
						$query1 = "UPDATE ingredienten SET GR={$update_aantal} WHERE ingrednr={$recept["ingrednr"]} LIMIT 1;";
						$update_ingred = mysqli_query($connection, $query1);
						if(!$update_ingred) {
							DIE(mysqli_error());
						}
					}					
				}
				
				//Bestelling compleet, toon boodschap en zet session variabelen op null.
				$_SESSION["message"][] = "Bestelling geplaatst, ongeveer om {$afleverdatum} wordt het bij u bezorgd";
				$_SESSION["klantcode"]  = NULL;
				$_SESSION["bestelling"] = NULL;
				header("Location: index.php");
			} else {
				DIE("Insert Mislukt" . mysqli_error($connection));
			}
		}
	}
	
?>

<div id="content">
	<h2> Bevestigen bestelling: </h2>
	
	<?php
		//Output eventuele foutmeldingen.
		if (!empty($_SESSION["errors"])) {
			echo print_errors($_SESSION["errors"]);
		}
	?>
	
	<table width="50%">
		<form action="" method="POST">
			<tr>
				<td> <b> Naam: </b> </td>
				<td> <?php echo $klant["voornaam"] . " " . $klant["achternaam"]; ?>
				<td> <b> Klantcode <b/> </td>
				<td> <?php echo $klant["klantcode"]; ?> </td>
			</tr>
			
			<tr>
				<td> <b> Afleveradres: </b> </td>
				<td> <input type="text" name="afleveradres" value="<?php echo $klant["adres"]; ?>"
			</tr>
			
			<tr>
				<td> <b> Plaats: </b> </td>
				<td> <input type="text" name="plaats" value="<?php echo $klant["plaats"]; ?>"
			</tr>
			
			<tr>
				<td> <b> Bestelling: </b> </td>
				<td>
					<?php
						//Laat overzicht zien van de bestelling.
						//Berekent ook de totaalprijs van de bestelling.
						$totaalprijs = 0;
						$orderprijs = 0;
						while ($artikel = mysqli_fetch_assoc($artikel_result)) {
							foreach($_SESSION["bestelling"] as $orders) {
								if(in_array($artikel["artikelnr"], $orders)) {
									echo "<tr>";
									echo "<td>" . $artikel["omschrijving"] . "</td>";
									echo "<td> <b> Aantal </b> </td>" . "<td> $orders[1] </td>";
									
									$orderprijs = ($artikel["verkoopprijs"] * $orders[1]);
									$totaalprijs += $orderprijs;
									
									echo "<td> <b> Prijs </b> </td>" . "<td> &euro; $orderprijs </td>";
									echo "</tr>";
								}
							}
						}
					?>
					<input type="hidden" name="totaalprijs" value="<?php echo $totaalprijs; ?>"/>
				</td>
			</tr>
			
			<!-- Lege TR -->
			<tr><td>&nbsp;</td></tr>
			
			<tr>
				<td> <b> Totaalprijs: </b> </td>
				<td> <?php echo "&euro;" . $totaalprijs; ?> </td>
			</tr>
			
			<tr>
				<td></td>
				<td> <input type="submit" name="bevestig" value="Bevestig Bestelling"  onClick="return confirm('Weet U zeker dat deze gegevens kloppen?');"/>
			</tr>
		</form>
	</table>	
</div>

<?php
	//Free de results en sluit verbinding.
	mysqli_free_result($artikel_result);
	mysqli_free_result($klant_result);
	mysqli_free_result($recept_result);
	mysqli_free_result($insert_ord);
	
	//Include footer.
	include("../Includes/Layouts/footer.php");
?>
