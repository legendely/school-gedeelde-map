<?php
// Als iemand al ingelogd is, dan stuur hem terug naar de index.php pagina
if(isset($_SESSION['gegevens'])){
    header ('location: index.php');
}

// De variabelen die in de invoervelden worden aangepast
$l_email = isset($_POST['l_email']) ? $_POST['l_email'] : "";
$l_password = isset($_POST['l_password']) ? $_POST['l_password'] : "";

if (isset($_POST['l_submit'])) {
	// Kijken of het email adres al in de database staat. Dit wordt gedaan door het ingevulde email adres m.b.v een WHERE en een COUNT statement te tellen. Als er 0 zijn, dan kan de persoon zich registeren. Als het op 1 staat, dan volgt er een error.
	$query = "SELECT KL_Wachtwoord FROM Klant WHERE KL_Mail = '" . $l_email . "' ";
	$result = mysqli_query($con, $query);
	$password = '';

	while($row = mysqli_fetch_array($result)) {
		$password = $row['KL_Wachtwoord'];
	}
}
?>

<!-- Alles wordt gecentreerd. -->
<center>
    <div class="content">
        <form class="form-signin" method="post">
            <h2>Inloggen</h2>
            <?php
                if(isset($_POST['l_submit']) && $password == $l_password){
                    echo '<div class="success">Succesvol ingelogd!</div><br>';

                    // Alle gegevens van de ingelogde gebruiker opslaan in een sessie
                    $query = "SELECT * FROM Klant WHERE KL_Mail = '" . $l_email . "' ";
                    $result = mysqli_query($con, $query);
                    $_SESSION['soortgebruiker'] = "klant";
                    $_SESSION['gegevens'] = mysqli_fetch_array($result);

                    // Als een gebruiker succesvol is ingelogd, dan doorsturen naar de homepage.
                    header ('location: index.php');

                // Een foutmelding weergeven als de ingevoerde gegevens niet kloppen.
                }elseif(isset($_POST['l_submit']) && $password != $l_password){
                    echo '<div class="error">Onjuiste gegevens ingevoerd</div><br>';
                }
            ?>
            
            <!-- Een link plaatsen die verwijst naar de registreer pagina -->
            <a href="?p=register">Nog geen lid? Registreer je hier!</a><br><br>

            <!-- Email en wachtwoord veld -->
            <input type="email" class="invoerveld" name="l_email" placeholder="Email" required autofocus value=<?php echo '"' . $l_email . '"'; ?>><br><br>
            <input type="password" class="invoerveld" name="l_password" placeholder="Wachtwoord" required value=<?php echo '"' . $l_password . '"'; ?>><br><br>
            <br>
            <button type="submit" name="l_submit" class="submit">Inloggen</button>
        </form><br><br>

        <!-- Een link naar de login van de medewerkers plaatsen -->
        <a href="?p=login_medewerker">Medewerkersportaal</a>
    </div>
</center>