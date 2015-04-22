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

//Alleen beheerders hebben toegang tot deze pagina. Geen beheerder: terugsturen naar index.php
if($gegevens['Afdeling'] != '1'){
    header ('location: index.php');
}

// Dit gebeurd er wanner iemand's afdeling wordt veranderd via het formulier.
if(isset($_POST['change_department'])){
	// Kijken of de variables zijn invuld. Als er een onderdeel is ingevoerd, dan wordt de functie update_data uitgevoerd en worden er hierbij parameters meegegeven. Deze functie
	// zorgt ervoor dat de data wordt aangepast in de database.
		update_department('Afdeling', $_POST['email'], $con);
}	

// Een functie die ervoor zorgt dat de klantgegevens worden aangepast in de database
	function update_department($name, $email, $con){
	// De update query
	$query = "UPDATE medewerkers SET Afdeling = '" . $_POST['afdeling'] . "' WHERE MED_Mail='" . $email . "'";
	$result = mysqli_query($con, $query);

	// Een melding van alle aanpassingen
	echo '<center><div class="success">' . ucfirst($name) . ' is succesvol aangepast!</div></center>';
}

// Dit gebeurd er wanneer iemand's manager ID wordt aangepast via het formulier
if(isset($_POST['change_manager'])){
	// Kijken of de variables zijn invuld. Als er een onderdeel is ingevoerd, dan wordt de functie update_data uitgevoerd en worden er hierbij parameters meegegeven. Deze functie
	// zorgt ervoor dat de data wordt aangepast in de database.
		update_manager('Manager', $_POST['email'], $con);
}	

// Een functie die ervoor zorgt dat de klantgegevens worden aangepast in de database
function update_manager($name, $email, $con){
	// De update query
	$query = "UPDATE medewerkers SET Manager_ID = '" . $_POST['manager'] . "' WHERE MED_Mail='" . $email . "'";
	$result = mysqli_query($con, $query);
	// Een melding van alle aanpassingen
	echo '<center><div class="success">' . ucfirst($name) . ' is succesvol aangepast!</div></center>';
}

// Dit gebeurd er wanneer iemand op inactief wordt gezet. De afdeling wordt op NULL gezet, want we willen de werknemer voor de toekomst natuurlijk wel bewaren.
if(isset($_POST['change_activity'])){
	// Kijken of de variables zijn invuld. Als er een onderdeel is ingevoerd, dan wordt de functie update_data uitgevoerd en worden er hierbij parameters meegegeven. Deze functie
	// zorgt ervoor dat de data wordt aangepast in de database.
	update_activity('Activiteit', $_POST['email'], $con);
}	
		
// Een functie die ervoor zorgt dat de klantgegevens worden aangepast in de database
function update_activity($name, $email, $con){
	// De update query
	$query = "UPDATE medewerkers SET Afdeling = NULL WHERE MED_Mail='" . $email . "'";
	$result = mysqli_query($con, $query);

	// Een melding van alle aanpassingen
	echo '<center><div class="success">' . ucfirst($name) . ' is succesvol aangepast!</div></center>';
}
?>

<!-- De content. Hierin komt alles dat de gebruiker op de pagina ziet. -->
<div class="content">
	<h2>Nieuwe medewerker toevoegen</h2>
	<a href="?p=register_medewerker">Klik hier om een nieuwe medewerker toe te voegen.</a><br><br>

	<!-- Het formulier -->
	<h2>Afdeling wijzigen</h2>
		<!-- Hier kan de afdeling van een werknemer gewijzigd worden. -->
		<form class="form-signin" name="changedata" method="post" action="?p=beheerder">
			Email:<br><input type="email" class="invoerveld" placeholder="Email van gebruiker" name="email" required><br><br>
			Verplaatsen naar afdeling:<br>
				<select class="invoerveld" name="afdeling" required>
		            <option value="1">Directie</option>
		            <option value="2">Expeditie</option>
		            <option value="3">Administratie</option>
		            <option value="4">Financiële administratie</option>
		            <option value="5">Personeelsadministratie</option>
		            <option value="6">Keuken</option>
		            <option value="7">Inkoop</option>
		            <option value="8">Verkoop</option>
		        </select>
			<br><br>

			<!-- Het formulier verzenden. -->
			<button type="submit" name="change_department" class="submit">Wijzingen opslaan</button>
		</form>


	<!-- Het formulier -->
	<h2>Manager wijzigen</h2>
		<!-- Hier kan de afdeling van een werknemer gewijzigd worden. -->
		<form class="form-signin" name="changedata" method="post" action="?p=beheerder">
			Email:<br><input type="email" class="invoerveld" placeholder="Email van gebruiker" name="email" required><br><br>
			Manager ID geven:<br><input type="number" class="invoerveld" name="manager" required><br><br>

			<!-- Het formulier verzenden. -->
			<button type="submit" name="change_manager" class="submit">Wijzingen opslaan</button>
			<br><br>
	</form>

	<!-- Als je een medewerker op inactief zet, dan kunnen ze niet meer inloggen. Dit wordt gedaan door de afdeling op NULL te zetten. We verwijderen de medewerker niet permanent, omdat de gegevens later van pas kunnen komen. -->
	<h2>Medewerker op inactief zetten</h2>
		<!-- Hier kan de afdeling van een werknemer gewijzigd worden. -->
		<form class="form-signin" name="changedata" method="post" action="?p=beheerder">
		Email:<br><input type="email" class="invoerveld" placeholder="Email van gebruiker" name="email" required><br><br>
		<!-- Het formulier verzenden. -->
		<button type="submit" name="change_activity" class="submit">Wijzingen opslaan</button>
	</form>

	<!-- Een lijst met alle actieve medewerkers weergeven -->
	<h2>Actieve Medewerkers</h2>
	<?php

	// Medewerkers uit de database halen. Ze moeten altijd in een afdeling zitten.
	$query = "SELECT * from medewerkers WHERE Afdeling IS NOT NULL";
	$result = mysqli_query($con, $query);

	// De tabel met gebruikers weergeven
	echo '<table style="width:100%">';
	echo '<tr><td><b>Email</b></td><td><b>Voornaam</b></td><td><b>Achternaam</b></td><td><b>Adres</b></td><td><b>Telefoonnr</b></td><td><b>Postcode</b></td><td><b>Afdeling</b></td><td><b>Manager_ID</b></td>';
	
	while($row = mysqli_fetch_array($result)) {
		echo "<tr><td>" . $row['MED_Mail'] . "</td><td>" . $row['MED_Voornaam']. "</td><td>" . $row['MED_Achternaam']. "</td><td>" . $row['MED_Adres']. "</td><td>" . $row['MED_Telefoonnummer']. "</td><td>" . $row['MED_Postcode']  . '</td><td>'. $row['Afdeling'] . '</td><td>'. $row['Manager_ID']. "</td><tr>";
	}
	
	echo '</table>';
	?>

	<!-- Een lijst met inactieve gebruikers opstellen. -->
	<h2>Inactieve Medewerkers</h2>
	<?php

	// Medewerkers uit de database halen. Ze moeten altijd in een afdeling zitten.
	$query = "SELECT * from medewerkers WHERE Afdeling IS NULL";
	$result = mysqli_query($con, $query);

	// De tabel met gebruikers weergeven
	echo '<table style="width:100%">';
	echo '<tr><td><b>Email</b></td><td><b>Voornaam</b></td><td><b>Achternaam</b></td><td><b>Adres</b></td><td><b>Telefoonnr</b></td><td><b>Postcode</b></td><td><b>Manager_ID</b></td>';
	
	while($row = mysqli_fetch_array($result)) {
		echo "<tr><td>" . $row['MED_Mail'] . "</td><td>" . $row['MED_Voornaam']. "</td><td>" . $row['MED_Achternaam']. "</td><td>" . $row['MED_Adres']. "</td><td>" . $row['MED_Telefoonnummer']. "</td><td>" . $row['MED_Postcode']  . '</td><td>'. $row['Manager_ID']. "</td><tr>";
	}
	
	echo '</table>';
	?>
</div>