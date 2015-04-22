<?php
/* Geschreven door:			Thijs Kuilman
 * Studentnummer:					327154
 *
 * Doel van dit bestand:
 * Deze pagina bevat een formulier waarmee een gebruiker zijn persoonlijke gegevens kan veranderen. Naast account instellingen kun je ook je wachtwoord aanpassen.
 */
if(!isset($_SESSION['gegevens'])){
    header ('location: index.php');
}

// Een functie die ervoor zorgt dat de Klantgegevens worden aangepast in de database
function update_klant($name, $email, $con, $newvalue){
	// De update query
	$query = "UPDATE Klant SET " . $name . "= '" . $newvalue . "' WHERE KL_Mail='" . $email . "'";
	$result = mysqli_query($con, $query);

	// Aangeven dat de aanpassingen succesvol zijn
	echo '<center><div class="success">' . ucfirst($name) . ' is succesvol aangepast!</div></center>';
}

// Een functie die ervoor zorgt dat de Medewerkersgegevens worden aangepast in de database
function update_med($name, $email, $con, $newvalue){
	// De update query
	$query = "UPDATE Medewerkers SET " . $name . "= '" . $newvalue . "' WHERE MED_Mail='" . $email . "'";
	$result = mysqli_query($con, $query);

	// Aangeven dat de aanpassingen succesvol zijn
	echo '<center><div class="success">' . ucfirst($name) . ' is succesvol aangepast!</div></center>';
}

// Als er een klant is ingelogd, dan krijgt hij/zij het volgende te zien:
if($_SESSION['soortgebruiker'] == "klant"){
	// print_r($gegevens);
	if(isset($_POST['submit_settings'])){
		// Kijken of de variables zijn invuld. Als er een onderdeel is ingevoerd, dan wordt de functie update_klant uitgevoerd en worden er hierbij parameters meegegeven. Deze functie
		// zorgt ervoor dat de data wordt aangepast in de database.
		if ($_POST['email'] != '') {
			$email = $_POST['email'];
			update_klant('KL_Mail', $gegevens['KL_Mail'], $con, $email);
		}

		if ($_POST['voornaam'] != '') {
			$voornaam = $_POST['voornaam'];
			update_klant('KL_Voornaam', $gegevens['KL_Mail'], $con, $voornaam);
		}

		if ($_POST['achternaam'] != '') {
			$achternaam = $_POST['achternaam'];
			update_klant('KL_Achternaam', $gegevens['KL_Mail'], $con, $achternaam);
		}

		if ($_POST['telefoonnummer'] != '') {
			$telefoonnummer = $_POST['telefoonnummer'];
			update_klant('KL_Telefoonnummer', $gegevens['KL_Mail'], $con, $telefoonnummer);
		}

		if ($_POST['plaats'] != '') {
			$plaats = $_POST['plaats'];
			update_klant('KL_Plaats', $gegevens['KL_Mail'], $con, $plaats);
		}

		if ($_POST['adres'] != '') {
			$adres = $_POST['adres'];
			update_klant('KL_Adres', $gegevens['KL_Mail'], $con, $adres);
		}

		if ($_POST['postcode'] != '') {
			$postcode = $_POST['postcode'];
			update_klant('KL_Postcode', $gegevens['KL_Mail'], $con, $postcode);
		}

		// Wachtwoord wijzigen. Dit heeft extra voorwaardes nodig. Zo moet het oude wachtwoord kloppen en moet het nieuwe wachtwoord twee keer identiek worden ingevuld.
		if(isset($_POST['wachtwoord'])){
			if( $_POST['wachtwoord'] != ''){
				if(strlen($_POST['password_new']) > 5){
					if ($_POST['wachtwoord'] == $gegevens['KL_Wachtwoord']) {
						if($_POST['password_new2'] == $_POST['password_new']){
							// Verander het KL_Wachtwoord in de database
							$query = "UPDATE Klant SET KL_Wachtwoord= '" . $_POST['password_new'] . "' WHERE KL_Mail='" . $gegevens['KL_Mail'] . "'";
							echo '<br><br>';
							
							$result = mysqli_query($con, $query);

							// Een melding van alle aanpassingen
							echo '<center><div class="success">Wachtwoord is succesvol aangepast!</div></center>';
						}
					}
				}
			}
		}
	}		
?>

	<!-- De content. Hier komt alle inhoud van de site. -->
	<div class="content">
		<?php
		// Hier wordt gekeken of er bij het aanvragen van een nieuw wachtwoord errors optraden. Als dit het geval is, dan wordt de error op het scherm weergegeven.
	    if(isset($_POST['wachtwoord'])){
			if( $_POST['wachtwoord'] != ''){
		    	if(strlen($_POST['password_new']) < 5){
		    		echo '<center><div class="error"> Het nieuwe wachtwoord moet meer dan 5 tekens bevatten.</div></center>';
		    	}

		    	if($_POST['password_new2'] != $_POST['password_new']){
					echo '<center><div class="error"> Vul uw nieuwe wachtwoord twee keer identiek in.</div></center>';
				}

				if ($_POST['wachtwoord'] != $gegevens['KL_Wachtwoord']) {
					echo '<center><div class="error"> Het oude wachtwoord klopt niet.</div></center>';
				}
			}
	    }
		?>
		<!-- Het formulier -->
		<h2>Persoonlijke gegevens</h2>
		<!-- Alle invoervelden voor het wijzigen van de persoonlijke gegevens. Niets is required, dus velden kunnen ook worden overgeslagen als ze hetzelfde moeten blijven. -->
		<form class="form-signin" name="changedata" method="post" action="?p=instellingen">
		Email:<br><input type="email" name="email" class="invoerveld" placeholder= <?php echo '"' . $gegevens['KL_Mail'] . '"'; ?>><br><br>
		Voornaam:<br><input type="text" class="invoerveld" name="voornaam" placeholder=<?php echo '"' . $gegevens['KL_Voornaam'] . '"'; ?>><br><br>
		Achternaam:<br><input type="text" class="invoerveld" name="achternaam" placeholder=<?php echo '"' . $gegevens['KL_Achternaam'] . '"'; ?>><br><br>
		Telefoonnummer:<br><input type="number" class="invoerveld" name="telefoonnummer" placeholder=<?php echo '"' . $gegevens['KL_Telefoonnummer'] . '"'; ?>><br><br>
		Plaats:<br><input type="text" class="invoerveld" name="plaats" placeholder=<?php echo '"' . $gegevens['KL_Plaats'] . '"'; ?>><br><br>
		Adres + Huisnummer:<br><input type="text" class="invoerveld" name="adres" placeholder=<?php echo '"' . $gegevens['KL_Adres'] . '"'; ?>><br><br>
		Postcode:<br><input type="text" class="invoerveld" name="postcode" placeholder=<?php echo '"' . $gegevens['KL_Postcode'] . '"'; ?>><br><br>
			
		<!-- Hier kunnen de gebruikers hun wachtwoord veranderen. Het wijzigingsproces wordt ALLEEN geactiveerd als het eerste veld hiervan wordt ingevuld. -->
		<h2>Wachtwoord wijzigen</h2>
		Voer je huidige wachtwoord in:<br><input type="password" class="invoerveld" name="wachtwoord"><br><br>
		Voer je gewenst wachtwoord twee keer in:<br><input type="password" class="invoerveld" name="password_new"><br>
		<br><input type="password" class="invoerveld" name="password_new2"><br><br>

		<!-- Het formulier verzenden. -->
		<button type="submit" name="submit_settings" class="submit">Wijzigingen opslaan</button>
		</form>
	</div>
<?php } ?>

<?php
// Als er een klant is ingelogd, dan krijgt hij/zij het volgende te zien:

if($_SESSION['soortgebruiker'] == "medewerker"){
	if(isset($_POST['submit_settings'])){

		// Kijken of de variables zijn invuld. Als er een onderdeel is ingevoerd, dan wordt de functie update_med uitgevoerd en worden er hierbij parameters meegegeven. Deze functie
		// zorgt ervoor dat de data wordt aangepast in de database.
		if ($_POST['email'] != '') {
			$email = $_POST['email'];
			update_med('MED_Mail', $gegevens['MED_Mail'], $con, $email);
		}

		if ($_POST['voornaam'] != '') {
			$voornaam = $_POST['voornaam'];
			update_med('MED_Voornaam', $gegevens['MED_Mail'], $con, $voornaam);
		}

		if ($_POST['achternaam'] != '') {
			$achternaam = $_POST['achternaam'];
			update_med('MED_Achternaam', $gegevens['MED_Mail'], $con, $achternaam);
		}

		if ($_POST['telefoonnummer'] != '') {
			$telefoonnummer = $_POST['telefoonnummer'];
			update_med('MED_Telefoonnummer', $gegevens['MED_Mail'], $con, $telefoonnummer);
		}

		if ($_POST['plaats'] != '') {
			$plaats = $_POST['plaats'];
			update_med('MED_Plaats', $gegevens['MED_Mail'], $con, $plaats);
		}

		if ($_POST['adres'] != '') {
			$adres = $_POST['adres'];
			update_med('MED_Adres', $gegevens['MED_Mail'], $con, $adres);
		}

		if ($_POST['postcode'] != '') {
			$postcode = $_POST['postcode'];
			update_med('MED_Postcode', $gegevens['MED_Mail'], $con, $postcode);
		}

		// Wachtwoord wijzigen. Dit heeft extra voorwaardes nodig. Zo moet het oude wachtwoord kloppen en moet het nieuwe wachtwoord twee keer identiek worden ingevuld.
		if(isset($_POST['wachtwoord'])){
			if( $_POST['wachtwoord'] != ''){
				if(strlen($_POST['password_new']) > 5){
					if ($_POST['wachtwoord'] == $gegevens['MED_Wachtwoord']) {
						if($_POST['password_new2'] == $_POST['password_new']){
							// Verander het MED_Wachtwoord in de database
							$query = "UPDATE Medewerkers SET MED_Wachtwoord= '" . $_POST['password_new'] . "' WHERE MED_Mail='" . $gegevens['MED_Mail'] . "'";
							echo '<br><br>';
							
							$result = mysqli_query($con, $query);

							// Een melding van alle aanpassingen
							echo '<center><div class="success">Wachtwoord is succesvol aangepast!</div></center>';
						}
					}
				}
			}
		}
	}	
?>

	<!-- De content. Hier komt alle inhoud van de site. -->
	<div class="content">
		<?php
		// Hier wordt gekeken of er bij het aanvragen van een nieuw wachtwoord errors optraden. Als dit het geval is, dan wordt de error op het scherm weergegeven.
	    if(isset($_POST['wachtwoord'])){
			if( $_POST['wachtwoord'] != ''){
		    	if(strlen($_POST['password_new']) < 5){
		    		echo '<center><div class="error"> Het nieuwe wachtwoord moet meer dan 5 tekens bevatten.</div></center>';
		    	}

		    	if($_POST['password_new2'] != $_POST['password_new']){
					echo '<center><div class="error"> Vul uw nieuwe wachtwoord twee keer identiek in.</div></center>';
				}

				if ($_POST['wachtwoord'] != $gegevens['MED_Wachtwoord']) {
					echo '<center><div class="error"> Het oude wachtwoord klopt niet.</div></center>';
				}
			}
	    }
		?>
		<!-- Het formulier -->
		<h2>Persoonlijke gegevens Medewerker</h2>
		<!-- Alle invoervelden voor het wijzigen van de persoonlijke gegevens. Niets is required, dus velden kunnen ook worden overgeslagen als ze hetzelfde moeten blijven. -->
		<form class="form-signin" name="changedata" method="post" action="?p=instellingen">
		Email:<br><input type="email" name="email" class="invoerveld" placeholder= <?php echo '"' . $gegevens['MED_Mail'] . '"'; ?>><br><br>
		Voornaam:<br><input type="text" class="invoerveld" name="voornaam" placeholder=<?php echo '"' . $gegevens['MED_Voornaam'] . '"'; ?>><br><br>
		Achternaam:<br><input type="text" class="invoerveld" name="achternaam" placeholder=<?php echo '"' . $gegevens['MED_Achternaam'] . '"'; ?>><br><br>
		Telefoonnummer:<br><input type="number" class="invoerveld" name="telefoonnummer" placeholder=<?php echo '"' . $gegevens['MED_Telefoonnummer'] . '"'; ?>><br><br>
		Plaats:<br><input type="text" class="invoerveld" name="plaats" placeholder=<?php echo '"' . $gegevens['MED_Plaats'] . '"'; ?>><br><br>
		Adres:<br><input type="text" class="invoerveld" name="adres" placeholder=<?php echo '"' . $gegevens['MED_Adres'] . '"'; ?>><br><br>
		Postcode:<br><input type="text" class="invoerveld" name="postcode" placeholder=<?php echo '"' . $gegevens['MED_Postcode'] . '"'; ?>><br><br>
			
		<!-- Hier kunnen de gebruikers hun wachtwoord veranderen. Het wijzigingsproces wordt ALLEEN geactiveerd als het eerste veld hiervan wordt ingevuld. -->
		<h2>Wachtwoord wijzigen</h2>
		Voer je huidige wachtwoord in:<br><input type="password" class="invoerveld" name="wachtwoord"><br><br>
		Voer je gewenst wachtwoord twee keer in:<br><input type="password" class="invoerveld" name="password_new"><br>
		<br><input type="password" class="invoerveld" name="password_new2"><br><br>

		<!-- Het formulier verzenden. -->
		<button type="submit" name="submit_settings" class="submit">Wijzigingen opslaan</button>
		</form>
	</div>
<?php } ?>