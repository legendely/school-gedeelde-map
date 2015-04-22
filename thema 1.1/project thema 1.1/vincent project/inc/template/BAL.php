<div class="content">
<?php
	//Alleen beheerders en de inkoop afdeling hebben toegang tot deze pagina. Geen van deze? Terugsturen naar index.php
	if($gegevens['Afdeling'] != '7'){
	    if($gegevens['Afdeling'] != '1'){
	        header ('location: index.php');
	    }
	}
	//Opstellen BAL
	$economische_voorraad;
	$bestelniveau;
	
	//Qeury opzoeken bestelniveau
	$query = "SELECT ArtNR, ART_Naam, ART_TechnischeVoorraad, ART_InBestelling, ART_Gereserveerd, ART_BestelNiveau ";
	$query .= "FROM Artikelen ";
	
	//Resultaat query
	$result = mysqli_query($con, $query);
	
	//Wordt gekeken of de query werkt.
	if (!$result) {
		die("Query werkt niet");
	}
	
	//Query wordt in een array gezet.
	while ($artikelen = mysqli_fetch_assoc($result)) {
        $bestelniveau = $artikelen["ART_BestelNiveau"];
        $TV = $artikelen["ART_TechnischeVoorraad"];
        $IB = $artikelen["ART_InBestelling"];
        $GR = $artikelen["ART_Gereserveerd"];
        $naam = $artikelen["ART_Naam"];
        $nr = $artikelen["ArtNR"];

        $economische_voorraad = $TV + $IB - $GR;
        if ($economische_voorraad <= $bestelniveau) {
            $aantal_bestellen = $bestelniveau - $economische_voorraad;
            if ($aantal_bestellen > 0 ) {
                $bestellen[] = array('ArtikelNR' => $nr, 'Naam' => $naam, 'Aantal' => $aantal_bestellen);
            }
        }
	}
	
	//Wordt bekeken of er artikelen bij moet worden bestelt. Zo ja wordt er een overzicht weergegeven.
	if (!empty($bestellen)) {
		echo "Het volgende product of de volgende producten moeten worden bestelt:</br>";
		echo "</br>";
		echo "<table border=1><tr><td>Artikel nummer</td><td>Artikel naam</td><td>Aantal</td></tr>";
		foreach ($bestellen as $i) {
			echo "<tr><td>". $i['ArtikelNR']. "</td><td>". $i['Naam']. "</td><td>". $i['Aantal']. "</td></tr>";
		}
		echo "</table>";
	}
	//Als er geen artikelen moeten worden besteld wordt het onderstaande weergegeven.
	if (empty($bestellen)) {
		echo "Er zijn geen artikelen die moeten worden bijbesteld.";
	}
	
?>
</div>