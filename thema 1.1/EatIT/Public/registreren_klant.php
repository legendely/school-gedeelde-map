<?php
	//Includes en variabelen.
	include("../Includes/session.php");
	include("../Includes/Functions.php");
	$page = "Registreren";
	include("../Includes/db_connect.php");
	include("../Includes/Layouts/header.php");
	
	//Registreren van de klant.
	if (isset($_POST["submit"])) {
	
		//Controleren van de input.
		if(empty($_POST["voornaam"])) {
			$_SESSION["errors"][] = "Vul uw voornaam in.";
		}
		
		if(empty($_POST["achternaam"])) {
			$_SESSION["errors"][] = "Vul uw achternaam in.";
		}
		
		if(empty($_POST["adres"])) {
			$_SESSION["errors"][] = "Vul uw adres in.";
		}
		
		if(empty($_POST["plaats"])) {
			$_SESSION["errors"][] = "Vul uw woonplaats in.";
		}
		
		//Nog controleren op geldig emailadres.
		if(empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
			$_SESSION["errors"][] = "Vul een geldig email adres in.";
		}
		
		//Kijk of er een geldig telefoonnummer is ingevuld (10 cijfers).
		if (empty($_POST["telefoonnr"]) || !check_max_len($_POST["telefoonnr"], 10) || !preg_match("/^(((\\+31|0|0031)6){1}[1-9]{1}[0-9]{7})$/i", $_POST["telefoonnr"])) {
			$_SESSION["errors"][] = "Vul uw tien cijferig (mobiel) telefoonnummer in.";
		}
		
		//Als er geen errors zijn, insert klant in database.
		if(empty($_SESSION["errors"])) {
			//Maken variabelen, TODO: Escapen.
			$voornaam 	= mysqli_real_escape_string($connection, $_POST["voornaam"]);
			$achternaam	= mysqli_real_escape_string($connection, $_POST["achternaam"]);
			$adres 		= mysqli_real_escape_string($connection, $_POST["adres"]);
			$plaats		= mysqli_real_escape_string($connection, $_POST["plaats"]);
			$email		= mysqli_real_escape_string($connection, $_POST["email"]);
			$telefoonnr = mysqli_real_escape_string($connection, $_POST["telefoonnr"]);
		
			//Aanmaken query
			$query = "INSERT INTO klant(voornaam, achternaam, adres, plaats, email, telefoonnummer) 
					  VALUES('{$voornaam}', '{$achternaam}', '{$adres}', '{$plaats}' ,'{$email}', '{$telefoonnr}');";
			
			//Uitvoeren query
			$insert = mysqli_query($connection, $query);
			if ($insert) {
				$_SESSION["message"][] = "Registratie gelukt, uw klantcode is: " . mysqli_insert_id($connection) . 
										 ". Bewaar deze goed, u heeft deze namelijk nodig bij het bestellen.";
				//Redirect naar de homepage.
				header("Location: index.php");
				exit();
			} else {
				DIE("Registreren mislukt." . mysqli_error($connection));
			}
		}
	}
?>

<!-- Content van pagina -->
<div id="content">
	<h2> Registreren: </h2>
	
	<?php
		//Output de foutmeldingen/berichten als die er zijn.
		if(!empty($_SESSION["errors"])) {
			echo print_errors($_SESSION["errors"]);
		}
	?>
	
	<table width="400">
		<form action="" method="POST">
			<!-- Splitsen in voor en achternaam -->
			<tr>
				<td> <b> Voornaam: </b> </td>
				<td> <input type="text" name="voornaam" value="<?php echo empty($_POST["voornaam"]) ? "" : $_POST["voornaam"]; ?>"/>* </td>
			</tr>
			
			<tr>
				<td> <b> Achternaam: </b> </td>
				<td> <input type="text" name="achternaam" value="<?php echo empty($_POST["achternaam"]) ? "" : $_POST["achternaam"]; ?>"/>* </td>
			</tr>
			
			<tr>
				<td> <b> Email: </b> </td>
				<td> <input type="text" name="email" value="<?php echo empty($_POST["email"]) ? "" : $_POST["email"]; ?>"/>* </td>
			</tr>
			
			<!-- Toevoegen van plaats -->
			<tr>
				<td> <b> Adres: </b> </td>
				<td> <input type="text" name="adres" value="<?php echo empty($_POST["adres"]) ? "" : $_POST["adres"]; ?>"/>* </td>
			</tr>
			
			<tr>
				<td> <b> Plaats: </b> </td>
				<td> <input type="text" name="plaats" value="<?php echo empty($_POST["plaats"]) ? "" : $_POST["plaats"]; ?>"/>* </td>
			</tr>
			
			<tr>
				<td> <b> Telefoonnummer: </b> </td>
				<td> <input type="text" name="telefoonnr" value="<?php echo empty($_POST["telefoonnr"]) ? "" : $_POST["telefoonnr"]; ?>"/>* </td>
			</tr>
			
			<tr>
				<td> </td>
				<td> <input type="submit" name="submit" value="Verzenden" onClick="return confirm('Weet U zeker dat deze gegevens kloppen?');"/> </td>
			</tr>
		</form>
	</table>
	
	<br/> Velden met een sterretje zijn verplicht.
	
</div>

<?php
	//Sluiten van de verbinding.
	mysqli_close($connection);

	//Include footer.
	include("../Includes/Layouts/footer.php");
?>