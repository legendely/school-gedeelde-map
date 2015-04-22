<?php 
//redirect functie wordt gedefineerd
function redirect_to($new_location)
{header("location: " . $new_location); 
exit;}

//Alleen beheerders en de expeditie hebben toegang tot deze pagina. Geen van deze? Terugsturen naar index.php
if($gegevens['Afdeling'] != '2'){
    if($gegevens['Afdeling'] != '1'){
	    header ('location: index.php');
	}
}

// Een individuele bestelling's status veranderen
if(isset($_GET['nummer'])){
	$bestelnummer = $_GET['nummer'];
	$query = "UPDATE Bestelling SET BEST_Status = 'afgerond' WHERE bestNR =" . $bestelnummer;
    $result = mysqli_query($con, $query);
}

?>

<div class="content">
<?php
	//de bestellingen die klaarstaan worden opgehaald
	$query  = "select bestNR, KL_Voornaam, KL_Achternaam, KL_Plaats, KL_Adres ";
	$query .= "from Bestelling b, Klant k ";
	$query .= "where k.Klantnr = b.Klantnr ";
	$query .= "and BEST_Status = 'bezorgen' ";
	$query .= "order by KL_Plaats; ";
	$result = mysqli_query($con, $query);
	
	if(mysqli_num_rows($result) == 0){
		echo "	<form action\"\" method=\"post\">
					<input type=\"submit\" name=\"herladen\" class=\"submit\" value=\"Opnieuw Checken\" /><br><br>
				</form>";
		if(isset($_POST["herladen"])){
			header("location: index.php?p=routeplanning");
		}
		die("Geen bestellingen klaar" . mysqli_connect_error());
	}
	//de bestellingen worden weergegeven in een tabel en de bestellingsnummers komen in de array $bestnr te staan
	echo "<table width=100%><tr><td><b>Bestelling</b></td><td><b>Voornaam</b></td><td><b>Achternaam</b></td><td><b>Plaats</b></td><td><b>Adres</b></td><td><b>Afronden</b></td></tr>";
	while($row = mysqli_fetch_assoc($result)){
		$bestnr[] = $row["bestNR"];
		echo "<tr><td>". $row["bestNR"]. "</td><td>". $row["KL_Voornaam"]. "</td><td>". $row["KL_Achternaam"]. "</td><td>". $row["KL_Plaats"]. "</td><td>" . $row["KL_Adres"] . "</td><td><a href=\"?p=routeplanning&nummer=" . $row["bestNR"] . "\">Aftekenen</a></td></tr>";
		;
	}
	echo '</table>';
	//de array $bestnr wordt in een session gezet om hem mee te geven aan de volgende pagina
	if(isset($bestnr)){
	$_SESSION['bestnr'] = $bestnr;
	}
	
?>
<!-- deze button stuurt je door naar de volgende pagina en geeft post de waarde "klaar" mee -->
<br>
<form action="?p=routeplanning2" method="post">
<input type="submit" name="done" class="submit" value="Alles aftekenen" />
</form>
&nbsp
<!-- als je op deze button drukt wordt de pagina geprint -->
<form action="" method="post">
<input type="button" class="submit" onClick="window.print()" value="Print"/>
</form>
</div>


