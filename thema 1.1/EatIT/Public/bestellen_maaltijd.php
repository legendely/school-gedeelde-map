<?php
	//Includes en variabelen.
	include("../Includes/session.php");
	require_once("../Includes/Functions.php");
	$page = "Bestellen";
	include("../Includes//db_connect.php");
	include("../Includes/Layouts/header.php");
	
	if(!empty($_SESSION["bestelling"])) {
		$_SESSION["bestelling"] = NULL;
	}
	
	//Ophalen gegevens artikelen.
	$query = "SELECT * FROM artikel ORDER BY artikelnr";
	$artikelen = mysqli_query($connection, $query);
	if (!$artikelen) {
		DIE("Query Mislukt");
	}
	$aantal_artikelen = mysqli_num_rows($artikelen);
	
	//Als er op de submit knop gedrukt is.
	if (isset($_POST["submit"])) {
		//Opslaan klantcode in session.
		if(!empty($_POST["klantcode"])) {
			$_SESSION["klantcode"] = mysqli_real_escape_string($connection, $_POST["klantcode"]);
		} else {
			$_SESSION["errors"][] = "Vul uw klantcode in";
		}
	
		//Valideren van formulier
		if(empty($_POST["bestelling"])) {
			$_SESSION["errors"][] = "U heeft nog geen artikelen geselecteerd.";
		} else {
			foreach($_POST["aantal"] as $x) {
				if(!is_numeric($x)) {
					$_SESSION["errors"][] = "Vul bij aantal alleen getallen in";
					break;
				}
			}
			
			//Hij checkt alleen voorraad van eerste artikel, waarom??
			for($i=0; $i<=($aantal_artikelen)-1; $i++) {
				if(!empty($_POST["bestelling"][$i])) {
					$test_bst = $_POST["bestelling"][$i];
					$test_art = (int)$_POST["aantal"][$i];
					
					$check_voorraad = "SELECT * FROM artikel WHERE artikelnr={$test_bst}";
					$voorraad_result = mysqli_query($connection, $query);
					$xVoorraad = mysqli_fetch_assoc($voorraad_result);
					
					if($xVoorraad["voorraad"] < $test_art) {
						$_SESSION["errors"][] = "Niet genoeg voorraad";
						break;
					}
				}
				
				//Wel artikel geselecteerd maar geen aantal (of aantal is 0) ingvuld.
				if(!empty($_POST["bestelling"][$i]) && (empty($_POST["aantal"][$i]) || $_POST["aantal"][$i] <= 0)) {
					$_SESSION["errors"][] = "Vul het aantal in dat u wilt bestellen.";
					break;
				}
			}
		}
		
		//Als er geen errors zijn, opslaan in session en doorsturen naar bevestiging.
		if(empty($_SESSION["errors"])) {		
			//Opslaan bestelling in session.
			//Aantal artikelen = aantal rijen in artikel tabel.
			for($i=0; $i<=($aantal_artikelen)-1; $i++) {
				$aantal_esc = mysqli_real_escape_string($connection, $_POST["aantal"][$i]);
				if(!empty($_POST["bestelling"][$i])) {
					$_SESSION["bestelling"][] = array($_POST["bestelling"][$i], $aantal_esc);
				}	
			}
		
			header("Location: bevestigen_bestelling.php");
			exit();
		}
	}
	
?>

<!-- Content van pagina -->
<div id="content">
	<h2> Nieuwe Bestelling: </h2>
	
	<?php
		//Output eventuele foutmeldingen.
		if (!empty($_SESSION["errors"])) {
			echo print_errors($_SESSION["errors"]);
		}
	?>
	
	<table width="50%">
		<form action="" method="POST">
			<tr>
				<td> <b> Klantcode: </b> </td>
				<td> <input type="text" name="klantcode" 
					value="<?php echo empty($_POST["klantcode"]) ? "" : $_POST["klantcode"]; ?>"/> </td>
			</tr>
			
			<!-- Lege TR -->
			<tr><td>&nbsp;</td></tr>
			
			<?php
				echo "<th>Menu</th> <th>Voorraad</th> <th>Prijs</th> <th>Aantal</th>";
				$i=0;
				while ($artikel = mysqli_fetch_assoc($artikelen)) {
					echo "<tr>";
						echo "<td> <input type='checkbox' name='bestelling[$i]' 
							value='" . $artikel["artikelnr"] . "' ";
						
						if (!empty($_POST["bestelling"]) && in_array($artikel["artikelnr"], $_POST["bestelling"])) {
							echo "CHECKED";
						}
						
						echo " />" . $artikel["omschrijving"] . "</td>";
							
						echo "<td align='center'>" . $artikel["voorraad"] . "</td>";
						
						echo "<td align='center'>" . $artikel["verkoopprijs"] . "</td>";
						
						echo "<td align='center'> <input type='text' name='aantal[$i]' 
							value='0'/>";
					echo "</tr>";
				$i++;	
				}
			?>
			
			<!-- Lege TR -->
			<tr><td>&nbsp;</td></tr>
			
			<tr>
				<td> <input type="submit" name="submit" value="Bestellen"/> </td>
			</tr>
		</form>
	</table>
	
</div>

<?php
	//Vrijmaken resultaat en closen verbinding.
	mysqli_free_result($artikelen);
	mysqli_close($connection);
	
	//Include footer.
	include("../Includes/Layouts/footer.php");
?>