<?php
// De variabelen die in de invoervelden worden aangepast
$l_email = isset($_POST['l_email']) ? $_POST['l_email'] : "";
$l_password = isset($_POST['l_password']) ? $_POST['l_password'] : "";
$afdeling = '';

// Als iemand al ingelogd is, dan stuur hem terug naar de index.php pagina
if(isset($_SESSION['gegevens'])){
    header ('location: index.php');
}

if (isset($_POST['l_submit'])) {

	// Het wachtwoord wordt uit de database gehaald m.b.v het ingevulde email adres
	$query = "SELECT MED_Wachtwoord FROM Medewerkers WHERE MED_Mail = '" . $l_email . "' ";
	$result = mysqli_query($con, $query);
	$password = '';

    // Het wachtwoord in een variabel opslaan
	while($row = mysqli_fetch_array($result)) {
		$password = $row['MED_Wachtwoord'];
	}

    // Het nummer van de afdeling van een gebruiker uit de database halen. Dit is nodig om later te kijken of de gebruiker op actief staat.
    $query = "SELECT * FROM Medewerkers WHERE MED_Mail = '" . $l_email . "' ";
    $result = mysqli_query($con, $query);

    // Afdeling in een variabel opslaan
    while($row = mysqli_fetch_array($result)) {
        $afdeling = $row['Afdeling'];
    }
}
    
?>
<center>
<div class="content">
        <form class="form-signin" method="post">
            <h2>Inloggen Medewerker</h2>
            <?php
            if(isset($_POST['l_submit']) && $password == $l_password){
                // Als je account op inactief is geplaatst, kun je niet meer inloggen.
                if($afdeling == NULL){
                    echo '<div class="error">Uw account is op inactief gezet.</div><br>';
                }

                // Als de afdeling geen NULL is en het wachtwoord klopt, dan wordt de gebruiker succesvol ingelogd.
                if($afdeling != NULL){
                    echo '<div class="success">Succesvol ingelogd!</div><br>';
                    // Alle gegevens van de ingelogde gebruiker opslaan in een sessie
                    $query = "SELECT * FROM Medewerkers WHERE MED_Mail = '" . $l_email . "' ";
                    $result = mysqli_query($con, $query);
                    $_SESSION['soortgebruiker'] = "medewerker";
                    $_SESSION['gegevens'] = mysqli_fetch_array($result);
                    header ('location: index.php');
                }

                // Als het ingevulde wachtwoord niet klopt, dan moet er een foutmelding weergegeven worden.
                }elseif(isset($_POST['l_submit']) && $password != $l_password){
                    // Foutmelding aangeven wanneer het ingevulde wachtwoord niet klopt
                    echo '<div class="error">Onjuiste gegevens ingevoerd</div><br>';
                }
            ?>
            <!-- Het formulier -->
            <input type="email" class="invoerveld" name="l_email" placeholder="Email" required autofocus value=<?php echo '"' . $l_email . '"'; ?>><br><br>
            <input type="password" class="invoerveld" name="l_password" placeholder="Wachtwoord" required value=<?php echo '"' . $l_password . '"'; ?>><br><br>
            <br>
            <button type="submit" name="l_submit" class="submit">Inloggen</button>
        </form>
</div>
</center>