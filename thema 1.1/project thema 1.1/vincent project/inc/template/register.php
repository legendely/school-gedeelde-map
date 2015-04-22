<?php
/* Geschreven door:			Thijs Kuilman
 * Studentnummer:					327154
 *
 * Doel van dit bestand:
 * Deze pagina bevat een formulier waarmee een persoon zich kan registreren op de site.
 */

//Als een gebruiker al ingelogd is, wordt het doorverwezen naar index.php
if(isset($_SESSION['gegevens'])) {
    header('location: index.php');
}

// De variabelen die gebruikt worden voor het systeem
$emailcheck = 0;
$voltooid = 0;
$secretcode = "";

// Kijken of de variables zijn invuld. Zoja: zet de postdata om naar de variabelen. Dit is puur om de query straks iets netter te maken.
$email = isset($_POST['email']) ? $_POST['email'] : "";
$voornaam = isset($_POST['voornaam']) ? $_POST['voornaam'] : "";
$achternaam = isset($_POST['achternaam']) ? $_POST['achternaam'] : "";
$telefoonnummer = isset($_POST['telefoonnummer']) ? $_POST['telefoonnummer'] : "";
$plaats = isset($_POST['plaats']) ? $_POST['plaats'] : "";
$adres = isset($_POST['adres']) ? $_POST['adres'] : "";
$postcode = isset($_POST['postcode']) ? $_POST['postcode'] : "";


// Het account aanmaken zodra er op het knopje is geklikt.
if(isset($_POST['submit'])){
	// Kijken of het email adres al in de database staat. Dit wordt gedaan door het ingevulde email adres m.b.v een WHERE en een COUNT statement te tellen. Als er 0 zijn, dan kan de persoon zich registeren. Als het op 1 staat, dan volgt er een error.
	$query = "SELECT count(KL_Mail) FROM Klant WHERE KL_Mail = '" . $email . "' ";
	$result = mysqli_query($con, $query);


	while($row = mysqli_fetch_array($result)) {
		$emailcheck = $row['count(KL_Mail)'];
	}

	// Als het email adres niet gevonden is, dan worden de gegevens in de database gezet
	if($emailcheck == 0){
		// Een inlogcode (oftewel: wachtwoord) genereren
		// Uit deze letters kan het script kiezen
		$letters = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		// De variabel die de gegenereerde code opslaat 
		$secretcode = '';

		// Een loop die telkens een willekeurige positie van $letters pakt en dit aan de code toevoegt
		for ($i=0; $i < 10; $i++) { 
			$secretcode .= $letters[rand(0,strlen($letters) - 1)];
		}

		// De geheime code door een hash halen, waardoor het nog moeilijker te kraken is
		$secretcode = hash('crc32b', $secretcode);

		// De gegevens invullen
		$query = "INSERT INTO Klant(KL_Mail, KL_Voornaam, KL_Achternaam, KL_Telefoonnummer, KL_Plaats, KL_Adres, KL_Postcode, KL_Wachtwoord) VALUES ( '" . $email . "', '" . $voornaam . "'";
		$query .= ", '" . $achternaam . "', '" . $telefoonnummer . "', '" . $plaats . "', '" . $adres . "', '" . $postcode . "', '" . $secretcode . "')";
		$result = mysqli_query($con, $query);

		$voltooid = 1;
	}
}

?>

<!-- Het formulier waarin de invoervelden staan om je te registreren -->
<div class="content">
	<center>
        <form class="form-signin" method="post">
          <h2>Registreren</h2>
          <!-- Als de registratie voltooid is (wordt in regel 94 bepaald), dan laat het systeem een bevesteging op het scherm zien. Ook krijgt de gebruiker het wachtwoord en wordt
          hij/zij aangeraden om in te loggen -->
          <?php if ($voltooid == 1) {
            echo '<div class="success">Aanmelding succesvol. Uw wachtwoord:<br> ' . $secretcode . '<br><br> <a href="?p=login">Log hier in!</a> </div><br>';
          } ?>
          <?php if ($voltooid == 0) {?>
          <!-- Als de gebruiker al een account heeft, dan kunnen ze via deze link naar de inlogpagina -->
          <a href="?p=login">Al lid? Log hier in!</a><br><br>
            <?php
                // Errorcode weergeven wanneer een email in bezet is
                if($emailcheck == 1 && isset($_POST['submit'])){
                    echo '<div class="error">Dit email adres is al in gebruik.</div>';
                }
            ?>
                <!-- Alle invoervelden voor het registreren. (zijn allemaal required) -->
                <input type="email" class="invoerveld" name="email" placeholder="Email" required autofocus><br><br>
                <input type="text" class="invoerveld" name="voornaam" placeholder="Voornaam" required><br><br>
                <input type="text" class="invoerveld" name="achternaam" placeholder="Achternaam" required><br><br>
                <input type="number" class="invoerveld" name="telefoonnummer" placeholder="Telefoonnummer" required><br><br>
                <input type="text" class="invoerveld" name="plaats" placeholder="Plaats" required><br><br>
                <input type="text" class="invoerveld" name="adres" placeholder="Adres" required><br><br>
                <input type="text" class="invoerveld" name="postcode" placeholder="Postcode" required><br><br>
            <br>
            <!-- De knop om de gegevens te versturen. Hierna worden de bovestaande systemen uitgevoerd om de gebruiker in de database te zetten. -->
            <button type="submit" name="submit" class="submit">Aanmelding voltooien</button>
            <?php } ?>
        </form>
	</center>
</div>