<div class="content">
    <?php

    if (isset($_POST['back'])) {
         header ('location: index.php');
    }

    // Winkelwagen is alleen te gebruiken voor klanten.
	if($_SESSION['soortgebruiker'] != "klant"){
		header ('location: index.php');
	}

    if(isset($_GET['res'])){
        if($_GET['res'] == 'success'){
            echo '<div class="success"><center>U heeft uw bestelling geplaatst!</center></div><br>';
        } else if($_GET['res'] == 'error'){
            echo '<div class="error"><center>Oeps er ging iets fout</center></div><br>';
        } else if($_GET['res'] == 'fail') {
            echo '<div class="success"><center>U kunt artikel '.$_GET['gid'].' niet meer bestellen, want die hebben wij niet meer op voorraad!</center></div><br>';
        }
    }

    if(isset($_POST["delete"])){
        $_SESSION['bes'] = null;
    }

    //alle orders die zijn besteld worden weergegeven
    if(isset($_SESSION['bes'])){
        foreach ($_SESSION['bes'] as $gerecht => $aantal) {
            $query2  = "select GER_Naam from Gerecht ";
            $query2 .= "where GerNR = $gerecht; ";
            $result2 = mysqli_query($con, $query2);
            if(!$result2){
                die("Database query failed");
            }
            while ($naam = mysqli_fetch_assoc($result2)) {
                echo "Het gerecht " . $naam['GER_Naam'] . " is " . $aantal. " keer door U besteld";
                echo "<hr/>";
            }
        }
//als er geen gerechten besteld zijn wordt dat weergegeven
    } else {
        echo "Je hebt nog geen gerechten besteld";
    }
    //als er gerechten besteld zijn wordt de verwijder knop weergegeven
    if(isset($_SESSION['bes'])){
        echo "		<form action=\"\" method=\"post\">
					<input type=\"submit\" name=\"delete\" class=\"submit\" value=\"Verwijderen\" />
				</form><br>";
    }


    ?>
    <!-- als er gerechten besteld zijn wordt de bevestig knop weergegeven-->
    <form action="" method="post">
        <?php 	if(isset($_SESSION['bes'])) {
            echo"<input type=\"submit\" name=\"confirm\" class=\"submit\" value=\"Bevestigen\"/>";
        } ?>
        <br><br><input type="submit" name="back" class="submit" value="Terug naar bestellen"/>
    </form>

    <?php
    //als er op bevestigen wordt gedrukt wordt de bestelling ingevoerd in de database
    if (isset($_POST["confirm"]) == "bevestigen") {
        if(isset($_SESSION['bes'])){
            foreach ($_SESSION['bes'] as $gerecht => $aantal) {
                $query = "SELECT * FROM Aantalingredienten a JOIN Artikelen i ON i.ArtNR = a.ArtNR WHERE a.GerNR = $gerecht;";
                $res2 = mysqli_query($con, $query);
                $stocked = 0;
                if (!$res2) continue;
                while ($row2 = mysqli_fetch_assoc($res2)) {
                    $voorraad = $row2['ART_TechnischeVoorraad'] - $row2['ART_Gereserveerd'];
                    if (($row2['ING_Aantal'] * $aantal) > $voorraad) {
                        header("location: index.php?p=winkelwagen&res=error&gid=$gerecht");
                    }
                }
            }
        }

        //query die de data in de bestelling tabel zet
        $query  = "insert into Bestelling (KlantNR, Best_Datum, BEST_Status) values ({$_SESSION['gegevens']['KlantNR']} ,str_to_date( '" . date('d-m-Y ') . "' , '%d-%m-%Y' ), 'besteld'); ";
        $result = mysqli_query($con, $query);
        if(!$result){
            header("location: index.php?p=winkelwagen&res=error");
        }

        $BestelNR = mysqli_insert_id($con);
    foreach ($_SESSION['bes'] as $gerecht => $aantal) {

        //query die de bij behorende data in de aantalverkocht tabel zet en aan de  bestelling tabel linkt
        $query3  = "insert into AantalVerkocht (GerNR, Aantal, BestNR) values ($gerecht, $aantal, $BestelNR);";
        $result3 = mysqli_query($con, $query3);
        if(!$result3){
            header("location: index.php?p=winkelwagen&res=error");
        }
        //query die het aantal ingredienten samen met het ingredientnummer ophaalt
        $query4  = "select ING_Aantal, ArtNR
            from Gerecht g, Aantalingredienten a
            where g.GerNR = a.GerNR
            and g.GerNR = $gerecht; ";
        $result4 = mysqli_query($con,$query4);
        if(!$result4){
            header("location: index.php?&winkelwagen&res=error");
        }
        while ($row = mysqli_fetch_assoc($result4)) {
            //query dat het aantal gereserveerd van het ingredient wordt opgehaald
            $query6  = "select ART_Gereserveerd from Artikelen where ArtNR =" . $row['ArtNR'] . ";";
            $result6 = mysqli_query($con, $query6);
            while ($gereserveerd = mysqli_fetch_assoc($result6)) {
                //query die het aantal gereserveerd aanpast
                $var1 = $gereserveerd['ING_Aantal'];
                $war2 = $gereserveerd['ART_Gereserveerd'];
                $var =  $var1 + $var2;
                $query5  = "update Artikelen
                        set ART_Gereserveerd = $var
                        where ArtNR = {$row['ArtNR']};";
            }
        }
    }
    unset($_SESSION['bes']);
    header("location: index.php?p=winkelwagen&res=success");
}

    ?>

    <!-- ADRES WIJZIGEN -->
<?php

// Een functie die ervoor zorgt dat de Klantgegevens worden aangepast in de database
function update_klant($name, $email, $con, $newvalue){
	// De update query
	$query = "UPDATE Klant SET " . $name . "= '" . $newvalue . "' WHERE KL_Mail='" . $email . "'";
	$result = mysqli_query($con, $query);

	// Aangeven dat de aanpassingen succesvol zijn
	echo '<center><div class="success">' . ucfirst($name) . ' is succesvol aangepast!</div></center>';
}


// Als er een klant is ingelogd, dan krijgt hij/zij het volgende te zien:
if($_SESSION['soortgebruiker'] == "klant"){
	// print_r($gegevens);
	if(isset($_POST['submit_settings'])){

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
	}		
?>

	<!-- De content. Hier komt alle inhoud van de site. -->
	<div class="content">
		<!-- Het formulier -->
		<h2>Adresgegevens wijzigen (optioneel)</h2>
		<!-- Alle invoervelden voor het wijzigen van de persoonlijke gegevens. Niets is required, dus velden kunnen ook worden overgeslagen als ze hetzelfde moeten blijven. -->
		<form class="form-signin" name="changedata" method="post" action="?p=winkelwagen">
		Plaats:<br><input type="text" class="invoerveld" name="plaats" placeholder=<?php echo '"' . $gegevens['KL_Plaats'] . '"'; ?>><br><br>
		Adres + Huisnummer:<br><input type="text" class="invoerveld" name="adres" placeholder=<?php echo '"' . $gegevens['KL_Adres'] . '"'; ?>><br><br>
		Postcode:<br><input type="text" class="invoerveld" name="postcode" placeholder=<?php echo '"' . $gegevens['KL_Postcode'] . '"'; ?>><br><br>

		<!-- Het formulier verzenden. -->
		<button type="submit" name="submit_settings" class="submit">Wijzigingen opslaan</button>
		</form>
	</div>
<?php } ?>

</div>	
</div>